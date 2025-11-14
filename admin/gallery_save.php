<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: gallery.php');
  exit;
}

$id = isset($_POST['id_gallery']) ? (int)$_POST['id_gallery'] : 0;
$caption = (string)($_POST['caption'] ?? '');
$sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
$is_active = isset($_POST['is_active']) ? 1 : 0;

// upload handling
$uploadPath = __DIR__ . '/../assets/uploads/gallery/';
$imageRel = null;
if (!is_dir($uploadPath)) {
  @mkdir($uploadPath, 0777, true);
}

if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $tmp = $_FILES['image']['tmp_name'];
  $orig = basename((string)$_FILES['image']['name']);
  $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
  $allowed = ['jpg','jpeg','png','webp'];
  if (in_array($ext, $allowed, true)) {
    $newName = 'gal_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = $uploadPath . $newName;
    if (move_uploaded_file($tmp, $target)) {
      $imageRel = 'assets/uploads/gallery/' . $newName;
    }
  }
}

try {
  if ($id > 0) {
    // get old image if none uploaded
    $oldImage = null;
    $stOld = $pdo->prepare('SELECT image_path FROM gallery WHERE id_gallery = :id');
    $stOld->execute([':id' => $id]);
    $rowOld = $stOld->fetch(PDO::FETCH_ASSOC);
    if ($rowOld && !empty($rowOld['image_path'])) {
      $oldImage = $rowOld['image_path'];
    }

    $sql = 'UPDATE gallery SET image_path = :image, caption = :caption, sort_order = :sort_order, is_active = :active WHERE id_gallery = :id';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':image' => $imageRel !== null ? $imageRel : $oldImage,
      ':caption' => $caption,
      ':sort_order' => $sort_order,
      ':active' => $is_active,
      ':id' => $id,
    ]);
  } else {
    if ($imageRel === null) {
      header('Location: gallery.php?error=' . urlencode('Gambar wajib diupload untuk item baru'));
      exit;
    }
    $sql = 'INSERT INTO gallery (image_path, caption, sort_order, is_active) VALUES (:image, :caption, :sort_order, :active)';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':image' => $imageRel,
      ':caption' => $caption,
      ':sort_order' => $sort_order,
      ':active' => $is_active,
    ]);
  }

  header('Location: gallery.php?success=1');
  exit;
} catch (Exception $e) {
  header('Location: gallery.php?error=' . urlencode($e->getMessage()));
  exit;
}
