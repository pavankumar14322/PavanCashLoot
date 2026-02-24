<?php
session_start();
date_default_timezone_set('Asia/Kolkata');

/*/ ✅ Check if admin
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}*/

// Get message
$message = trim($_POST['message'] ?? '');
if(!$message){
    echo json_encode(['status'=>'error','message'=>'Message is required']);
    exit;
}

// Load existing notifications
$notifFile = __DIR__.'/noticaticons/noticaticons.json';
$notifications = [];
if(file_exists($notifFile)){
    $notifications = json_decode(file_get_contents($notifFile), true) ?: [];
}

// Create new notification
$newNotif = [
    'time' => date('d/m/Y H:i:s'),
    'message' => $message,
    'read' => [] // store user identifiers who have read it
];

// Add new notification on top
array_unshift($notifications, $newNotif);

// Save to file
file_put_contents($notifFile, json_encode($notifications, JSON_PRETTY_PRINT));

echo json_encode(['status'=>'success','message'=>'Notification sent']);
?>
