<?php
session_start();
header('Content-Type: application/json');

$number = $_SESSION['user']['number'] ?? null;
if (!$number) {
    echo json_encode(['status'=>'error','message'=>'Not logged in']);
    exit;
}

$notifFile = __DIR__."/noticaticons/noticaticons.json";
if(!file_exists($notifFile)){
    echo json_encode(['status'=>'error','message'=>'No notifications']);
    exit;
}

$notifications = json_decode(file_get_contents($notifFile), true) ?: [];

// Mark read
foreach($notifications as &$note){
    if(!in_array($number, $note['read'] ?? [])){
        $note['read'][] = $number;
    }
}
unset($note);

file_put_contents($notifFile, json_encode($notifications, JSON_PRETTY_PRINT));
echo json_encode(['status'=>'success','message'=>'All notifications marked as read']);