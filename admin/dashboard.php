<?php
require_once __DIR__ . '/_auth.php';
require_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h4 mb-0">Dashboard Admin</h1>
      <a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
    </div>

    <div class="row g-3">
      <div class="col-md-3">
        <a class="btn btn-primary w-100" href="peminjaman.php">Kelola Peminjaman Lab</a>
      </div>
      <div class="col-md-3">
        <a class="btn btn-primary w-100" href="about.php">Kelola About (lab_profile)</a>
      </div>
      <div class="col-md-3">
        <a class="btn btn-primary w-100" href="activities.php">Kelola Activities (kegiatan_lab)</a>
      </div>
      <div class="col-md-3">
        <a class="btn btn-primary w-100" href="gallery.php">Kelola Gallery</a>
      </div>
      <div class="col-md-3">
        <a class="btn btn-primary w-100" href="profiles.php">Kelola Profile (anggota_lab)</a>
      </div>
    </div>
  </div>
</body>
</html>
