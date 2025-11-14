<?php
require_once __DIR__ . '/_auth.php';
require_login();

if (!isset($_GET['id'], $_GET['aksi'])) {
  header('Location: peminjaman.php');
  exit;
}

$id = (int)$_GET['id'];
$aksi = strtolower((string)$_GET['aksi']);

if ($id <= 0 || !in_array($aksi, ['setujui', 'tolak'], true)) {
  header('Location: peminjaman.php');
  exit;
}

$statusBaru = $aksi === 'setujui' ? 'Disetujui' : 'Ditolak';

require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) {
  die('Koneksi database tidak tersedia');
}

try {
  $stmt = $pdo->prepare('UPDATE peminjaman_lab SET status = :st WHERE id_peminjaman = :id');
  $stmt->execute([
    ':st' => $statusBaru,
    ':id' => $id,
  ]);
} catch (Exception $e) {
  // Untuk kesederhanaan, tampilkan pesan error dasar
  die('Gagal mengubah status peminjaman: ' . htmlspecialchars($e->getMessage()));
}

header('Location: peminjaman.php');
exit;
