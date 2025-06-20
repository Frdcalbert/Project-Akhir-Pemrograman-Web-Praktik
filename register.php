<?php
require 'config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? ''); // Ambil nomor telepon
    $full_name = trim($_POST['full_name'] ?? ''); // Ambil nama lengkap

    // Validasi input
    if ($username === '') {
        $errors[] = 'Username harus diisi.';
    } else {
        // Cek apakah username sudah terdaftar
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $errors[] = 'Username sudah terdaftar.';
        }
    }

    if ($password === '') {
        $errors[] = 'Password harus diisi.';
    }
    if ($email === '') {
        $errors[] = 'Email harus diisi.';
    } else {
        // Cek apakah email sudah terdaftar
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email sudah terdaftar.';
        }
    }

    if ($phone_number === '') {
        $errors[] = 'Nomor telepon harus diisi.'; // Validasi nomor telepon
    }

    if ($full_name === '') {
        $errors[] = 'Nama lengkap harus diisi.'; // Validasi nama lengkap
    }

    // Jika tidak ada error, lakukan registrasi
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, phone_number, full_name) VALUES (?, ?, ?, ?, ?)"); // Tambahkan kolom full_name
        $stmt->execute([$username, $hashed_password, $email, $phone_number, $full_name]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registrasi - Loker.id</title>
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
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            padding: 35px 40px;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(0,0,0,0.1);
            margin-bottom: 80px;
        }
        h1 {
            color: #764ba2;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-submit {
            background-color: #764ba2;
            border: none;
            border-radius: 30px;
            padding: 12px 0;
            color: white;
            font-weight: 600;
            width: 100%;
            font-size: 1.1rem;
            letter-spacing: 0.05em;
            transition: background-color 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #5b3883;
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
    <nav class="navbar">
        <a class="navbar-brand" href="index.php">Loker.id</a>
    </nav>

    <div class="container">
        <h1>Registrasi</h1>

        <?php if ($success): ?>
            <div class="alert alert-success">Registrasi berhasil! Silakan <a href="login.php">login</a>.</div>
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

        <form method="post" action="register.php">
            <div class="mb-4">
                <label for="full_name" class="form-label">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="contohemail@gmail.com" required>
            </div>
            <div class="mb-4">
                <label for="phone_number" class="form-label">Nomor Telepon</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn-submit">Daftar</button>
        </form>
        <div class="mt-3 text-center">
            <p>Sudah punya akun? <a href="login.php">Login</a>.</p>
        </div>
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
