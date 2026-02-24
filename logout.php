<?php
session_start();

// Remove session
$_SESSION = [];
session_destroy();

// Remove auth cookie
if (isset($_COOKIE['auth_token'])) {
    $token = $_COOKIE['auth_token'];

    // Remove token from tokens.json
    $tokenFile = "users/tokens.json";
    if (file_exists($tokenFile)) {
        $tokens = json_decode(file_get_contents($tokenFile), true);
        foreach ($tokens as $number => $data) {
            if ($data['token'] === $token) {
                unset($tokens[$number]);
                break;
            }
        }
        file_put_contents($tokenFile, json_encode($tokens, JSON_PRETTY_PRINT));
    }

    // Expire cookie
    setcookie("auth_token", "", time() - 3600, "/", "", false, true);
}

// Redirect to login page
header("Location: index.php");
exit;