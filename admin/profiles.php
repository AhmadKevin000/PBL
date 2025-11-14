<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

// Ambil semua anggota_lab
$stmt = $pdo->query('SELECT id_anggota, nama, jabatan, nip, foto, sort_order FROM anggota_lab ORDER BY sort_order ASC, id_anggota ASC');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mode edit
$edit = null;
if (isset($_GET['edit'])) {
  $id = (int)$_GET['edit'];
  $st = $pdo->prepare('SELECT * FROM anggota_lab WHERE id_anggota = :id');
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
  <title>Kelola Profile Anggota</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0">Kelola Profile Anggota (anggota_lab)</h1>
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
            <span>Daftar Anggota Lab</span>
            <a href="profiles.php" class="btn btn-sm btn-primary">Anggota Baru</a>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-sm mb-0 align-middle">
                <thead>
                  <tr>
                    <th style="width:60px;">Urut</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th style="width:120px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($rows): ?>
                    <?php foreach ($rows as $row): ?>
                      <tr>
                        <td><?php echo (int)$row['sort_order']; ?></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                        <td>
                          <a href="profiles.php?edit=<?php echo (int)$row['id_anggota']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                          <a href="profiles_delete.php?id=<?php echo (int)$row['id_anggota']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus profil ini?');">Hapus</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="4" class="text-center py-3">Belum ada data anggota.</td>
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
            <?php echo $edit ? 'Edit Profil Anggota' : 'Profil Anggota Baru'; ?>
          </div>
          <div class="card-body">
            <form method="post" action="profiles_save.php" enctype="multipart/form-data">
              <input type="hidden" name="id_anggota" value="<?php echo $edit ? (int)$edit['id_anggota'] : 0; ?>">

              <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['nama']) : ''; ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['nip']) : ''; ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['jabatan']) : ''; ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="foto" class="form-control" accept="image/*" <?php echo $edit ? '' : 'required'; ?>>
                <?php if ($edit && !empty($edit['foto'])): ?>
                  <div class="mt-2">
                    <small>Foto saat ini:</small><br>
                    <img src="../<?php echo htmlspecialchars($edit['foto']); ?>" alt="Foto" style="max-width:120px; height:auto; border-radius:50%;">
                  </div>
                <?php endif; ?>
              </div>

              <div class="mb-3">
                <label class="form-label">Kontak (email/telepon)</label>
                <input type="text" name="kontak" class="form-control" value="<?php echo $edit ? htmlspecialchars($edit['kontak']) : ''; ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Urutan</label>
                <input type="number" name="sort_order" class="form-control" value="<?php echo $edit ? (int)$edit['sort_order'] : 0; ?>">
              </div>

              <div class="mb-3">
                <label class="form-label">Profil Singkat</label>
                <textarea name="bio_profile" class="form-control" rows="3"><?php echo $edit ? htmlspecialchars($edit['bio_profile']) : ''; ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Pendidikan (satu per baris)</label>
                <textarea name="pendidikan" class="form-control" rows="3"><?php echo $edit ? htmlspecialchars($edit['pendidikan']) : ''; ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Penelitian (satu per baris)</label>
                <textarea name="penelitian" class="form-control" rows="4"><?php echo $edit ? htmlspecialchars($edit['penelitian']) : ''; ?></textarea>
              </div>

              <button type="submit" class="btn btn-primary">Simpan</button>
              <?php if ($edit): ?>
                <a href="profiles.php" class="btn btn-outline-secondary ms-2">Batal</a>
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
