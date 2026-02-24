<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    echo json_encode(['status'=>'error','message'=>'Unauthorized']);
    exit;
}

$notifFile = __DIR__."/noticaticons/noticaticons.json";
if(file_exists($notifFile)){
    file_put_contents($notifFile, json_encode([]));
}

echo json_encode(['status'=>'success','message'=>'All notifications cleared']);