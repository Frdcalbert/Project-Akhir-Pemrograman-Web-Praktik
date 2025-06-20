<?php
session_start();
require 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch jobs created by this user
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE created_by = ? ORDER BY posted_date DESC");
$stmt->execute([$user_id]);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lowongan Saya - Loker.id</title>
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
      margin-top: 10px;
      transition: background 0.3s ease;
      margin-left: 1rem;
    }
    .btn-back:hover {
      background: #5b3883;
      color: #fff;
      text-decoration: none;
    }
    .container {
      max-width: 1100px;
      margin: 50px auto 80px;
      margin-bottom: 80px;
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
    .btn-custom {
      background-color: #764ba2;
      color: white;
      font-weight: 600; 
      border-radius: 30px;
      padding: 10px 20px;
      border: none; 
      transition: background-color 0.3s ease; 
      margin-top: 10px; 
    }
    .btn-custom:hover {
      background-color: #5b3883; 
    }
    .btn-reject {
      background-color: #ff3131; 
      color: white; 
      font-weight: 600; 
      border-radius: 30px; 
      padding: 10px 20px; 
      border: none; 
      transition: background-color 0.3s ease; 
      margin-top: 10px; 
    }

    .btn-reject:hover {
      background-color: #d02424; 
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
    .applicant-info {
      margin-top: 10px;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #f9f9f9;
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
  <h1>Lowongan Saya</h1>
  <?php if (count($jobs) === 0): ?>
    <div class="alert alert-light text-center fs-5" style="color:#fff;">Anda belum membuat lowongan pekerjaan apa pun.</div>
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
            <button class="btn btn-apply toggle-description">Lihat Selengkapnya</button> <br>
            <div class="card-footer">
              <small>Ditayangkan <?= date('d M Y', strtotime($job['posted_date'])) ?></small>
              <div>
                <a href="edit_job.php?job_id=<?= $job['id'] ?>" class="btn btn-back">Edit</a>
                <a href="delete_job.php?id=<?= $job['id'] ?>" class="btn btn-reject" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">Hapus</a>
              </div>
            </div>
            <h6 class="mt-3"><strong>Pelamar :</strong></h6>
            <?php
            $applicants_stmt = $pdo->prepare("SELECT * FROM applications WHERE job_id = ?");
            $applicants_stmt->execute([$job['id']]);
            $applicants = $applicants_stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php if (count($applicants) > 0): ?>
              <div class="applicant-info">
                <ul class="list-unstyled">
            <?php foreach ($applicants as $applicant): ?>
                <li>
                    <strong>Nama:</strong> <?= htmlspecialchars($applicant['applicant_name']) ?><br>
                    <strong>Email:</strong> <?= htmlspecialchars($applicant['applicant_email']) ?><br>
                    <strong>Telepon:</strong> <?= htmlspecialchars($applicant['applicant_phone']) ?><br>
                    <strong>CV:</strong> <a href="<?= htmlspecialchars($applicant['cv_file']) ?>" target="_blank">Lihat CV</a><br>
                    <strong>Status:</strong> <?= htmlspecialchars($applicant['STATUS']) ?><br>
                    <form method="post" action="update_status.php">
                      <input type="hidden" name="application_id" value="<?= $applicant['application_id'] ?>">
                      <button type="submit" class="btn btn-custom">Konfirmasi Data & CV <?= $applicant['STATUS'] === 'Belum Ditinjau' ? 'Sudah Ditinjau' : 'Belum Ditinjau' ?></button>
                      <button type="submit" name="reject" value="1" class="btn btn-reject">Tolak Lamaran</button>
                    </form>
                    <?php if ($_SESSION['user_id'] === $applicant['applicant_id']): ?>
                      <a href="delete_application.php?id=<?= $applicant['application_id'] ?>" class="btn btn-reject" style="margin-left: 0.5rem;" onclick="return confirm('Apakah Anda yakin ingin menghapus lamaran ini?');">Hapus Lamaran</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
                </ul>
              </div>
            <?php else: ?>
              <p>Tidak ada pelamar untuk lowongan ini.</p>
            <?php endif; ?>
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
</script>
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
