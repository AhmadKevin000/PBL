<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: activities.php');
  exit;
}

$id = isset($_POST['id_kegiatan']) ? (int)$_POST['id_kegiatan'] : 0;
$judul = trim((string)($_POST['judul'] ?? ''));
$deskripsi = (string)($_POST['deskripsi'] ?? '');
$tanggal = trim((string)($_POST['tanggal'] ?? ''));
// handle optional image upload
$uploadPath = __DIR__ . '/../assets/uploads/activities/';
$fotoRel = null;
if (!is_dir($uploadPath)) {
  @mkdir($uploadPath, 0777, true);
}
if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $tmpName = $_FILES['foto']['tmp_name'];
  $origName = basename((string)$_FILES['foto']['name']);
  $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
  $allowed = ['jpg','jpeg','png','webp'];
  if (in_array($ext, $allowed, true)) {
    $newName = 'act_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = $uploadPath . $newName;
    if (move_uploaded_file($tmpName, $target)) {
      $fotoRel = 'assets/uploads/activities/' . $newName;
    }
  }
}

if ($judul === '' || $deskripsi === '') {
  header('Location: activities.php?error=' . urlencode('Judul dan deskripsi wajib diisi'));
  exit;
}

try {
  if ($id > 0) {
    // get existing foto if any
    $oldFoto = null;
    $stOld = $pdo->prepare('SELECT foto FROM kegiatan_lab WHERE id_kegiatan = :id');
    $stOld->execute([':id' => $id]);
    $rowOld = $stOld->fetch(PDO::FETCH_ASSOC);
    if ($rowOld && !empty($rowOld['foto'])) {
      $oldFoto = $rowOld['foto'];
    }

    $sql = 'UPDATE kegiatan_lab SET judul = :judul, deskripsi = :deskripsi, tanggal = :tanggal, foto = :foto WHERE id_kegiatan = :id';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':judul' => $judul,
      ':deskripsi' => $deskripsi,
      ':tanggal' => $tanggal !== '' ? $tanggal : null,
      ':foto' => $fotoRel !== null ? $fotoRel : $oldFoto,
      ':id' => $id,
    ]);
  } else {
    $sql = 'INSERT INTO kegiatan_lab (judul, deskripsi, tanggal, foto) VALUES (:judul, :deskripsi, :tanggal, :foto)';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':judul' => $judul,
      ':deskripsi' => $deskripsi,
      ':tanggal' => $tanggal !== '' ? $tanggal : null,
      ':foto' => $fotoRel,
    ]);
  }

  header('Location: activities.php?success=1');
  exit;
} catch (Exception $e) {
  header('Location: activities.php?error=' . urlencode($e->getMessage()));
  exit;
}
