<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$stmt = $pdo->prepare("SELECT full_name, email, phone_number FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$job_id = isset($_GET['job_id']) ? (int) $_GET['job_id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ?");
$stmt->execute([$job_id]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$job) {
    die('Lowongan pekerjaan tidak ditemukan.');
}

// Cek apakah user pembuat lowongan mencoba melamar sendiri
if ($job['created_by'] == $user_id) {
    $error_own_job = "Anda tidak dapat melamar pada lowongan yang Anda buat sendiri.";
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($error_own_job)) {
    $applicant_name = $user['full_name']; // Menggunakan nama lengkap dari data pengguna
    $applicant_email = $user['email']; // Menggunakan email dari data pengguna
    $applicant_phone = trim($_POST['applicant_phone'] ?? ''); // Nomor telepon diambil dari input

    if ($applicant_phone === '') {
        $errors[] = 'Nomor telepon harus diisi.';
    }
    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File CV harus diunggah dengan benar.';
    } else {
        $allowed_types = [
            'application/pdf', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        $file_type = $_FILES['cv']['type'];
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Format file CV harus PDF, DOC, atau DOCX.';
        }
        if ($_FILES['cv']['size'] > 5 * 1024 * 1024) {
            $errors[] = 'Ukuran file CV maksimal 5MB.';
        }
    }

    if (empty($errors)) {
        // Simpan file CV
        $cv_file = 'uploads/' . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cv_file);

        // Simpan data lamaran
        $stmt = $pdo->prepare("INSERT INTO applications (job_id, applicant_id, applicant_name, applicant_email, applicant_phone, cv_file, applied_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$job_id, $user_id, $applicant_name, $applicant_email, $applicant_phone, $cv_file]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lamar Lowongan - <?= htmlspecialchars($job['job_title']) ?></title>
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
    .application-form {
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
      font-weight: bold;
      text-align: center;
      margin-bottom: 40px;
      text-shadow: 0 2px 8px #fff; 
    }
    .form-label {
      font-weight: 600;
    }
    .btn-submit {
      background-color: #764ba2;
      border: none;
      border-radius: 25px;
      padding: 12px 0;
      color: white;
      font-weight: 700;
      width: 100%;
      font-size: 1.1rem;
      letter-spacing: 0.05em;
      transition: background-color 0.3s ease;
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

<div class="application-form">
  <h1>Lamar: <?= htmlspecialchars($job['job_title']) ?></h1>

  <?php if (isset($error_own_job)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error_own_job) ?></div>
    <a href="index.php" class="btn btn-outline-secondary">Kembali ke Lowongan</a>
  <?php elseif ($success): ?>
    <div class="alert alert-success">Lamaran Anda berhasil diproses. Terima kasih!</div>
    <a href="index.php" class="btn btn-submit mt-3">Kembali ke Lowongan</a>
  <?php else: ?>
    <?php if ($errors): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" novalidate>
      <div class="mb-4">
        <label for="applicant_name" class="form-label">Nama Lengkap</label>
        <input type="text" id="applicant_name" name="applicant_name" class="form-control form-control-lg" value="<?= htmlspecialchars($user['full_name']) ?>" readonly required />
      </div>
      <div class="mb-4">
        <label for="applicant_email" class="form-label">Email</label>
        <input type="email" id="applicant_email" name="applicant_email" class="form-control form-control-lg" value="<?= htmlspecialchars($user['email']) ?>" readonly required />
      </div>
      <div class="mb-4">
        <label for="applicant_phone" class="form-label">Nomor Telepon</label>
        <input type="text" id="applicant_phone" name="applicant_phone" class="form-control form-control-lg" value="<?= htmlspecialchars($user['phone_number']) ?>" required />
      </div>
      <div class="mb-4">
        <label for="cv" class="form-label">Upload CV (PDF, DOC, DOCX max 5MB)</label>
        <input type="file" class="form-control form-control-lg" id="cv" name="cv" accept=".pdf,.doc,.docx" required />
      </div>
      <button type="submit" class="btn btn-submit">Kirim Lamaran</button>
      <a href="index.php" class="btn btn-outline-secondary">Kembali ke Beranda</a>
    </form>
  <?php endif; ?>
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
</body>
</html>

