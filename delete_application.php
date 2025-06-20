<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$application_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($application_id <= 0) {
    die('Invalid application ID.');
}

// Verify that the application belongs to the user before deletion
$stmt = $pdo->prepare("SELECT applicant_id FROM applications WHERE application_id = ?");
$stmt->execute([$application_id]);
$applicant = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$applicant || $applicant['applicant_id'] != $user_id) {
    die('Unauthorized action.');
}

// Delete the application
$stmt = $pdo->prepare("DELETE FROM applications WHERE application_id = ?");
$stmt->execute([$application_id]);

// Redirect back to user's applications page
header('Location: my_applications.php');
exit;
?>
