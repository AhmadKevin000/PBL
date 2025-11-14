<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  header('Location: activities.php');
  exit;
}

try {
  $st = $pdo->prepare('DELETE FROM kegiatan_lab WHERE id_kegiatan = :id');
  $st->execute([':id' => $id]);
  header('Location: activities.php?success=1');
  exit;
} catch (Exception $e) {
  header('Location: activities.php?error=' . urlencode($e->getMessage()));
  exit;
}
