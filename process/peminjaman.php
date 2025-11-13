<?php
// Simple handler for Lab borrowing form
// Expects: nama_peminjam, nim, email, mata_kuliah, tanggal, waktu_mulai, waktu_selesai, keperluan

header('Content-Type: text/plain; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  http_response_code(405);
  echo 'Method not allowed';
  exit;
}

$required = ['nama_peminjam','nim_nip','email','mata_kuliah','tanggal','waktu_mulai','waktu_selesai','keperluan'];
foreach ($required as $field) {
  if (!isset($_POST[$field]) || trim((string)$_POST[$field]) === '') {
    http_response_code(400);
    echo 'Field missing: ' . $field;
    exit;
  }
}

$nama = trim((string)$_POST['nama_peminjam']);
$nim_nip = trim((string)$_POST['nim_nip']);
$email = trim((string)$_POST['email']);
$mata_kuliah = trim((string)$_POST['mata_kuliah']);
$tanggal = trim((string)$_POST['tanggal']);
$mulai = trim((string)$_POST['waktu_mulai']);
$selesai = trim((string)$_POST['waktu_selesai']);
$keperluan = trim((string)$_POST['keperluan']);

try {
  require_once __DIR__ . '/../config/koneksi.php'; // provides $pdo
  // Align variable from config (it defines $conn)
  if (!isset($pdo) && isset($conn)) {
    $pdo = $conn;
  }

  $sql = 'INSERT INTO peminjaman_lab (nama_peminjam, nim_nip, email, mata_kuliah, tanggal_pinjam, jam_mulai, jam_selesai, keperluan, status)
          VALUES (:nama, :nim_nip, :email, :mk, :tgl, :mulai, :selesai, :keperluan, :status)';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':nama' => $nama,
    ':nim_nip' => $nim_nip,
    ':email' => $email,
    ':mk' => $mata_kuliah,
    ':tgl' => $tanggal,
    ':mulai' => $mulai,
    ':selesai' => $selesai,
    ':keperluan' => $keperluan,
    ':status' => 'Menunggu',
  ]);

  echo 'OK'; // for php-email-form compatible frontend
} catch (Exception $e) {
  http_response_code(500);
  echo 'Error: ' . $e->getMessage();
}
