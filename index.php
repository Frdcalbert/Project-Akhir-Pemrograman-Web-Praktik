<?php
session_start(); 
require 'config.php';

// Ambil data lowongan
$stmt = $pdo->query("SELECT * FROM jobs ORDER BY posted_date DESC");
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lowongan Kerja Lokal - Loker.id</title>
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
    .btn-apply {
      background: #764ba2;
      border: none;
      color: white;
      font-weight: 600;
      border-radius: 30px;
      padding: 10px 20px;
      transition: background 0.3s ease;
      margin-left: 1rem;
    }
    .btn-apply:hover {
      background: #5b3883;
      color: #fff;
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
    .card-footer {
      margin-top: auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .card-footer small {
      color: #777;
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
      transition: bottom 0.3s ease;
      z-index: 1030;
    }
    .help-button {
      position: fixed;
      bottom: 90px;
      right: 20px;
      width: 50px;
      height: 50px;
      background-color: #764ba2;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
      z-index: 1100;
      user-select:none;
      line-height: 1;
    }
    .help-overlay {
      display: none;
      position: fixed;
      top:0;
      left:0;
      width: 100vw;
      height: 100vh;
      background: rgba(0,0,0,0.5);
      z-index: 1200;
      align-items: center;
      justify-content: center;
    }
    .help-content h2 {
      margin-top: 0;
      margin-bottom: 15px;
      color: #764ba2;
      font-weight: 700;
      font-size: 1.5rem;
    }
    .help-content {
      background: white;
      border-radius: 10px;
      max-width: 500px; 
      width: 90vw;
      padding: 20px 25px 25px 25px;
      box-shadow: 0 3px 20px rgba(0,0,0,0.3);
      position: relative;
      color: #333;
      font-size: 1rem;
      text-align: left;
      line-height: 1.4;
      max-height: 80vh; 
      overflow-y: auto; 
    }
   .help-close-btn {
      position: absolute;
      top: 15px; 
      right: 15px; 
      background: transparent;
      border: none;
      font-size: 28px;
      color: #ff3131;
      cursor: pointer;
      font-weight: 700;
      line-height: 1;
      user-select: none;
      padding: 0;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: color 0.3s ease;
    }
    .help-close-btn:hover {
      color:rgb(187, 32, 32);
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
  <div class="d-flex align-items-center">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="my_applications.php" class="btn btn-apply">Lamaran Saya</a> 
      <a href="my_jobs.php" class="btn btn-apply">Lowongan Saya</a>
    <?php endif; ?>
    <a href="add_job.php" class="btn btn-apply">Tambah Lowongan</a>
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="profile.php" class="btn btn-apply">Profile</a> 
    <?php endif; ?>
  </div>
</nav>

<div class="container">
  <h1> Daftar Lowongan Kerja </h1>
  <?php if (count($jobs) === 0): ?>
    <div class="alert alert-light text-center fs-5">Belum ada lowongan pekerjaan saat ini.</div>
  <?php else: ?>
    <div class="row row-cols-1">
      <?php foreach ($jobs as $job): ?>
      <div class="col">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($job['job_title']) ?></h5>
            <h6 class="card-subtitle mb-2"><?= htmlspecialchars($job['company_name']) ?> - <?= htmlspecialchars($job['location']) ?></h6>
            <p class="card-text short-description"><?= nl2br(htmlspecialchars(substr($job['description'], 0, 100))) ?>...</p>
            <p class="card-text full-description" style="display: none;"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
            <button class="btn btn-apply toggle-description">Lihat Selengkapnya</button><br>
            <div class="card-footer">
              <small>Ditayangkan <?= date('d M Y', strtotime($job['posted_date'])) ?></small>
              <a href="apply.php?job_id=<?= $job['id'] ?>" class="btn btn-apply">Lamar</a>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<div id="help-button" class="help-button" aria-label="Tombol bantuan" role="button" tabindex="0">?</div>
<div id="help-overlay" class="help-overlay" role="dialog" aria-modal="true" aria-labelledby="help-title" tabindex="-1">
  <div class="help-content">
    <button id="help-close" class="help-close-btn" aria-label="Tutup">&times;</button>
    <h2 id="help-title">Cara Penggunaan Website</h2>
    <p><b>BAGI PELAMAR</b></p>
    <p>1. Jika anda ingin mencari lowongan pekerjaan anda bisa cek di halaman utama untuk melihat berbagai lowongan</p>
    <p>2. Klik tombol "Lamar" jika anda tertarik untuk melamar pada lowongan tersebut</p>
    <p>3. Setelah itu anda bisa isi formulir yang disediakan</p>
    <p>4. Setelah lamaran sudah di kirim anda dapat cek status lamaran Anda di halaman "Lamaran Saya"</p>
    <p>5. Jika Status berubah "Sudah Ditinjau", cek email anda secara berkala untuk informasi terkait lamaran anda yang akan di kirim oleh pembuat lowongan</p>    
    <p>5. Jika Status berubah "Ditolak", maka mungkin kriteria anda tidak sesuai dengan pembuat lowongan</p>       
    <br>
    <p><b>BAGI PEMBUAT LOWONGAN</b></p>
    <p>1. Jika anda ingin membuat lowongan, Anda bisa klik tombol "Tambah Lowongan"</p>
    <p>2. Setelah itu isi form yang sudah di sediakan dan klik "Tambah Lowongan"</p>    
    <p>3. Anda bisa cek lowongan yang anda buat pada halaman "Lowongan Saya", anda juga bisa mengeditnya di halaman tsb</p>
    <p>4. Jika ada pelamar, maka anda bisa melihat data diri dan CV yang dikirm kan oleh pelamar, anda juga bisa memberikan konfirmasi apakah lamaran tsb sudah anda tinjau atau belum, atau mungkin anda bisa menolak nya</p>
    <P>5. Jika anda sudah meninjau nya ada bisa kirim email pada pelamar sesuai dengan email yang di cantumkan olek pelamar</P>
  </div>
</div>

<footer>
  &copy; <?= date('Y') ?> Loker.id
</footer>

<script>
  // Toggle detail lowongan kerja //
  document.querySelectorAll('.toggle-description').forEach(button => {
    button.addEventListener('click', function() {
      const cardBody = this.closest('.card-body');
      const shortDescription = cardBody.querySelector('.short-description');
      const fullDescription = cardBody.querySelector('.full-description');

      if (fullDescription.style.display === 'none') {
        fullDescription.style.display = 'block';
        shortDescription.style.display = 'none';
        this.textContent = 'Sembunyikan';
      } else {
        fullDescription.style.display = 'none';
        shortDescription.style.display = 'block';
        this.textContent = 'Lihat Selengkapnya';
      }
    });
  });

  const helpBtn = document.getElementById('help-button');
  const helpOverlay = document.getElementById('help-overlay');
  const helpClose = document.getElementById('help-close');

  helpBtn.addEventListener('click', () => {
    helpOverlay.style.display = 'flex';
    helpOverlay.focus();
    document.body.style.overflow = 'hidden'; 
  });

  helpClose.addEventListener('click', () => {
    helpOverlay.style.display = 'none';
    document.body.style.overflow = ''; 
    helpBtn.focus();
  });

  
  window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && helpOverlay.style.display === 'flex') {
      helpOverlay.style.display = 'none';
      document.body.style.overflow = '';
      helpBtn.focus();
    }
  });

  // Footer animasi muncul/hilang sesuai scroll
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
