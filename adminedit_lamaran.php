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

// Fetch application data
$stmt = $pdo->prepare("SELECT a.*, j.job_title, u.full_name, u.email, u.phone_number FROM applications a JOIN jobs j ON a.job_id = j.id JOIN users u ON a.applicant_id = u.user_id WHERE a.application_id = ?");
$stmt->execute([$application_id]);
$application = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$application) {
    die('Application not found.');
}

$errors = [];
$success = false;

$statuses = ['Belum Ditinjau', 'Sudah Ditinjau', 'Ditolak'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'] ?? '';

    if (!in_array($new_status, $statuses)) {
        $errors[] = 'Status tidak valid.';
    }

    if (empty($errors)) {
        $update_stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE application_id = ?");
        $update_stmt->execute([$new_status, $application_id]);
        $success = true;
        // Refresh data after update
        $stmt->execute([$application_id]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Lamaran - Loker.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
      body {
          font-family: 'Poppins', sans-serif;
          background-color: #ffffff;
          color: #6b7280;
          margin: 0;
          padding: 0;
      }
      nav.navbar {
          position: sticky;
          top: 0;
          background-color: #f9fafb;
          padding: 1rem 2rem;
          box-shadow: 0 1px 3px rgba(0,0,0,0.1);
          z-index: 1000;
      }
      nav.navbar a.navbar-brand {
          font-weight: 700;
          font-size: 1.75rem;
          color: #111827;
          text-decoration: none;
      }
      nav.navbar .nav-link {
          color: #6b7280;
          font-weight: 600;
          margin-right: 1rem;
          transition: color 0.3s ease;
      }
      nav.navbar .nav-link:hover, nav.navbar .nav-link:focus {
          color: #4f46e5;
          text-decoration: underline;
      }
      .container {
          max-width: 600px;
          margin: 3rem auto 4rem;
          padding: 2rem;
          background: #fff;
          border-radius: 0.75rem;
          box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      }
      h1 {
          font-size: 2.5rem;
          font-weight: 700;
          color: #111827;
          margin-bottom: 2rem;
          text-align: center;
      }
      label {
          font-weight: 600;
          color: #374151;
      }
      .form-control {
          border-radius: 0.5rem;
          border: 1px solid #d1d5db;
          padding: 0.5rem 0.75rem;
          font-size: 1rem;
          color: #374151;
      }
      .form-control[readonly] {
          background-color: #f9fafb;
          cursor: default;
      }
      .btn-primary {
          background-color: #4f46e5;
          border: none;
          padding: 0.75rem 1.5rem;
          font-weight: 700;
          border-radius: 0.75rem;
          font-size: 1.1rem;
          transition: background-color 0.3s ease;
          box-shadow: 0 4px 6px rgba(79,70,229,0.3);
          width: 100%;
          margin-top: 1.5rem;
      }
      .btn-primary:hover, .btn-primary:focus {
          background-color: #4338ca;
          box-shadow: 0 6px 8px rgba(67,56,202,0.4);
      }
      .alert-success {
          margin-bottom: 1rem;
          font-weight: 600;
          color: #166534;
          background-color: #d1fae5;
          padding: 1rem;
          border-radius: 0.5rem;
          text-align: center;
      }
      .alert-danger {
          margin-bottom: 1rem;
          font-weight: 600;
          color: #991b1b;
          background-color: #fecaca;
          padding: 1rem;
          border-radius: 0.5rem;
      }
      footer {
          text-align: center;
          padding: 1rem 0;
          font-weight: 600;
          color: #9ca3af;
          border-top: 1px solid #e5e7eb;
          background-color: #ffffff;
          position: fixed;
          bottom: 0;
          width: 100%;
      }
      a.btn-outline-secondary {
          display: inline-block;
          text-decoration: none;
          color: #4f46e5;
          border: 2px solid #4f46e5;
          padding: 0.4rem 1rem;
          border-radius: 0.75rem;
          margin-top: 1rem;
          font-weight: 700;
          transition: background-color 0.3s ease, color 0.3s ease;
          text-align: center;
      }
      a.btn-outline-secondary:hover {
          background-color: #4f46e5;
          color: white;
      }
    </style>
</head>
<body>
<nav class="navbar">
    <a class="navbar-brand" href="admin_dashboard.php">Dashboard Admin Loker.Id</a>
</nav>

<div class="container">
    <h1>Edit Lamaran</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Status lamaran berhasil diperbarui.</div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="adminedit_lamaran.php?id=<?= $application_id ?>">
        <div class="mb-3">
            <label for="applicant_name">Nama Pelamar</label>
            <input type="text" id="applicant_name" class="form-control" value="<?= htmlspecialchars($application['full_name']) ?>" readonly />
        </div>
        <div class="mb-3">
            <label for="applicant_email">Email Pelamar</label>
            <input type="email" id="applicant_email" class="form-control" value="<?= htmlspecialchars($application['email']) ?>" readonly />
        </div>
        <div class="mb-3">
            <label for="applicant_phone">Telepon Pelamar</label>
            <input type="text" id="applicant_phone" class="form-control" value="<?= htmlspecialchars($application['phone_number']) ?>" readonly />
        </div>
        <div class="mb-3">
            <label for="job_title">Judul Pekerjaan</label>
            <input type="text" id="job_title" class="form-control" value="<?= htmlspecialchars($application['job_title']) ?>" readonly />
        </div>
        <div class="mb-3">
            <label for="status">Status Lamaran</label>
            <select name="status" id="status" class="form-control" required>
                <?php foreach ($statuses as $status_option): ?>
                    <option value="<?= $status_option ?>" <?= $application['STATUS'] === $status_option ? 'selected' : '' ?>>
                        <?= $status_option ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="data_lamaran.php" class="btn-outline-secondary">Kembali</a>
    </form>
</div>

<footer>
    &copy; <?= date('Y') ?> Loker.id
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

