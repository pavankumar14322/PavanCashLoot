<?php
session_start();
$number = $_SESSION['user']['number'] ?? null;
if (!$number) {
    die("❌ User not logged in.");
}

if (!isset($_GET['product']) || empty($_GET['product'])) {
    die("❌ No product specified.");
}

$filename = basename($_GET['product']);
$jsonPath = "Store/$filename/$filename.json";
$userFile = "users/$number/$number.txt";

if (!file_exists($jsonPath) || !file_exists($userFile)) {
    die("❌ Product or user not found.");
}

$data = json_decode(file_get_contents($jsonPath), true);
$zipPath = "Store/$filename/" . $data['zip'];

if (!file_exists($zipPath)) {
    die("❌ ZIP file missing.");
}

header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . basename($zipPath) . '"');
header('Content-Length: ' . filesize($zipPath));
readfile($zipPath);
exit;