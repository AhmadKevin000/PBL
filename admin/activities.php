<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

// Ambil semua kegiatan
$stmt = $pdo->query('SELECT id_kegiatan, judul, deskripsi, tanggal FROM kegiatan_lab ORDER BY tanggal DESC NULLS LAST, id_kegiatan ASC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jika mode edit
$edit = null;
if (isset($_GET['edit'])) {
  $id = (int)$_GET['edit'];
  $st = $pdo->prepare('SELECT * FROM kegiatan_lab WHERE id_kegiatan = :id');
  $st->execute([':id' => $id]);
  $edit = $st->fetch(PDO::FETCH_ASSOC) ?: null;
}

$success = isset($_GET['success']);
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Activities</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Kelola Activities (kegiatan_lab)</h1>
      <div>
        <a class="btn btn-secondary btn-sm" href="dashboard.php">Kembali</a>
        <a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
      </div>
    </div>

    <?php if ($success): ?>
      <div class="alert alert-success">Perubahan berhasil disimpan.</div>
    <?php endif; ?>
    <?php if ($error): ?>
      <div class="alert alert-danger">Terjadi kesalahan: <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Kegiatan</span>
            <a href="activities.php" class="btn btn-sm btn-primary">Kegiatan Baru</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th style="width: 60px;">#</th>
                    <th>Judul</th>
                    <th style="width: 110px;">Tanggal</th>
                    <th style="width: 110px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($rows): ?>
                    <?php foreach ($rows as $index => $row): ?>
                      <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($row['judul']); ?></td>
                        <td><?php echo $row['tanggal'] ? htmlspecialchars($row['tanggal']) : '-'; ?></td>
                        <td>
                          <a href="activities.php?edit=<?php echo (int)$row['id_kegiatan']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                          <a href="activities_delete.php?id=<?php echo (int)$row['id_kegiatan']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus kegiatan ini?');">Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center py-3">Belum ada data kegiatan.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header">
            <?php echo $edit ? 'Edit Kegiatan' : 'Kegiatan Baru'; ?>
          </div>
          <div class="card-body">
            <form method="post" action="activities_save.php" enctype="multipart/form-data">
              <input type="hidden" name="id_kegiatan" value="<?php echo $edit ? (int)$edit['id_kegiatan'] : 0; ?>">

              <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" name="judul" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['judul']) : ''; ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Foto (opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <?php if ($edit && !empty($edit['foto'])): ?>
                  <div class="mt-2">
                    <small>Foto saat ini:</small><br>
                    <img src="../<?php echo htmlspecialchars($edit['foto']); ?>" alt="Foto kegiatan" style="max-width: 120px; height: auto; border-radius: 4px;">
                  </div>
                <?php endif; ?>
              </div>

              <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?php echo $edit && $edit['tanggal'] ? htmlspecialchars($edit['tanggal']) : ''; ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="5" required><?php echo $edit ? htmlspecialchars($edit['deskripsi']) : ''; ?></textarea>
              </div>

              <button type="submit" class="btn btn-primary">Simpan</button>
              <?php if ($edit): ?>
                <a href="activities.php" class="btn btn-outline-secondary ms-2">Batal</a>
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
