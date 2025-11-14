<footer id="footer" class="footer">

  <?php
    require_once __DIR__ . '/../config/koneksi.php';
    $pdo = isset($pdo) ? $pdo : (isset($conn) ? $conn : null);
    $lp = null;
    if ($pdo) {
      try {
        $st = $pdo->query('SELECT * FROM lab_profile ORDER BY id_lab ASC LIMIT 1');
        $lp = $st->fetch(PDO::FETCH_ASSOC);
      } catch (Exception $e) {
        $lp = null;
      }
    }
    $gedung = $lp && isset($lp['nama_gedung']) ? $lp['nama_gedung'] : '';
    $alamat = $lp && isset($lp['alamat']) ? $lp['alamat'] : 'Alamat belum diisi';
    $email  = $lp && isset($lp['email']) ? $lp['email'] : 'email@example.com';
    $telp   = $lp && isset($lp['no_telp']) ? $lp['no_telp'] : '+62-000-0000';
    $maps   = $lp && isset($lp['link_maps']) && $lp['link_maps'] !== '' ? $lp['link_maps'] : 'https://maps.app.goo.gl/gjbgHqMMvzLyqgk57';
  ?>

  <div class="container footer-top">
    <div class="row gy-4">

      <div class="col-lg-4 col-md-6 footer-about">
        <a href="index.php" class="logo d-flex align-items-center">
          <span class="sitename">Landing</span>
        </a>
        <div class="footer-contact pt-3">
          <?php if ($gedung !== ''): ?>
            <p><strong><?php echo htmlspecialchars($gedung); ?></strong></p>
          <?php endif; ?>
          <p><?php echo htmlspecialchars($alamat); ?></p>
          <p class="mt-3"><strong><i class="bi bi-telephone-outbound-fill"> </i>Phone : </strong> <span><?php echo htmlspecialchars($telp); ?></span></p>
          <p><strong><i class="bi bi-envelope-fill"> </i>Email : </strong> <span><?php echo htmlspecialchars($email); ?></span></p>
        </div>
        <div class="social-links d-flex mt-4">
          <a href=""><i class="bi bi-twitter-x"></i></a>
          <a href=""><i class="bi bi-facebook"></i></a>
          <a href=""><i class="bi bi-instagram"></i></a>
          <a href=""><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <div class="col-lg-8 col-md-6 footer-map">
        <h4 class="footer-map-title">Lokasi</h4>
        <div class="map-container">
          <a href="<?php echo htmlspecialchars($maps); ?>" target="_blank" rel="noopener">
            <img src="../assets/img/map-gedung-640x480.jpg" alt="Peta lokasi" style="width:100%; height:auto; max-width:640px; display:block; margin:0 auto; object-fit:cover; aspect-ratio:4/3;" onerror="this.onerror=null;this.src='../assets/img/map-gedung.png';">
          </a>
        </div>
        <p class="map-note mt-2">
          <a href="<?php echo htmlspecialchars($maps); ?>" target="_blank" rel="noopener">
            Buka rute di Google Maps
          </a>
        </p>
      </div>

    </div>
  </div>

</footer>

<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/aos/aos.js"></script>
<script src="../assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="../assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="../assets/vendor/purecounter/purecounter_vanilla.js"></script>
<script src="../assets/js/main.js"></script>

</body>
</html>
