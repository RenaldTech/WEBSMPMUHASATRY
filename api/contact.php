<?php
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

global $db;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$subject = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

// Validasi
if (!$name || !$email || !$message) {
    http_response_code(400);
    echo json_encode(['error' => 'Mohon lengkapi semua field yang wajib diisi']);
    exit;
}

if (!isValidEmail($email)) {
    http_response_code(400);
    echo json_encode(['error' => 'Email tidak valid']);
    exit;
}

// Escape data
$name = $db->escapeString($name);
$email = $db->escapeString($email);
$phone = $db->escapeString($phone);
$subject = $db->escapeString($subject);
$message = $db->escapeString($message);

// Insert ke database
$sql = "INSERT INTO contact_messages (name, email, phone, subject, message) 
        VALUES ('$name', '$email', '$phone', '$subject', '$message')";

if ($db->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Pesan Anda berhasil dikirim']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Gagal mengirim pesan, silakan coba lagi']);
}
