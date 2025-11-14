<?php
require_once __DIR__ . '/_auth.php';
require_login();

require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) {
  die('Koneksi database tidak tersedia');
}

// Filter status: all | menunggu | disetujui | ditolak
$allowedStatus = ['all', 'menunggu', 'disetujui', 'ditolak'];
$statusFilter = isset($_GET['status']) ? strtolower((string)$_GET['status']) : 'all';
if (!in_array($statusFilter, $allowedStatus, true)) {
  $statusFilter = 'all';
}

try {
  if ($statusFilter === 'all') {
    $sql = "SELECT id_peminjaman, nama_peminjam, nim_nip, email, mata_kuliah, tanggal_pinjam, jam_mulai, jam_selesai, keperluan, status
            FROM peminjaman_lab
            ORDER BY status ASC, tanggal_pinjam DESC, id_peminjaman DESC";
    $stmt = $pdo->query($sql);
  } else {
    $sql = "SELECT id_peminjaman, nama_peminjam, nim_nip, email, mata_kuliah, tanggal_pinjam, jam_mulai, jam_selesai, keperluan, status
            FROM peminjaman_lab
            WHERE LOWER(status) = :st
            ORDER BY tanggal_pinjam DESC, id_peminjaman DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':st' => $statusFilter]);
  }

  if ($statusFilter === 'all') {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } else {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
} catch (Exception $e) {
  die('Gagal mengambil data peminjaman: ' . htmlspecialchars($e->getMessage()));
}

function status_badge_class($status)
{
  $status = strtolower((string)$status);
  if ($status === 'disetujui') return 'bg-success';
  if ($status === 'ditolak') return 'bg-danger';
  return 'bg-secondary'; // Menunggu / lainnya
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Peminjaman Lab</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h4 mb-0">Kelola Peminjaman Lab</h1>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="dashboard.php">&laquo; Kembali ke Dashboard</a>
        <a class="btn btn-outline-danger btn-sm" href="logout.php">Logout</a>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>Daftar Pengajuan Peminjaman</span>
        <div class="btn-group btn-group-sm" role="group" aria-label="Filter status">
          <a href="peminjaman.php?status=all" class="btn btn-outline-secondary<?php echo $statusFilter === 'all' ? ' active' : ''; ?>">Semua</a>
          <a href="peminjaman.php?status=menunggu" class="btn btn-outline-secondary<?php echo $statusFilter === 'menunggu' ? ' active' : ''; ?>">Menunggu</a>
          <a href="peminjaman.php?status=disetujui" class="btn btn-outline-secondary<?php echo $statusFilter === 'disetujui' ? ' active' : ''; ?>">Disetujui</a>
          <a href="peminjaman.php?status=ditolak" class="btn btn-outline-secondary<?php echo $statusFilter === 'ditolak' ? ' active' : ''; ?>">Ditolak</a>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th style="width: 60px;">#</th>
                <th>Nama</th>
                <th style="width: 140px;">NIM/NIP</th>
                <th>Mata Kuliah</th>
                <th style="width: 130px;">Tanggal</th>
                <th style="width: 110px;">Jam</th>
                <th>Keperluan</th>
                <th style="width: 120px;">Status</th>
                <th style="width: 170px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($rows): ?>
                <?php foreach ($rows as $index => $row): ?>
                  <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td>
                      <div class="fw-semibold"><?php echo htmlspecialchars($row['nama_peminjam']); ?></div>
                      <div class="small text-muted"><?php echo htmlspecialchars($row['email']); ?></div>
                    </td>
                    <td><?php echo htmlspecialchars($row['nim_nip']); ?></td>
                    <td><?php echo htmlspecialchars($row['mata_kuliah']); ?></td>
                    <td><?php echo htmlspecialchars($row['tanggal_pinjam']); ?></td>
                    <td><?php echo htmlspecialchars($row['jam_mulai'] . ' - ' . $row['jam_selesai']); ?></td>
                    <td style="max-width: 260px;">
                      <div class="small text-wrap"><?php echo nl2br(htmlspecialchars($row['keperluan'])); ?></div>
                    </td>
                    <td>
                      <span class="badge <?php echo status_badge_class($row['status']); ?>">
                        <?php echo htmlspecialchars($row['status']); ?>
                      </span>
                    </td>
                    <td>
                      <?php if (strtolower((string)$row['status']) === 'menunggu'): ?>
                        <a href="peminjaman_update.php?id=<?php echo (int)$row['id_peminjaman']; ?>&aksi=setujui" class="btn btn-sm btn-success mb-1" onclick="return confirm('Setujui peminjaman ini?');">Setujui</a>
                        <a href="peminjaman_update.php?id=<?php echo (int)$row['id_peminjaman']; ?>&aksi=tolak" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Tolak peminjaman ini?');">Tolak</a>
                      <?php else: ?>
                        <span class="text-muted small">Tidak ada aksi</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="9" class="text-center py-3">Belum ada data peminjaman.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
