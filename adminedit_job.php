<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;   

// Ambil data lowongan
// PERUBAHAN: Hapus kondisi AND created_by = ?
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?"); //
$stmt->execute([$job_id]); //
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die('Lowongan pekerjaan tidak ditemukan atau Anda tidak memiliki izin untuk mengedit.');
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_title = trim($_POST['job_title'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($job_title === '') {
        $errors[] = 'Judul pekerjaan harus diisi.';
    }
    if ($company_name === '') {
        $errors[] = 'Nama perusahaan harus diisi.';
    }
    if ($location === '') {
        $errors[] = 'Lokasi harus diisi.';
    }
    if ($description === '') {
        $errors[] = 'Deskripsi pekerjaan harus diisi.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE jobs SET job_title = ?, company_name = ?, location = ?, description = ? WHERE id = ?");
        $stmt->execute([$job_title, $company_name, $location, $description, $job_id]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Lowongan Kerja - Loker.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
        /* Base and layout */
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
            color: #4f46e5; /* Indigo-600 */
            text-decoration: underline;
        }
        .container {
            max-width: 1200px;
            margin: 3rem auto 4rem;
            padding: 0 2rem;
        }
        h1 {
            font-size: 3rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 2rem;
            text-align: center;
        }

        .row > div.col-md-4 {
            text-align: center;
            margin-bottom: 2rem;
        }
        .btn-primary {
            background-color: #4f46e5;
            border: none;
            padding: 1rem 2rem;
            font-weight: 700;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 6px rgba(79,70,229,0.3);
        }
        .btn-primary:hover, .btn-primary:focus {
            background-color: #4338ca;
            box-shadow: 0 6px 8px rgba(67,56,202,0.4);
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
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Lowongan Kerja</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Lowongan pekerjaan berhasil diperbarui!</div>
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

    <form method="post" action="adminedit_job.php?id=<?= $job_id ?>">
        <div class="mb-4">
            <label for="job_title" class="form-label">Judul Pekerjaan</label>
            <input type="text" id="job_title" name="job_title" class="form-control" value="<?= htmlspecialchars($job['job_title']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="company_name" class="form-label">Nama Perusahaan</label>
            <input type="text" id="company_name" name="company_name" class="form-control" value="<?= htmlspecialchars($job['company_name']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="location" class="form-label">Lokasi</label>
            <input type="text" id="location" name="location" class="form-control" value="<?= htmlspecialchars($job['location']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="description" class="form-label">Deskripsi Pekerjaan</label>
            <textarea id="description" name="description" rows="5" class="form-control" required><?= htmlspecialchars($job['description']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="data_master.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>