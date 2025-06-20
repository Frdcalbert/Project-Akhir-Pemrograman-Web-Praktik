<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($job_id <= 0) {
    die('Invalid job ID.');
}

// Delete job only if it belongs to the logged-in user
$stmt = $pdo->prepare("DELETE FROM jobs WHERE id = ? AND created_by = ?");
$stmt->execute([$job_id, $user_id]);

// Redirect back to user's jobs page
header('Location: my_jobs.php');
exit;
?>
