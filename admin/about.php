<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

// Ambil satu record lab_profile (strategi: id_lab=1 atau pertama)
$stmt = $pdo->query('SELECT * FROM lab_profile ORDER BY id_lab ASC LIMIT 1');
$about = $stmt->fetch(PDO::FETCH_ASSOC) ?: [
  'id_lab' => 1,
  'nama_lab' => '',
  'nama_gedung' => '',
  'deskripsi' => '',
  'visi' => '',
  'misi' => '',
  'alamat' => '',
  'email' => '',
  'no_telp' => '',
  'link_maps' => ''
];
$success = isset($_GET['success']);
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola About</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Kelola About (lab_profile)</h1>
      <div>
        <a class="btn btn-secondary btn-sm" href="dashboard.php">Kembali</a>
        <a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
      </div>
    </div>

    <?php if ($success): ?>
      <div class="alert alert-success">Berhasil disimpan.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger">Gagal menyimpan: <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post" action="about_save.php" class="card p-3 shadow-sm bg-white">
      <input type="hidden" name="id_lab" value="<?php echo (int)$about['id_lab']; ?>">

      <div class="mb-3">
        <label class="form-label">Nama Lab</label>
        <input type="text" name="nama_lab" class="form-control" value="<?php echo htmlspecialchars($about['nama_lab']); ?>" required>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="4"><?php echo htmlspecialchars($about['deskripsi']); ?></textarea>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Visi</label>
        <textarea name="visi" class="form-control" rows="3"><?php echo htmlspecialchars($about['visi']); ?></textarea>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Misi</label>
        <textarea name="misi" class="form-control" rows="3"><?php echo htmlspecialchars($about['misi']); ?></textarea>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Nama Gedung</label>
         <input type="text" name="nama_gedung" class="form-control" value="<?php echo htmlspecialchars($about['nama_gedung']); ?>">
      </div>

      <div class="mb-3">
        <label class="form-label">Alamat</label>
        <input type="text" name="alamat" class="form-control" value="<?php echo htmlspecialchars($about['alamat']); ?>">
      </div>

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($about['email']); ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">No. Telp</label>
          <input type="text" name="no_telp" class="form-control" value="<?php echo htmlspecialchars($about['no_telp']); ?>">
        </div>
      </div>

      <div class="mb-3 mt-3">
        <label class="form-label">Link Google Maps</label>
        <input type="text" name="link_maps" class="form-control" value="<?php echo htmlspecialchars($about['link_maps']); ?>">
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="dashboard.php" class="btn btn-outline-secondary">Batal</a>
      </div>
    </form>
  </div>
</body>
</html>
