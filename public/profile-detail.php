<?php
include '../includes/head.php';
include '../includes/header.php';

require_once __DIR__ . '/../config/koneksi.php';
$pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
if (!$pdo) {
  die('Koneksi database tidak tersedia');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  http_response_code(404);
  echo '<main class="main"><div class="container py-5"><h1>Profil tidak ditemukan</h1></div></main>';
  include '../includes/footer.php';
  exit;
}

$stmt = $pdo->prepare('SELECT nama, jabatan, nip, foto, kontak, bio_profile, pendidikan, penelitian FROM anggota_lab WHERE id_anggota = :id');
$stmt->execute([':id' => $id]);
$prof = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$prof) {
  http_response_code(404);
  echo '<main class="main"><div class="container py-5"><h1>Profil tidak ditemukan</h1></div></main>';
  include '../includes/footer.php';
  exit;
}
?>

<main class="main">
  <section class="section light-background">
    <div class="container py-4">
      <a href="index.php#profile" class="btn btn-outline-secondary btn-sm mb-3">&laquo; Kembali ke daftar profil</a>

      <div class="row g-4 align-items-start">
        <div class="col-md-4 text-center">
          <img src="../<?php echo htmlspecialchars($prof['foto']); ?>" alt="<?php echo htmlspecialchars($prof['nama']); ?>" class="img-fluid rounded-circle mb-3" style="max-width: 220px; height: auto; object-fit: cover;">
          <h2 class="h4 mb-1"><?php echo htmlspecialchars($prof['nama']); ?></h2>
          <?php if (!empty($prof['jabatan'])): ?>
            <p class="mb-1"><?php echo htmlspecialchars($prof['jabatan']); ?></p>
          <?php endif; ?>
          <?php if (!empty($prof['nip'])): ?>
            <p class="mb-1"><strong>NIP:</strong> <?php echo htmlspecialchars($prof['nip']); ?></p>
          <?php endif; ?>
          <?php if (!empty($prof['kontak'])): ?>
            <p class="mb-0"><strong>Kontak:</strong> <?php echo htmlspecialchars($prof['kontak']); ?></p>
          <?php endif; ?>
        </div>

        <div class="col-md-8">
          <?php if (!empty($prof['bio_profile'])): ?>
            <h3 class="h5">Profil Singkat</h3>
            <p><?php echo nl2br(htmlspecialchars($prof['bio_profile'])); ?></p>
          <?php endif; ?>

          <?php if (!empty($prof['pendidikan'])): ?>
            <h3 class="h5 mt-4">Pendidikan</h3>
            <p><?php echo nl2br(htmlspecialchars($prof['pendidikan'])); ?></p>
          <?php endif; ?>

          <?php if (!empty($prof['penelitian'])): ?>
            <h3 class="h5 mt-4">Penelitian</h3>
            <p><?php echo nl2br(htmlspecialchars($prof['penelitian'])); ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../includes/footer.php'; ?>
