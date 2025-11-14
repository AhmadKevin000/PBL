<?php include '../includes/head.php'; ?>

  <?php include '../includes/header.php'; ?>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row align-items-center">
          <div class="col-lg-6">
            <div class="hero-content" data-aos="fade-up" data-aos-delay="200">

              <h1 class="mb-4">
                Lab <br>
                <span class="accent-text">Business Analytics</span>
              </h1>

              <p class="mb-4 mb-md-5">
                Lab ini berletak pada gedung sipil lt. 8
              </p>

              <div class="hero-buttons">
                <a href="#about" class="btn btn-primary me-0 me-sm-2 mx-1">Lets Go</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="hero-image" data-aos="zoom-out" data-aos-delay="300">
              <img src="../assets/img/pinguin-lab.png" alt="Hero Image" class="img-fluid">
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
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
    ?>
    <section id="about" class="about section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="container section-title" data-aos="fade-up">
          <h2>About Us</h2>
        </div>
        <h3 class="about-title"><?php echo $lp ? htmlspecialchars($lp['nama_lab']) : 'LAB BUSSINES ANALYTICS'; ?></h3>
        <p class="about-description"><?php echo $lp && isset($lp['deskripsi']) && $lp['deskripsi'] !== '' ? htmlspecialchars($lp['deskripsi']) : 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.'; ?></p>
        <div class="about-visimisi">
          <div class="visi">
            <span style="font-size: 25px;">Visi</span>
            <p class="p-visi"><?php echo $lp && isset($lp['visi']) && $lp['visi'] !== '' ? nl2br(htmlspecialchars($lp['visi'])) : 'Visi laboratorium.'; ?></p>
          </div>
          <div class="misi">
            <span style="font-size: 25px;">Misi</span>
            <p class="p-misi"><?php echo $lp && isset($lp['misi']) && $lp['misi'] !== '' ? nl2br(htmlspecialchars($lp['misi'])) : 'Misi laboratorium.'; ?></p>
          </div>
        </div>
      </div>

    </section><!-- /About Section -->

    <!-- Activities Section -->
    <?php
      $activities = [];
      if (isset($pdo) && $pdo) {
        try {
          $stAct = $pdo->query("SELECT judul, deskripsi, foto FROM kegiatan_lab ORDER BY tanggal DESC NULLS LAST, id_kegiatan ASC");
          $activities = $stAct->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
          $activities = [];
        }
      }
    ?>
    <section id="activities" class="about section contact light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="container section-title" data-aos="fade-up">
          <h2>Activities</h2>
        </div>
        <h3 class="about-title">Kegiatan Laboratorium</h3>
        <p class="about-description">Ringkasan kegiatan yang dilakukan di Laboratorium Business Analytics, seperti praktikum, penelitian, workshop, dan kolaborasi industri.</p>

        <div class="about-list">
          <?php if ($activities): ?>
            <?php foreach ($activities as $act): ?>
              <h3 class="about-title"><?php echo htmlspecialchars($act['judul']); ?></h3>
              <div class="row align-items-start g-3 mb-3">
                <?php if (!empty($act['foto'])): ?>
                  <div class="col-4 col-md-3">
                    <img src="../<?php echo htmlspecialchars($act['foto']); ?>" alt="<?php echo htmlspecialchars($act['judul']); ?>" style="max-width: 120px; height: auto; border-radius: 4px; object-fit: cover;">
                  </div>
                  <div class="col-8 col-md-9">
                    <p class="about-description mb-0"><?php echo nl2br(htmlspecialchars($act['deskripsi'])); ?></p>
                  </div>
                <?php else: ?>
                  <div class="col-12">
                    <p class="about-description mb-0"><?php echo nl2br(htmlspecialchars($act['deskripsi'])); ?></p>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <h3 class="about-title">Praktikum</h3>
            <p class="about-description">Kegiatan praktikum rutin yang difokuskan untuk meningkatkan keterampilan analisis data mahasiswa.</p>

            <h3 class="about-title">Riset &amp; Workshop</h3>
            <p class="about-description">Riset terapan, workshop, dan project kolaborasi bersama mitra untuk pemecahan masalah berbasis data.</p>
          <?php endif; ?>
        </div>

      </div>

    </section><!-- /Activities Section -->

    <!-- Gallery Section -->
    <?php
      $galleryItems = [];
      if (isset($pdo) && $pdo) {
        try {
          $stGal = $pdo->query('SELECT image_path, caption FROM gallery WHERE is_active = true ORDER BY sort_order ASC, id_gallery ASC');
          $galleryItems = $stGal->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
          $galleryItems = [];
        }
      }
    ?>
    <section id="gallery" class="gallery section">

      <div class="container section-title" data-aos="fade-up">
        <h2>Gallery</h2>
        <p>Dokumentasi kegiatan laboratorium.</p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row g-5">
          <?php if ($galleryItems): ?>
            <?php foreach ($galleryItems as $item): ?>
              <div class="col-md-6">
                <div class="gallery-card">
                  <div class="thumb">
                    <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="Gallery item">
                  </div>
                  <div class="desc"><?php echo htmlspecialchars($item['caption']); ?></div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12 text-center">
              <p>Belum ada gambar gallery yang aktif.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </section><!-- /Gallery Section -->

    <!-- Profile Section -->
    <section id="profile" class="profile section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Profile</h2>
      </div>
      <div class="row-profile">
        <div class="profile-dosen">
          <a href=""><img src="../assets/img/avatar-1.webp" alt="Dosen" class="dosen-img"></a>
          <h3 class="nama">Khoirul Umam Novalidi.S.kom, M.kom</h3>
        </div>
        <div class="profile-dosen">
          <a href=""><img src="../assets/img/avatar-1.webp" alt="Dosen" class="dosen-img"></a>
          <h3 class="nama">Khoirul Umam Novalidi.S.kom, M.kom</h3>
        </div>
        <div class="profile-dosen">
          <a href=""><img src="../assets/img/avatar-1.webp" alt="Dosen" class="dosen-img"></a>
          <h3 class="nama">Khoirul Umam Novalidi.S.kom, M.kom</h3>
        </div>
      </div>
    </section>

    <!-- Form Peminjaman Section -->
    <section id="form" class="contact section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Form Peminjaman Lab</h2>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-4 g-lg-5">
          <div class="col-lg-12">
            <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
              <form action="../process/peminjaman.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                <div class="row gy-4">

                  <div class="col-md-6">
                    <input type="text" name="nama_peminjam" class="form-control" placeholder="Nama Lengkap" required>
                  </div>

                  <div class="col-md-6">
                    <input type="text" name="nim_nip" class="form-control" placeholder="NIM / NIP" required>
                  </div>

                  <div class="col-md-6">
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                  </div>

                  <div class="col-md-6">
                    <input type="text" name="mata_kuliah" class="form-control" placeholder="Mata Kuliah / Kegiatan" required>
                  </div>

                  <div class="col-md-6">
                    <input type="date" name="tanggal" class="form-control" placeholder="Tanggal" required>
                  </div>

                  <div class="col-md-6">
                    <div class="row align-items-center g-2">
                      <div class="col-5">
                        <input type="time" name="waktu_mulai" class="form-control" placeholder="Waktu Mulai" required>
                      </div>
                      <div class="col-2 text-center">-</div>
                      <div class="col-5">
                        <input type="time" name="waktu_selesai" class="form-control" placeholder="Waktu Selesai" required>
                      </div>
                    </div>
                  </div>

                  <div class="col-12">
                    <textarea name="keperluan" class="form-control" rows="6" placeholder="Keperluan / Deskripsi singkat" required></textarea>
                  </div>

                  <div class="col-12 text-center">
                    <div class="loading">Loading</div>
                    <div class="error-message"></div>
                    <div class="sent-message">Permohonan peminjaman telah dikirim. Terima kasih!</div>

                    <button type="submit" class="btn">Kirim Permohonan</button>
                  </div>

                </div>
              </form>

            </div>
          </div>
        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <?php include '../includes/footer.php'; ?>