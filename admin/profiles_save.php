<?php
require_once __DIR__ . '/_auth.php';
require_login();
require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) { die('DB not available'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: profiles.php');
  exit;
}

$id = isset($_POST['id_anggota']) ? (int)$_POST['id_anggota'] : 0;
$nama = trim((string)($_POST['nama'] ?? ''));
$nip = trim((string)($_POST['nip'] ?? ''));
$jabatan = trim((string)($_POST['jabatan'] ?? ''));
$kontak = trim((string)($_POST['kontak'] ?? ''));
$sort_order = isset($_POST['sort_order']) ? (int)$_POST['sort_order'] : 0;
$bio_profile = (string)($_POST['bio_profile'] ?? '');
$pendidikan = (string)($_POST['pendidikan'] ?? '');
$penelitian = (string)($_POST['penelitian'] ?? '');

if ($nama === '') {
  header('Location: profiles.php?error=' . urlencode('Nama wajib diisi'));
  exit;
}

// Upload foto
$uploadPath = __DIR__ . '/../assets/uploads/profile/';
$fotoRel = null;
if (!is_dir($uploadPath)) {
  @mkdir($uploadPath, 0777, true);
}

if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
  $tmp = $_FILES['foto']['tmp_name'];
  $orig = basename((string)$_FILES['foto']['name']);
  $ext = strtolower(pathinfo($orig, PATHINFO_EXTENSION));
  $allowed = ['jpg','jpeg','png','webp'];
  if (in_array($ext, $allowed, true)) {
    $newName = 'prof_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target = $uploadPath . $newName;
    if (move_uploaded_file($tmp, $target)) {
      $fotoRel = 'assets/uploads/profile/' . $newName;
    }
  }
}

try {
  if ($id > 0) {
    // ambil foto lama jika tidak upload baru
    $oldFoto = null;
    $stOld = $pdo->prepare('SELECT foto FROM anggota_lab WHERE id_anggota = :id');
    $stOld->execute([':id' => $id]);
    $rowOld = $stOld->fetch(PDO::FETCH_ASSOC);
    if ($rowOld && !empty($rowOld['foto'])) {
      $oldFoto = $rowOld['foto'];
    }

    $sql = 'UPDATE anggota_lab SET nama = :nama, nip = :nip, jabatan = :jabatan, kontak = :kontak, sort_order = :sort_order, bio_profile = :bio, pendidikan = :pendidikan, penelitian = :penelitian, foto = :foto WHERE id_anggota = :id';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':nama' => $nama,
      ':nip' => $nip,
      ':jabatan' => $jabatan,
      ':kontak' => $kontak,
      ':sort_order' => $sort_order,
      ':bio' => $bio_profile,
      ':pendidikan' => $pendidikan,
      ':penelitian' => $penelitian,
      ':foto' => $fotoRel !== null ? $fotoRel : $oldFoto,
      ':id' => $id,
    ]);
  } else {
    if ($fotoRel === null) {
      header('Location: profiles.php?error=' . urlencode('Foto wajib diupload untuk profil baru'));
      exit;
    }
    $sql = 'INSERT INTO anggota_lab (nama, nip, jabatan, kontak, sort_order, bio_profile, pendidikan, penelitian, foto) VALUES (:nama, :nip, :jabatan, :kontak, :sort_order, :bio, :pendidikan, :penelitian, :foto)';
    $st = $pdo->prepare($sql);
    $st->execute([
      ':nama' => $nama,
      ':nip' => $nip,
      ':jabatan' => $jabatan,
      ':kontak' => $kontak,
      ':sort_order' => $sort_order,
      ':bio' => $bio_profile,
      ':pendidikan' => $pendidikan,
      ':penelitian' => $penelitian,
      ':foto' => $fotoRel,
    ]);
  }

  header('Location: profiles.php?success=1');
  exit;
} catch (Exception $e) {
  header('Location: profiles.php?error=' . urlencode($e->getMessage()));
  exit;
}
