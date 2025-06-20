<?php
session_start();
require 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$application_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($application_id <= 0) {
    die('Invalid application ID.');
}

// Delete application
$stmt = $pdo->prepare("DELETE FROM applications WHERE application_id = ?");
$stmt->execute([$application_id]);

// Redirect back to data_lamaran.php
header('Location: data_lamaran.php');
exit;
?>
