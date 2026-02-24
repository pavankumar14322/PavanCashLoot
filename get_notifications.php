<?php
session_start();
header('Content-Type: application/json');

$number = $_SESSION['user']['number'] ?? null;
if (!$number) {
    echo json_encode(['status'=>'error','message'=>'Not logged in']);
    exit;
}

$notifFile = __DIR__."/noticaticons/noticaticons.json";
$notifications = [];
if(file_exists($notifFile)){
    $notifications = json_decode(file_get_contents($notifFile), true) ?: [];
}

// Count unread for this user
$unreadCount = 0;
foreach($notifications as $note){
    if(!in_array($number, $note['read'] ?? [])){
        $unreadCount++;
    }
}

echo json_encode([
    'status' => 'success',
    'notifications' => $notifications,
    'unreadCount' => $unreadCount
]);