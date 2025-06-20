<?php
session_start();
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = trim($_POST['identifier'] ?? ''); // Menggunakan identifier untuk username atau email
    $password = $_POST['password'] ?? '';
    if ($identifier === '' || $password === '') {
        $errors[] = 'Username/Email dan password harus diisi.';
    } else {
        // Query untuk mengambil data pengguna berdasarkan email atau username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$identifier, $identifier]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // Memverifikasi password
        if ($user && password_verify($password, $user['PASSWORD'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Menyimpan role di sesi
            // Redirect berdasarkan peran
            if ($_SESSION['role'] === 'admin') {
                header('Location: admin_dashboard.php'); // Redirect ke dashboard admin
            } else {
                header('Location: index.php'); // Redirect ke halaman utama
            }
            exit;
        } else {
            $errors[] = 'Username/Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login User - Loker.id</title>
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
      padding: 15px 40px;
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
      height: 60px;
      background-color:rgba(255, 255, 255, 0.9);
      color: #764ba2;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      position: fixed;
      bottom: 0;
      width: 100%;
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
    <h1>Login User</h1>

    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="post" action="login.php" novalidate>
      <div class="mb-4">
        <label for="identifier" class="form-label">Username atau Email</label>
        <input type="text" id="identifier" name="identifier" class="form-control form-control-lg" value="<?= htmlspecialchars($_POST['identifier'] ?? '') ?>" required />
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" id="password" name="password" class="form-control form-control-lg" required />
      </div>
      <button type="submit" class="btn-submit">Login</button>
    </form>
    <div class="mt-3 text-center">
            <p>Belum punya akun? <a href="register.php">Daftar</a>.</p>
    </div>
  </div>

  <footer>
    &copy; <?= date('Y') ?> Loker.id
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
