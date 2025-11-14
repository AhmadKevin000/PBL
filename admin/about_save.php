<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: about.php');
  exit;
}

$id_lab = isset($_POST['id_lab']) ? (int)$_POST['id_lab'] : 0;
$nama_lab = trim((string)($_POST['nama_lab'] ?? ''));
$nama_gedung = trim((string)($_POST['nama_gedung'] ?? ''));
$deskripsi = (string)($_POST['deskripsi'] ?? '');
$visi = (string)($_POST['visi'] ?? '');
$misi = (string)($_POST['misi'] ?? '');
$alamat = trim((string)($_POST['alamat'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$no_telp = trim((string)($_POST['no_telp'] ?? ''));
$link_maps = trim((string)($_POST['link_maps'] ?? ''));

try {
  // Apakah ada record?
  $stmt = $pdo->query('SELECT id_lab FROM lab_profile ORDER BY id_lab ASC LIMIT 1');
  $exists = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($exists) {
    $sql = 'UPDATE lab_profile SET nama_lab = :nama_lab, nama_gedung = :nama_gedung, deskripsi = :deskripsi, visi = :visi, misi = :misi, alamat = :alamat, email = :email, no_telp = :no_telp, link_maps = :link_maps, updated_at = now() WHERE id_lab = :id';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':nama_lab' => $nama_lab,
      ':nama_gedung' => $nama_gedung,
      ':deskripsi' => $deskripsi,
      ':visi' => $visi,
      ':misi' => $misi,
      ':alamat' => $alamat,
      ':email' => $email,
      ':no_telp' => $no_telp,
      ':link_maps' => $link_maps,
      ':id' => (int)$exists['id_lab'],
    ]);
  } else {
    $sql = 'INSERT INTO lab_profile (nama_lab, nama_gedung, deskripsi, visi, misi, alamat, email, no_telp, link_maps, updated_at) VALUES (:nama_lab, :nama_gedung, :deskripsi, :visi, :misi, :alamat, :email, :no_telp, :link_maps, now())';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':nama_lab' => $nama_lab,
      ':nama_gedung' => $nama_gedung,
      ':deskripsi' => $deskripsi,
      ':visi' => $visi,
      ':misi' => $misi,
      ':alamat' => $alamat,
      ':email' => $email,
      ':no_telp' => $no_telp,
      ':link_maps' => $link_maps,
    ]);
  }

  header('Location: about.php?success=1');
  exit;
} catch (Exception $e) {
  header('Location: about.php?error=' . urlencode($e->getMessage()));
  exit;
}
