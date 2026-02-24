<?php
// CONFIG
define('BOT_TOKEN', '8456038801:AAEFK9TgjPRfT2wI9B3Ps5r-u4Tk6IaQxQM');
define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');
define('AUTH_API', 'https://PavanCashLoot.xyz/Loots/alldata.php');

// INPUT
$content = file_get_contents("php://input");
$update = json_decode($content, true);
$message = $update['message'] ?? [];
$text = $message['text'] ?? '';
$chat_id = $message['chat']['id'] ?? '';
$user_id = $message['from']['id'] ?? '';
$stepFile = "step/$chat_id.json";

// === STORE CHAT ID ===
if (!file_exists("users")) mkdir("users", 0777, true);
$chatIDsFile = "users/chat_ids.json";
$chatIDs = file_exists($chatIDsFile) ? json_decode(file_get_contents($chatIDsFile), true) : [];

if (!in_array($chat_id, $chatIDs)) {
    $chatIDs[] = $chat_id;
    file_put_contents($chatIDsFile, json_encode($chatIDs));
}

// === STEP SYSTEM ===
function saveStep($chat_id, $data) {
    file_put_contents("step/$chat_id.json", json_encode($data));
}
function loadStep($chat_id) {
    return file_exists("step/$chat_id.json") ? json_decode(file_get_contents("step/$chat_id.json"), true) : [];
}

// === SEND MESSAGE ===
function sendMessage($chat_id, $text, $buttons = null, $isInline = false) {
    $data = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    if ($buttons) {
        $markup = $isInline
            ? ['inline_keyboard' => $buttons]
            : ['keyboard' => $buttons, 'resize_keyboard' => true];
        $data['reply_markup'] = json_encode($markup);
    }
    file_get_contents(API_URL . "sendMessage?" . http_build_query($data));
}

/*// === START ===
if ($text === '/start') {
    sendMessage($chat_id, "<b>👋 Welcome to Pavan Cash Loot</b>\n\n<b>Choose an option:</b>", [
        ['📝 Register', '🔐 Login'],
        ['🔑 Forgot Password', '🗑️ Delete Account']
    ]);
    saveStep($chat_id, ['step' => 'start']);
    return;
}

$data = loadStep($chat_id);
$step = $data['step'] ?? '';
*/

// === LOGIN CHECK ===
function checkUserLogin($chat_id) {
    $file = __DIR__ . "/users/{$chat_id}.json";
    if (file_exists($file)) {
        $user = json_decode(file_get_contents($file), true);
        return isset($user['logged_in']) && $user['logged_in'] === true;
    }
    return false;
}

// Load current step
$data = loadStep($chat_id);
$step = $data['step'] ?? '';

// === START ===
if ($text === '/start') {
    if (checkUserLogin($chat_id)) {
        sendMessage($chat_id, "<b>👋 Welcome back to Pavan Cash Loot</b>\n\n<b>Choose an option:</b>", [
            ['💰 My Wallet', '🎁 Daily Bonus'],
            ['📜 My Transactions', '⚙️ Settings'],
            ['🚪 Logout']
        ]);
        saveStep($chat_id, ['step' => 'dashboard']);
    } else {
        sendMessage($chat_id, "<b>👋 Welcome to Pavan Cash Loot</b>\n\n<b>Choose an option:</b>", [
            ['📝 Register', '🔐 Login'],
            ['🔑 Forgot Password', '🗑️ Delete Account']
        ]);
        saveStep($chat_id, ['step' => 'start']);
    }
    return;
}

// === REGISTER FLOW ===
// ... [Same register/login/reset code as yours — unchanged for brevity] ...

// REGISTER FLOW 
/*
if ($text === '📝 Register') {
    sendMessage($chat_id, "<b>Enter your desired username (a–z only):</b>");
    saveStep($chat_id, ['step' => 'register_username']);
}
elseif ($step === 'register_username') {
    if (!preg_match('/^[a-z]+$/', $text)) {
        sendMessage($chat_id, "<b>❌ Invalid username.</b>\nUse only lowercase letters a–z. No numbers, spaces, or uppercase.");
        return;
    }
*/
    //foreach (glob("users/*/*.json") as $file) {
       /* $user = json_decode(file_get_contents($file), true);
        if (isset($user['username']) && strtolower($user['username']) === strtolower($text)) {
            sendMessage($chat_id, "<b>⚠️ Username already taken. Try another one.</b>");
            return;
        }
    }

    $data['username'] = $text;
    $data['step'] = 'register_name';
    sendMessage($chat_id, "<b>Enter your Full Name:</b>");
    saveStep($chat_id, $data);
}
elseif ($step === 'register_name') {
    $data['name'] = $text;
    $data['step'] = 'register_number';
    sendMessage($chat_id, "<b>Enter your 10-digit Mobile Number:</b>");
    saveStep($chat_id, $data);
}
elseif ($step === 'register_number') {
    if (!preg_match('/^[6-9]\d{9}$/', $text)) {
        sendMessage($chat_id, "<b>❌ Invalid number. Please enter a valid 10-digit Indian mobile number.</b>");
        return;
    }
    $data['number'] = $text;
    $data['step'] = 'register_email';
    sendMessage($chat_id, "<b>Enter your Email ID:</b>");
    saveStep($chat_id, $data);
}
elseif ($step === 'register_email') {
    $data['email'] = $text;
    $data['step'] = 'register_password';
    sendMessage($chat_id, "<b>Set your Password:</b>");
    saveStep($chat_id, $data);
}
elseif ($step === 'register_password') {
    $data['password'] = $text;

    $postData = [
        'work' => 'register',
        'username' => $data['username'],
        'name'     => $data['name'],
        'number'   => $data['number'],
        'email'    => $data['email'],
        'password' => $data['password']
    ];

    $ch = curl_init(AUTH_API);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($response, true);
    
    if ($res['status'] === 'success') {
        sendMessage($chat_id, "<b>✅ Registration Successful!</b>", [
            [['text' => '🔐 Login', 'url' => 'https://PavanCashLoot.xyz/Loots/login.php']]
        ], true);
    } else {
        sendMessage($chat_id, "<b>❌ " . ($res['message'] ?? 'Registration failed.') . "</b>");
    }

    unlink($stepFile);
    return;
}
*/

// LOGIN FLOW
if ($text === '🔐 Login') {
    sendMessage($chat_id, "<b>Enter your Mobile Number or Email:</b>");
    saveStep($chat_id, ['step' => 'login_id']);
}
elseif ($step === 'login_id') {
    $data['id'] = $text;
    $data['step'] = 'login_password';
    sendMessage($chat_id, "<b>Enter your Password:</b>");
    saveStep($chat_id, $data);
}
elseif ($step === 'login_password') {
    $postData = [
        'work' => 'login',
        'id'   => $data['id'],
        'pass' => $text
    ];

    $ch = curl_init(AUTH_API);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($response, true);

    if ($res['status'] === 'success') {
        $name = $res['user']['name'] ?? 'User';
        sendMessage($chat_id, "✅ <b>Welcome back, $name!</b>", [
            [['text' => '🌐 Open Dashboard', 'url' => 'https://PavanCashLoot.xyz/Loots/dashboard.php']]
        ], true);
          // Save login status
        file_put_contents("users/{$chat_id}.json", json_encode([
            'logged_in' => true,
            'user' => $res['user']
        ]));

        sendMessage($chat_id, "✅ <b>Welcome back, $name!</b>", [
            ['💰 My Wallet', '🎁 Daily Bonus'],
            ['📜 My Transactions', '⚙️ Settings'],
            ['🚪 Logout']
        ]);
    } else {
        sendMessage($chat_id, "<b>❌ " . ($res['message'] ?? 'Login failed.') . "</b>");
    }

    unlink($stepFile);
    return;
}


// === LOGOUT ===
if ($text === '🚪 Logout') {
    $file = "users/{$chat_id}.json";
    if (file_exists($file)) unlink($file);
    sendMessage($chat_id, "<b>✅ You have been logged out.</b>", [
        ['📝 Register', '🔐 Login'],
        ['🔑 Forgot Password', '🗑️ Delete Account']
    ]);
    saveStep($chat_id, ['step' => 'start']);
    return;
}

// FORGOT PASSWORD FLOW
if ($text === '🔑 Forgot Password') {
    sendMessage($chat_id, "<b>🔑 Enter your registered Mobile Number:</b>");
    saveStep($chat_id, ['step' => 'reset_number']);
    return;
}
elseif ($step === 'reset_number') {
    if (!preg_match('/^[6-9]\d{9}$/', $text)) {
        sendMessage($chat_id, "<b>❌ Invalid number. Please enter a valid 10-digit Indian mobile number.</b>");
        return;
    }

    $data['number'] = $text;
    $data['step'] = 'reset_password';
    sendMessage($chat_id, "<b>🔐 Enter your new password:</b>");
    saveStep($chat_id, $data);
}
elseif ($step === 'reset_password') {
    $number = $data['number'];
    $newpass = $text;

    $postData = [
        'work' => 'reset',
        'number' => $number,
        'password' => $newpass
    ];

    $ch = curl_init(AUTH_API);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($response, true);

    if ($res['status'] === 'success') {
        sendMessage($chat_id, "<b>✅ Password reset successful!</b>\n\nYou can now login using your new password or open the dashboard directly:", [
            [['text' => '🌐 Open Dashboard', 'url' => 'https://PavanCashLoot.xyz/Loots/dashboard.php']],
            [['text' => '🔐 Login Again']]
        ], true);
    } else {
        sendMessage($chat_id, "<b>❌ " . ($res['message'] ?? 'Password reset failed.') . "</b>");
    }

    unlink($stepFile);
    return;
}



// === DELETE PLACEHOLDER ===
if ($text === '🗑️ Delete Account') {
    sendMessage($chat_id, "<b>⚠️ Delete account feature is under development. Please check back later.</b>");
}

// === BROADCAST (ADMIN ONLY) ===
$admin_id = '8464827200'; // Replace with your real Telegram user ID

if ($text === '/broadcast' && $chat_id == $admin_id) {
    sendMessage($chat_id, "<b>📢 Please send the broadcast message:</b>\nYou can send plain text or a photo with caption.");
    saveStep($chat_id, ['step' => 'broadcast']);
    return;
}

if ($step === 'broadcast' && $chat_id == $admin_id) {
    $chatIDs = file_exists($chatIDsFile) ? json_decode(file_get_contents($chatIDsFile), true) : [];

    if (isset($message['photo'])) {
        $photo = end($message['photo']);
        $file_id = $photo['file_id'];
        $caption = "<b>" . ($message['caption'] ?? '📢 Broadcast') . "</b>";

        foreach ($chatIDs as $id) {
            file_get_contents(API_URL . "sendPhoto?" . http_build_query([
                'chat_id' => $id,
                'photo' => $file_id,
                'caption' => $caption,
                'parse_mode' => 'HTML'
            ]));
        }
    } elseif (!empty($text)) {
        foreach ($chatIDs as $id) {
            sendMessage($id, "<b>$text</b>");
        }
    }

    sendMessage($chat_id, "<b>✅ Broadcast sent to " . count($chatIDs) . " users.</b>");
    unlink($stepFile);
    return;
}
?>