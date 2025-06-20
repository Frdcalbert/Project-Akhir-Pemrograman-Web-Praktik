<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login dan memiliki hak akses admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Ambil data pengguna dan lowongan
$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
$jobs = $pdo->query("SELECT * FROM jobs")->fetchAll(PDO::FETCH_ASSOC);
?>
&nbsp;
&nbsp;

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Master</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="admin_dashboard.php">Dashboard Admin Loker.Id</a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="data_master.php">Data Master</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="data_lamaran.php">Data Lamaran</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="statistik.php">Statistik</a>
            </li>
        </ul>
            <ul class="navbar-nav"> <li class="nav-item">
                <a class="nav-link" href="login.php">Logout</a> </li>
            </ul>
    </div>
</nav>
&nbsp;
&nbsp;

<div class="container">
    <h1>Data Master</h1>
&nbsp;
&nbsp;

    <h2>Pengguna</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['full_name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone_number']) ?></td>
                    <td>
                        <a href="adminedit_user.php?id=<?= $user['user_id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="admindelete_user.php?id=<?= $user['user_id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Lowongan</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Judul Pekerjaan</th>
                <th>Perusahaan</th>
                <th>Lokasi</th>
                <th> ID Pembuat Lowongan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?= htmlspecialchars($job['id']) ?></td>
                    <td><?= htmlspecialchars($job['job_title']) ?></td>
                    <td><?= htmlspecialchars($job['company_name']) ?></td>
                    <td><?= htmlspecialchars($job['location']) ?></td>
                    <td><?= htmlspecialchars($job['created_by']) ?></td>
                    <td>
                        <a href="adminedit_job.php?id=<?= $job['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="admindelete_job.php?id=<?= $job['id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
&nbsp;
&nbsp;

<footer class="text-center">
    <p>&copy; <?= date('Y') ?> Loker.id</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
