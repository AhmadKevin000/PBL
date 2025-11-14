<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header('Location: profiles.php');
  exit;
}

try {
  $st = $pdo->prepare('DELETE FROM anggota_lab WHERE id_anggota = :id');
  $st->execute([':id' => $id]);
  header('Location: profiles.php?success=1');
  exit;
} catch (Exception $e) {
  header('Location: profiles.php?error=' . urlencode($e->getMessage()));
  exit;
}
