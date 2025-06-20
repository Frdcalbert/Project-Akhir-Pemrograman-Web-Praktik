<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = isset($_GET['job_id']) ? (int) $_GET['job_id'] : 0;

// Ambil data lowongan
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ? AND created_by = ?");
$stmt->execute([$job_id, $user_id]);
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
    <title>Edit Lowongan Kerja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            height: 100%; 
        }
        #background-video {
            position: fixed;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1; 
            transform: translate(-50%, -50%);
        }
        .navbar {
            background-color: rgba(255,255,255,0.9) !important;
            box-shadow: 0 4px 15px rgb(0 0 0 / 0.1);
            padding: 1rem 1.5rem;
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.7rem;
            color: #764ba2 !important;
            letter-spacing: 1px;
            text-decoration: none;
        }
        .container {
            max-width: 600px;
            background: #fff;
            padding: 35px 40px;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.1);
            margin: 0 auto;
            margin-top: 50px;
            margin-bottom: 80px;
        }
        h1 {
            color: #764ba2;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-submit {
            background-color: #764ba2;
            color: white;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 20px;
            width: 100%;
            border: none;
            transition: background-color 0.3s ease;
            font-size: 1.1rem;
        }
        .btn-submit:hover {
            background-color: #5b3883;
        }
        .btn-outline-secondary {
            border: 1px solid #764ba2;
            color: #764ba2;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 20px;
            width: 100%;
            transition: background-color 0.3s ease, color 0.3s ease;
            margin-top: 10px;
        }
        .btn-outline-secondary:hover {
            background-color: #5b3883;
            color: white;
        }
        .alert ul {
            margin-bottom: 0;
        }
        footer {
            position: fixed;
            bottom: -60px; 
            left: 0;
            width: 100%;
            height: 60px;
            background-color: rgba(255, 255, 255, 0.9);
            color: #764ba2; 
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: bottom 0.3s; 
            z-index: 1030;
        }
    </style>
</head>
<body>
<video autoplay muted loop id="background-video">
        <source src="bgjobs.mp4" type="video/mp4">
        Your browser does not support the video tag.
</video>  
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="index.php">Loker.id</a>
</nav>

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

    <form method="post" action="edit_job.php?job_id=<?= $job_id ?>" novalidate>
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
        <button type="submit" class="btn-submit">Simpan Perubahan</button>
        <a href="my_jobs.php" class="btn btn-outline-secondary">Kembali</a>
    </form>
</div>
<footer>
  &copy; <?= date('Y') ?> Loker.id
</footer>
<script>
        window.onscroll = function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                document.querySelector('footer').style.bottom = '0';
            } else {
                document.querySelector('footer').style.bottom = '-60px';
            }
        };
</script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>