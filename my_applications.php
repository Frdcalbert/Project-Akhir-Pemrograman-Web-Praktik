<?php
session_start();
require 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data lamaran yang diajukan oleh pengguna
$stmt = $pdo->prepare("SELECT a.*, j.job_title, j.company_name FROM applications a JOIN jobs j ON a.job_id = j.id WHERE a.applicant_id = ? ORDER BY a.applied_at DESC");
$stmt->execute([$user_id]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lamaran Saya - Loker.id</title>
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
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      padding: 1rem 1.5rem;
    }
    .navbar .navbar-brand {
      font-weight: 700;
      font-size: 1.7rem;
      color: #764ba2 !important;
      letter-spacing: 1px;
      text-decoration: none;
    }
    .btn-back {
      background: #764ba2;
      border: none;
      color: white;
      font-weight: 600;
      border-radius: 30px;
      padding: 10px 20px;
      transition: background 0.3s ease;
      margin-left: 1rem;
    }
    .btn-back:hover {
      background: #5b3883;
      color: #fff;
      text-decoration: none;
    }
    .btn-reject {
      background-color: #ff3131; 
      color: white;
      font-weight: 600;
      border-radius: 30px;
      padding: 10px 20px;
      border: none;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }
    .btn-reject:hover {
      background-color: #d02424;
    }
    .container {
      max-width: 1100px;
      margin: 50px auto 80px;
    }
    h1 {
      color: #fff;
      font-weight: bold;
      text-align: center;
      margin-bottom: 40px;
      text-shadow: 0 2px 8px #764ba2; 
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      cursor: pointer;
      background: #fff;
      display: flex;
      flex-direction: column;
      height: auto;
      margin-bottom: 20px;
    }
    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 16px 40px rgba(0,0,0,0.2);
    }
    .card-body {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      overflow: visible;
    }
    .card-title {
      font-weight: 700;
      font-size: 1.3rem;
      color: #764ba2;
      margin-bottom: 4px;
    }
    .card-subtitle {
      font-weight: 500;
      color: #555;
      font-style: italic;
      font-size: 0.95rem;
      margin-bottom: 15px;
    }
    .card-text {
      font-size: 1rem;
      color: #444;
      overflow: hidden;
      text-overflow: ellipsis;
      flex-grow: 1;
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
<nav class="navbar d-flex justify-content-between align-items-center">
  <a href="index.php" class="navbar-brand">Loker.id</a>
  <div>
    <a href="index.php" class="btn btn-back">Beranda</a>
    <a href="profile.php" class="btn btn-back">Profile</a>
  </div>
</nav>

<div class="container">
  <h1>Lamaran Saya</h1>
  <?php if (count($applications) === 0): ?>
    <div class="alert alert-light text-center fs-5" style="color: #5b3883;">Anda belum mengajukan lamaran apa pun.</div>
  <?php else: ?>
    <div class="row row-cols-1">
      <?php foreach ($applications as $application): ?>
      <div class="col">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($application['job_title']) ?></h5>
            <h6 class="card-subtitle mb-2"><?= htmlspecialchars($application['company_name']) ?></h6>
            <p class="card-text">Lamaran diajukan pada: <?= date('d M Y', strtotime($application['applied_at'])) ?></p>
            <p class="card-text">
              <strong>Status:</strong> <?= htmlspecialchars($application['STATUS']) ?>
              <?php if ($application['STATUS'] === 'Sudah Ditinjau'): ?>
                  (cek email berkala untuk detail lanjut lamaran anda)
              <?php elseif ($application['STATUS'] === 'Ditolak'): ?>
                  (lamaran Anda telah ditolak)
              <?php endif; ?>
            </p>
            <div class="card-footer">
              <small>CV: <a href="<?= htmlspecialchars($application['cv_file']) ?>" target="_blank">Lihat CV</a></small>
              <a href="delete_application.php?id=<?= $application['application_id'] ?>" class="btn btn-reject" style="margin-left: 1rem;" onclick="return confirm('Apakah Anda yakin ingin menghapus lamaran ini?');">Hapus Lamaran</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
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
