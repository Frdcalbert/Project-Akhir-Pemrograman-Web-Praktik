<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = $_POST['application_id'];

    // Ambil status saat ini
    $stmt = $pdo->prepare("SELECT status FROM applications WHERE application_id = ?");
    $stmt->execute([$application_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($application) {
        // Ubah status
        if (isset($_POST['reject'])) {
            $new_status = 'Ditolak';
        } else {
            $new_status = $application['status'] === 'Belum Ditinjau' ? 'Sudah Ditinjau' : 'Belum Ditinjau';
        }
        $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
        $stmt->execute([$new_status, $application_id]);
    }
    
    header('Location: my_jobs.php');
    exit;
}

?>
