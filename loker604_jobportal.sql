-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2025 at 11:29 AM
-- Server version: 11.4.7-MariaDB-cll-lve
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loker604_jobportal`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `applicant_email` varchar(255) NOT NULL,
  `applicant_phone` varchar(255) NOT NULL,
  `cv_file` varchar(255) NOT NULL,
  `applied_at` datetime DEFAULT current_timestamp(),
  `applicant_id` int(11) DEFAULT NULL,
  `STATUS` enum('Belum Ditinjau','Sudah Ditinjau','Ditolak') NOT NULL DEFAULT 'Belum Ditinjau'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `job_id`, `applicant_name`, `applicant_email`, `applicant_phone`, `cv_file`, `applied_at`, `applicant_id`, `STATUS`) VALUES
(13, 3, 'Frederico Andresta Albert', 'andrestaalbert126@gmail.com', '081225708810', 'uploads/Black Minimalist Professional CV Resume (1).pdf', '2025-05-18 02:38:09', 3, 'Ditolak'),
(15, 4, 'Frederico Albert Andresta', 'fredericoalbert12605@gmail.com', '089668880436', 'uploads/albert cv.docx', '2025-05-30 00:49:18', 5, 'Sudah Ditinjau'),
(24, 4, 'Asep', 'asepsahasukakayang@gmail.com', '083289897171', 'uploads/2172-Article Text-5158-1-10-20211130.pdf', '2025-06-13 01:26:40', 11, 'Belum Ditinjau'),
(25, 3, 'Imas Masturoh', 'imasmasturoh2310@gmail.com', '085523607834', 'uploads/CV ATS Imas Masturoh.pdf', '2025-06-13 17:09:28', 13, 'Sudah Ditinjau'),
(26, 4, 'Pramudya Ilham Saputra', 'pramudyaseyegan@gmail.com', '081222384928', 'uploads/UAS_Biokimia_Samuel_Ary_Mukti_24544590PT10355-1749642852153.pdf', '2025-06-14 13:36:17', 14, 'Ditolak'),
(28, 3, 'BAyu aditya', 'bayu@gmail.com', '087776888977', 'uploads/albert cv (2).docx', '2025-06-16 10:46:28', 16, 'Belum Ditinjau');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `posted_date` datetime DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_title`, `company_name`, `location`, `description`, `posted_date`, `created_by`) VALUES
(3, 'Staff Kasir', 'ClaypotKey', 'Mertoyudan, Kabupaten Magelang, Jawa Tengah', 'Posisi yang bertanggung jawab atas proses transaksi pembayaran pelanggan di berbagai tempat seperti toko, restoran, atau supermarket. Tugas utama adalah menerima pembayaran, memberikan struk, dan membungkus belanjaan. Juga dapat bertanggung jawab untuk membantu pelanggan, menjawab pertanyaan, dan bahkan menawarkan produk tambahan. \r\n\r\nKriteria : \r\n- Kemampuan Berhitung: Mahir dalam perhitungan, terutama dalam memberikan kembalian.\r\n- Komunikasi: Mampu berkomunikasi dengan jelas dan ramah kepada pelanggan.\r\n- Pengetahuan Produk: Memahami produk yang dijual dan mampu menjelaskan kepada pelanggan.\r\n- Ketelitian: Teliti dalam mencatat dan menghitung transaksi.\r\n- Customer Service: Bersikap ramah, membantu, dan mampu menangani keluhan pelanggan.\r\n- Jujur: Menjaga kepercayaan dalam menangani uang dan transaksi.\r\n- Sistem POS: Mampu menggunakan sistem POS dengan baik.', '2025-05-06 11:21:42', 5),
(4, 'Mekanik Motor', 'Pramspeed', 'Jln.Godean Km 5, Sleman, Yogyakarta', 'Ahli dalam merawat, memperbaiki, dan memodifikasi sepeda motor. Mereka memiliki pengetahuan dan keterampilan untuk mendiagnosa, memperbaiki, dan melakukan perawatan berkala pada berbagai jenis sepeda motor.  \r\n\r\nKriteria : \r\n- Pendidikan Minimal: SMK Otomotif atau sederajat.\r\n- Pengalaman: Minimal 1 tahun sebagai mekanik motor (fresh graduate dipertimbangkan).\r\n- Kemampuan Teknis: Paham sistem mesin, kelistrikan, dan perawatan motor.\r\n- Kemampuan Diagnostik: Mampu menganalisis kerusakan dan menentukan solusi.\r\n- Sertifikasi (opsional): Sertifikat pelatihan mekanik/motor dari lembaga resmi.\r\n- Kedisiplinan & Tanggung Jawab: Tepat waktu, teliti, dan bertanggung jawab.\r\n- Kerja Tim & Komunikasi: Mampu bekerja sama dalam tim dan berkomunikasi dengan pelanggan.\r\n- Fisik & Ketahanan Kerja: Sehat jasmani dan mampu bekerja dalam tekanan.\r\n- Komitmen & Loyalitas: Bersedia bekerja jangka panjang dan mengikuti aturan bengkel.\r\n- KTP & SIM: Memiliki KTP aktif, SIM C (lebih disukai jika bisa mengendarai motor uji coba).', '2025-05-06 11:26:05', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `PASSWORD`, `email`, `full_name`, `phone_number`, `created_at`, `role`) VALUES
(3, 'Andresta', '$2y$10$byML5AN5Sy8qNlEAp66TPuWdE7dHvZAyTp/xPORuA7.vdI/mVuSM6', 'andrestaalbert126@gmail.com', 'Andresta Albert Frederico', '081225708810', '2025-05-04 21:06:06', 'user'),
(5, 'Frederico', '$2y$10$EUicVLulD5e7YU2QCo9EzOZ1DDGaLKP8uPiZGszGGkw1maH16P5eq', 'fredericoalbert12605@gmail.com', 'Frederico Albert Andresta', '089668880436', '2025-05-06 10:52:48', 'user'),
(9, 'admin', '$2y$10$ITPNwANsMcEe.Rlyfiur2uVOCeXPBMWFf9fZnW6HaMmokJxOKe3K2', 'admin@gmail.com', 'Administrator', '081234567890', '2025-05-28 20:12:31', 'admin'),
(11, 'AsepSaha99', '$2y$10$xMPUxtpINubat.fnlUCzmOuxN416jpyQYM/Kx8GcnPFHuEycqlmKK', 'asepsahasukakayang@gmail.com', 'Asep', '083289897171', '2025-06-13 01:24:57', 'user'),
(12, 'Mathius', '$2y$10$fhtaNLjyRRCf4JSCxO6ieesMjvjBP5s3MXPWJxWxRSSIXks8X/Jua', 'mathiusjati19@gmail.com', 'Mathius Ronald', '08122768281', '2025-06-13 06:18:12', 'user'),
(13, 'imass', '$2y$10$oICpAVWFeYlMkHcROYDEeOYxmY63cQXEMuk9bRadOd1HsotXZdZFe', 'imasmasturoh2310@gmail.com', 'Imas Masturoh', '085523607834', '2025-06-13 17:08:00', 'user'),
(14, 'Seyegan', '$2y$10$FH2Fq8Ki0vFHGMsLhyoVFu7AkfjzCE9plwwS5Bt0hTfIjsORb9SoC', 'pramudyaseyegan@gmail.com', 'Pramudya Ilham Saputra', '081222384928', '2025-06-14 13:28:50', 'user'),
(16, 'bayuu', '$2y$10$rQgScq82q1gsDg07Dq5jd.AfCZeJpjE5.LA6HDPpz7IMQPJptZo6W', 'bayu@gmail.com', 'BAyu aditya', '087776888977', '2025-06-16 10:45:27', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `applicant_id` (`applicant_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`applicant_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
