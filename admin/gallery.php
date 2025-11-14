<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

// Ambil semua item gallery
$stmt = $pdo->query('SELECT id_gallery, image_path, caption, sort_order, is_active, created_at FROM gallery ORDER BY sort_order ASC, id_gallery ASC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mode edit
$edit = null;
if (isset($_GET['edit'])) {
  $id = (int)$_GET['edit'];
  $st = $pdo->prepare('SELECT * FROM gallery WHERE id_gallery = :id');
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
  <title>Kelola Gallery</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Kelola Gallery</h1>
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
      <div class="col-md-7">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <span>Daftar Gallery</span>
            <a href="gallery.php" class="btn btn-sm btn-primary">Item Baru</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm mb-0 align-middle">
                <thead>
                  <tr>
                    <th style="width:50px;">Urut</th>
                    <th>Gambar</th>
                    <th>Caption</th>
                    <th style="width:80px;">Aktif</th>
                    <th style="width:120px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($rows): ?>
                    <?php foreach ($rows as $row): ?>
                      <tr>
                        <td><?php echo (int)$row['sort_order']; ?></td>
                        <td>
                          <?php if (!empty($row['image_path'])): ?>
                            <img src="../<?php echo htmlspecialchars($row['image_path']); ?>" alt="" style="max-width:80px; height:auto; border-radius:4px;">
                          <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['caption']); ?></td>
                        <td>
                          <?php if (!empty($row['is_active'])): ?>
                            <span class="badge bg-success">Ya</span>
                          <?php else: ?>
                            <span class="badge bg-secondary">Tidak</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <a href="gallery.php?edit=<?php echo (int)$row['id_gallery']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                          <a href="gallery_delete.php?id=<?php echo (int)$row['id_gallery']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus item gallery ini?');">Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="text-center py-3">Belum ada data gallery.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-5">
        <div class="card shadow-sm">
          <div class="card-header">
            <?php echo $edit ? 'Edit Item Gallery' : 'Item Gallery Baru'; ?>
          </div>
          <div class="card-body">
            <form method="post" action="gallery_save.php" enctype="multipart/form-data">
              <input type="hidden" name="id_gallery" value="<?php echo $edit ? (int)$edit['id_gallery'] : 0; ?>">

              <div class="mb-3">
                <label class="form-label">Gambar</label>
                <input type="file" name="image" class="form-control" accept="image/*" <?php echo $edit ? '' : 'required'; ?>>
                <?php if ($edit && !empty($edit['image_path'])): ?>
                  <div class="mt-2">
                    <small>Gambar saat ini:</small><br>
                    <img src="../<?php echo htmlspecialchars($edit['image_path']); ?>" alt="" style="max-width:120px; height:auto; border-radius:4px;">
                  </div>
                <?php endif; ?>
              </div>

              <div class="mb-3">
                <label class="form-label">Caption</label>
                <textarea name="caption" class="form-control" rows="3"><?php echo $edit ? htmlspecialchars($edit['caption']) : ''; ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="<?php echo $edit ? (int)$edit['sort_order'] : 0; ?>">
              </div>

              <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" <?php echo !$edit || !empty($edit['is_active']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="is_active">
                  Tampilkan di halaman publik
                </label>
              </div>

              <button type="submit" class="btn btn-primary">Simpan</button>
              <?php if ($edit): ?>
                <a href="gallery.php" class="btn btn-outline-secondary ms-2">Batal</a>
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
