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

    if (!$username || !$name || !$number || !$email || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit;
    }

    if (!preg_match('/^[6-9]\d{9}$/', $number)) {
        echo json_encode(['status' => 'error', 'message' => 'Enter a valid 10-digit Indian mobile number']);
        exit;
    }

    $userDir  = "users/$number";
    $userFile = "$userDir/$number.json";

    if (file_exists($userFile)) {
        echo json_encode(['status' => 'error', 'message' => 'Mobile number already registered']);
        exit;
    }

    foreach (glob("users/*/*.json") as $file) {
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
        'chat_id'  => $chat_id ?? "",
        'banned'     => false,
        'role'       => "user",
        'created_at' => date("d-m-Y h:i A")
    ];

    file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT));

    // Create wallet balance file
    $walletFile = "$userDir/$number.txt";
    if (!file_exists($walletFile)) {
        file_put_contents($walletFile, "0");
    }
    

    
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
      <p><b>💝 PCL  – Team</b></p>
    </div>

    <!-- CTA Button -->
    <div style="text-align: center; padding: 20px;">
      <a href="https://pavancashloot.xyz/Loots/login.php?login" target="_blank" style="background-color: #2c3e50; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
        🔐 Login to Your Account
      </a>
    </div>

    <!-- Footer -->
    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;"> 
    <p>🔒  <b>	&copy; '. date('Y') .' Pavan Cash Loot</b></p>
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

echo json_encode(['status' => 'success', 'message' => 'Registered successfully']);

    exit;
    
}


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
                $_SESSION['user'] = $user;

                $number = $user['number'];
                $email = $user['email'];
                $name  = $user['name'];
                    // Optional: store IP to prevent hijacking
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];

                $walletFile = "users/{$number}/{$number}.txt";
                $balance = file_exists($walletFile) ? trim(file_get_contents($walletFile)) : "0";

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
                      <p><b>💝 PCL – Team</b></p>
                    </div>
                    <div style="text-align: center; padding: 20px;">
                      <a href="https://PavanCashLoot.xyz/Loots/dashboard.php" target="_blank" style="background-color: #2c3e50; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                        🪴 Go To Dashboard 
                      </a>
                    </div>
                    <div style="background-color: #2c3e50; color: #ffffff; text-align: center; padding: 15px; font-size: 14px;"> 
                      <p>🔒  <b>&copy; '. date('Y') .' Pavan Cash Loot</b></p>
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