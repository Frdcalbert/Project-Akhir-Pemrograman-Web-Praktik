<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT username, email, full_name, phone_number FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('Pengguna tidak ditemukan.');
}

$errors = [];
$success = false;

// Proses pembaruan data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');

    // Validasi input
    if ($username === '') {
        $errors[] = 'Username harus diisi.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email harus diisi dan valid.';
    }
    if ($full_name === '') {
        $errors[] = 'Nama lengkap harus diisi.';
    }
    if ($phone_number === '') {
        $errors[] = 'Nomor telepon harus diisi.';
    }

    // Jika tidak ada error, lakukan pembaruan
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, phone_number = ? WHERE user_id = ?");
        $stmt->execute([$username, $email, $full_name, $phone_number, $user_id]);
        $success = true;
    }
}
    // Proses logout
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        session_destroy();
        header('Location: login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Profile - Loker.id</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet"/>
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
            margin: 50px auto;
            margin-bottom: 80px;
        }
        h1 {
            color: #764ba2;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-back {
            background-color: #764ba2;
            color: white;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 20px;
            width: 100%;
            border: none;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .btn-back:hover {
            background-color: #5b3883;
        }
        .btn-logout {
            background-color: #ff3131; 
            color: white;
            font-weight: 700;
            border-radius: 30px;
            padding: 12px 20px;
            width: 100%;
            border: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }
        .btn-logout:hover {
            background-color: #ff3131; 
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
<nav class="navbar">
    <a class="navbar-brand" href="index.php">Loker.id</a>
</nav>

<div class="container">
    <h1>Edit Profile</h1>

    <?php if ($success): ?>
        <div class="alert alert-success">Data Anda berhasil diperbarui!</div>
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

    <form method="post" action="profile.php">
        <div class="mb-4">
            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="full_name" class="form-label">Nama Lengkap</label>
            <input type="text" id="full_name" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
        </div>
        <div class="mb-4">
            <label for="phone_number" class="form-label">Nomor Telepon</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['phone_number']) ?>" required>
        </div>
            <button type="submit" class="btn-back">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-outline-secondary">Kembali ke Beranda</a>
        </form>
            <a href="profile.php?action=logout" class="btn btn-logout">Logout</a>
        </form>
</div>
<footer>
  &copy; <?= date('Y') ?> Loker.id
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
        window.onscroll = function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                document.querySelector('footer').style.bottom = '0';
            } else {
                document.querySelector('footer').style.bottom = '-60px';
            }
        };
</script>
</bod>
</html>