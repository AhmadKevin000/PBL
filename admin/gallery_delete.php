<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header('Location: gallery.php');
  exit;
}

try {
  // optionally fetch image_path and delete file from disk (tidak wajib, bisa ditambah nanti)
  $st = $pdo->prepare('DELETE FROM gallery WHERE id_gallery = :id');
  $st->execute([':id' => $id]);
  header('Location: gallery.php?success=1');
  exit;
} catch (Exception $e) {
  header('Location: gallery.php?error=' . urlencode($e->getMessage()));
  exit;
}
