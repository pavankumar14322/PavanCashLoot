
<?php
$botToken = "8291217453:AAFBfpZXbjvv8oQxsr4Bnoki6uEmbgAaq4Q";
$AUTH_API = "https://siam.lexynova.com/Loots/alldata.php";
$API_URL  = "https://api.telegram.org/bot$botToken";
$admin_id = '5478832701';

// === Update data ===
$content = file_get_contents("php://input");
$update  = json_decode($content, true);
if (!$update) exit;

$message  = $update["message"] ?? [];
$text     = trim($message["text"] ?? '');
$chat_id  = $message["chat"]["id"] ?? null;


// === Step Handler ===
function getStep($chat_id) {
    $file = "steps/$chat_id.json";
    if (file_exists($file)) return json_decode(file_get_contents($file), true);
    return ['step' => null];
}
function saveStep($chat_id, $data) {
    if (!is_dir("steps")) mkdir("steps", 0777, true);
    file_put_contents("steps/$chat_id.json", json_encode($data, JSON_PRETTY_PRINT));
}
function clearStep($chat_id) {
    $file = "steps/$chat_id.json";
    if (file_exists($file)) unlink($file);
}

// === Send Message ===
function sendMessage($chat_id, $message, $keyboard = null, $html = false, $inline = false) {
    global $API_URL;

    $post_fields = [
        'chat_id'    => $chat_id,
        'text'       => $message,
        'parse_mode' => $html ? 'HTML' : 'Markdown'
    ];

    if ($keyboard) {
        if ($inline) {
            $post_fields['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
        } else {
            $post_fields['reply_markup'] = json_encode([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]);
        }
    }

    $ch = curl_init("$API_URL/sendMessage");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_exec($ch);
    curl_close($ch);
}



// === /start Command ===
if (strpos($text, "/start") === 0) {
    $parts = explode(" ", $text);
    if (count($parts) == 2) {
        $number = trim($parts[1]);
        if (!is_dir("users/$number")) mkdir("users/$number", 0777, true);
        $user_file = "users/$number/$number.json";
        $user_data = file_exists($user_file) ? json_decode(file_get_contents($user_file), true) : [
            "username" => "",
            "name"     => "",
            "number"   => $number,
            "email"    => "",
            "password" => "",
            "chat_id"  => "",
            "banned"   => false
        ];
        $user_data['chat_id'] = $chat_id;
        file_put_contents($user_file, json_encode($user_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        sendMessage($chat_id, "<b>❤️ Your Chat ID has been linked to PCL Account Number: $number</b>", null, true);
// === Alert Admin about New User ===
$admin_id = "5478832701"; // replace with your real Admin chat_id

$first_name = $update['message']['from']['first_name'] ?? '';
$last_name  = $update['message']['from']['last_name'] ?? '';
$username   = $update['message']['from']['username'] ?? '';

$full1name = trim($first_name . " " . $last_name);

$alertMsg = "📢 <b>New User Started the Bot\n\n"
          . "👤 Name:</b> <a href='tg://user?id=$chat_id'>" . htmlspecialchars($full1name) . "</a>\n"
          . "<b>💬 Username: @" . ($username ?: "N/A") . "\n"
          . "📱 Account Number: $number\n"
          . "🆔 Chat ID:</b> <code>$chat_id</code></b>";

sendMessage($admin_id, $alertMsg, null, true);
        
    } else {
        $inlineKeyboard = [
            [
                ['text' => '🔐 Login', 'url' => 'https://siam.lexynova.com/Loots/login.php'],
                ['text' => '📢 Join Updates', 'url' => 'https://t.me/smartxlifafa']
            ],
            [
                ['text' => "📍 Earn More", 'callback_data' => '#'],
                ['text' => '📊 Statistics', 'callback_data' => 'stats']
            ]
        ];
        sendMessage(
            $chat_id,
            "<b>👋 To link your account, click the button again.\n\n👉 Join Updates Channel:- @PCLWebsite</b>",
            $inlineKeyboard,
            true,
            true
        );
    }
}

    
// === Login Flow ===
$stepData = getStep($chat_id);
$step     = $stepData['step'] ?? null;
$data     = $stepData;

if ($text === '🔐 Login') {
    sendMessage($chat_id, "<b>Enter your Mobile Number or Email:</b>", null, true);
    saveStep($chat_id, ['step' => 'login_id']);
}
elseif ($step === 'login_id') {
    $data['id'] = $text;
    $data['step'] = 'login_password';
    sendMessage($chat_id, "<b>Enter your Password:</b>", null, true);
    saveStep($chat_id, $data);
}
elseif ($step === 'login_password') {
    $postData = ['work' => 'login', 'id' => $data['id'], 'pass' => $text];
    $ch = curl_init($AUTH_API);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $response = curl_exec($ch);
    curl_close($ch);
    $res = json_decode($response, true);

    if ($res['status'] === 'success') {
        $name = $res['user']['name'] ?? 'User';
        sendMessage($chat_id, "<b>✅ Welcome back, $name!</b>", null, true);
        file_put_contents("users/{$chat_id}.json", json_encode(['logged_in' => true, 'user' => $res['user']]));
    } else {
        sendMessage($chat_id, "<b>❌ " . ($res['message'] ?? 'Login failed.') . "</b>", null, true);
    }
    clearStep($chat_id);
}

// === Logout ===
if ($text === '🚪 Logout') {
    $file = "users/{$chat_id}.json";
    if (file_exists($file)) unlink($file);
    sendMessage($chat_id, "<b>✅ You have been logged out.</b>", [
        ['📝 Register', '🔐 Login'],
        ['🔑 Forgot Password', '🗑️ Delete Account']
    ], true);
    clearStep($chat_id);
}

// === Save Chat IDs ===
$chatIDsFile = "users/chat_ids.json";
if (!is_dir("users")) mkdir("users", 0777, true);
$chatIDs = file_exists($chatIDsFile) ? json_decode(file_get_contents($chatIDsFile), true) : [];
if (!in_array($chat_id, $chatIDs)) {
    $chatIDs[] = $chat_id;
    file_put_contents($chatIDsFile, json_encode($chatIDs, JSON_PRETTY_PRINT));
}

// === Broadcast ===
if ($text === '/broadcast' && $chat_id == $admin_id) {
    sendMessage($chat_id, "<b>📢 Send your broadcast message or media</b>", null, true);
    saveStep($chat_id, ['step' => 'broadcast']);
}
elseif ($step === 'broadcast' && $chat_id == $admin_id) {
    foreach ($chatIDs as $id) {
        if (isset($message['photo'])) {
            $photo = end($message['photo']);
            file_get_contents("$API_URL/sendPhoto?" . http_build_query([
                'chat_id' => $id,
                'photo'   => $photo['file_id'],
                'caption' => "<b>". $message['caption']. "</b>" ?? '',
                'parse_mode' => 'HTML'
            ]));
        }
        elseif (isset($message['video'])) {
            file_get_contents("$API_URL/sendVideo?" . http_build_query([
                'chat_id' => $id,
                'video'   => $message['video']['file_id'],
                'caption' => "<b>".$message['caption']."</b>" ?? '',
                'parse_mode' => 'HTML'
            ]));
        }
        elseif (isset($message['audio'])) {
            file_get_contents("$API_URL/sendAudio?" . http_build_query([
                'chat_id' => $id,
                'audio'   => $message['audio']['file_id'],
                'caption' => "<b>".$message['caption']."</b>" ?? '',
                'parse_mode' => 'HTML'
            ]));
        }
        elseif (!empty($text)) {
            sendMessage($id, "<b>".$text."</b>", null, true);
        }
    }
    sendMessage($chat_id, "<b>✅ Broadcast sent to " . count($chatIDs) . " users.</b>", null, true);
    clearStep($chat_id);
}



/* Optional: fetch balance from your API (uncomment & adapt if your API supports it)
function fetchBalanceFromAPI($number) {
    global $AUTH_API;
    $post = ['work' => 'get_balance', 'number' => $number];
    $ch = curl_init($AUTH_API);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $resp = curl_exec($ch);
    curl_close($ch);
    $j = json_decode($resp, true);
    if (is_array($j) && isset($j['status']) && $j['status'] === 'success') {
        return (string)($j['balance'] ?? $j['user']['balance'] ?? '0');
    }
    return null;
}
*/
// === /post Command (ADMIN ONLY) ===
if ($text === '/post' && $chat_id == $admin_id) {
    sendMessage($chat_id, "<b>📢 Send your channel post (text or media)</b>", null, true);
    saveStep($chat_id, ['step' => 'post']);
}
elseif ($step === 'post' && $chat_id == $admin_id) {
    $postedTo = [];

    // Collect all channels from every user's channels/*.json
    $channels = [];
    foreach (glob("users/*/channels/*.json") as $file) {
        $data = json_decode(file_get_contents($file), true);
        if (is_array($data)) {
            foreach ($data as $ch) {
                $username = $ch['username'] ?? $ch;
                if ($username && !in_array($username, $channels)) {
                    // Ensure correct format
                    if (strpos($username, '@') !== 0 && !is_numeric($username)) {
                        $username = '@'.$username; 
                    }
                    $channels[] = $username;
                }
            }
        }
    }

    // Post to each channel
    foreach ($channels as $channel) {
        if (isset($message['photo'])) {
            $photo = end($message['photo']);
            $res = file_get_contents("$API_URL/sendPhoto?" . http_build_query([
                'chat_id'    => $channel,
                'photo'      => $photo['file_id'],
                'caption'    => $message['caption'] ? "<b>".$message['caption']."</b>" : '',
                'parse_mode' => 'HTML'
            ]));
        }
        elseif (isset($message['video'])) {
            $res = file_get_contents("$API_URL/sendVideo?" . http_build_query([
                'chat_id'    => $channel,
                'video'      => $message['video']['file_id'],
                'caption'    => $message['caption'] ? "<b>".$message['caption']."</b>" : '',
                'parse_mode' => 'HTML'
            ]));
        }
        elseif (isset($message['audio'])) {
            $res = file_get_contents("$API_URL/sendAudio?" . http_build_query([
                'chat_id'    => $channel,
                'audio'      => $message['audio']['file_id'],
                'caption'    => $message['caption'] ? "<b>".$message['caption']."</b>" : '',
                'parse_mode' => 'HTML'
            ]));
        }
        elseif (!empty($text)) {
            $res = file_get_contents("$API_URL/sendMessage?" . http_build_query([
                'chat_id'    => $channel,
                'text'       => "<b>".$text."</b>",
                'parse_mode' => 'HTML'
            ]));
        }

        $r = json_decode($res, true);
        if (!empty($r['ok'])) {
            $postedTo[] = "✅ $channel";
        } else {
            $postedTo[] = "❌ $channel (" . ($r['description'] ?? 'error') . ")";
        }
    }

    // Report back
    $summary = "<b>📢 Post Summary:</b>\n\n";
    foreach ($postedTo as $c) $summary .= "• $c\n";

    sendMessage($chat_id, $summary, null, true);
    clearStep($chat_id);
}



// === /allchannels Command (ADMIN ONLY) ===
if ($text === '/allchannels' && $chat_id == $admin_id) {
    $msg = "<b>📢 All Users & Their Channels</b>\n\n";

    // Loop through all user folders
    foreach (glob("users/*", GLOB_ONLYDIR) as $userDir) {
        $userNumber = basename($userDir);

        // Skip invalid folders
        if (!is_numeric($userNumber)) continue;

        $channelsFile = "users/$userNumber/channels/$userNumber.json";
        if (file_exists($channelsFile)) {
            $channels = json_decode(file_get_contents($channelsFile), true);

            if (is_array($channels) && count($channels) > 0) {
                $msg .= "👤 <b>User:</b> <code>$userNumber</code>\n";
                foreach ($channels as $ch) {
                    // Handle array format or plain string
                    $username = is_array($ch) ? ($ch['username'] ?? '') : $ch;
                    if ($username) {
                        if (strpos($username, '@') !== 0) $username = '@'.$username;
                        $msg .= "   ➡️ <b>$username</b>\n";
                    }
                }
                $msg .= "\n";
            }
        }
    }

    if (trim($msg) === "<b>📢 All Users & Their Channels</b>") {
        $msg .= "\n⚠️ <b>No channels found yet.</b>";
    }

    sendMessage($chat_id, $msg, null, true);
}


// === /statistics Command (ADMIN ONLY) ===
if ($text === '/statistics' && $chat_id == $admin_id) {

    $totalUsers = 0;

    $addFunds = [
        'total'=>0,'approved'=>0,'pending'=>0,'rejected'=>0,
        'amt_total'=>0,'amt_approved'=>0,'amt_pending'=>0,'amt_rejected'=>0
    ];
    $withdrawals = [
        'total'=>0,'approved'=>0,'pending'=>0,'rejected'=>0,
        'amt_total'=>0,'amt_approved'=>0,'amt_pending'=>0,'amt_rejected'=>0
    ];

    // === Count Users ===
    foreach (glob("users/*/*.json") as $file) {
        if (preg_match("/users\/(\d+)\/\\1\.json$/", $file)) {
            $totalUsers++;
        }
    }

// === Count Add Funds Requests ===
foreach (glob("users/*/Rq-Add/*/*.json", GLOB_BRACE) as $file) {
    if (!file_exists($file)) continue;
    $data = json_decode(file_get_contents($file), true);
    if (!$data) continue;

    $amount = (float)($data['amount'] ?? 0);
    $status = strtolower(trim($data['status'] ?? 'pending'));

    $addFunds['total']++;
    $addFunds['amt_total'] += $amount;

    if (isset($addFunds[$status])) {
        $addFunds[$status]++;
        $addFunds["amt_$status"] += $amount;
    } else {
        $addFunds['pending']++;
        $addFunds['amt_pending'] += $amount;
    }
}

// === Count Withdraw Requests ===
foreach (glob("users/*/Rq-Cashout/*/*.json", GLOB_BRACE) as $file) {
    if (!file_exists($file)) continue;
    $data = json_decode(file_get_contents($file), true);
    if (!$data) continue;

    $amount = (float)($data['amount'] ?? 0);
    $status = strtolower(trim($data['status'] ?? 'pending'));

    $withdrawals['total']++;
    $withdrawals['amt_total'] += $amount;

    if (isset($withdrawals[$status])) {
        $withdrawals[$status]++;
        $withdrawals["amt_$status"] += $amount;
    } else {
        $withdrawals['pending']++;
        $withdrawals['amt_pending'] += $amount;
    }
}

    // === Send Result ===
    $msg = "<b>📊 System Statistics</b>\n\n"
         . "👥 Total Users: <b>$totalUsers</b>\n\n"

         . "💰 <u>Add Funds</u>\n"
         . "   • Total: <b>{$addFunds['total']}</b> | ₹<b>{$addFunds['amt_total']}</b>\n"
         . "   • ✅ Approved: <b>{$addFunds['approved']}</b> | ₹<b>{$addFunds['amt_approved']}</b>\n"
         . "   • ⏳ Pending: <b>{$addFunds['pending']}</b> | ₹<b>{$addFunds['amt_pending']}</b>\n"
         . "   • ❌ Rejected: <b>{$addFunds['rejected']}</b> | ₹<b>{$addFunds['amt_rejected']}</b>\n\n"

         . "🏧 <u>Withdrawals</u>\n"
         . "   • Total: <b>{$withdrawals['total']}</b> | ₹<b>{$withdrawals['amt_total']}</b>\n"
         . "   • ✅ Approved: <b>{$withdrawals['approved']}</b> | ₹<b>{$withdrawals['amt_approved']}</b>\n"
         . "   • ⏳ Pending: <b>{$withdrawals['pending']}</b> | ₹<b>{$withdrawals['amt_pending']}</b>\n"
         . "   • ❌ Rejected: <b>{$withdrawals['rejected']}</b> | ₹<b>{$withdrawals['amt_rejected']}</b>\n";

    sendMessage($chat_id, $msg, null, true);
}

?>