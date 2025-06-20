<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Hapus pengguna
$stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);

header('Location: data_master.php');
exit;
?>
