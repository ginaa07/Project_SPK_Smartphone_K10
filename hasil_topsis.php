<?php
// 1. Mulai session dan hubungkan ke database
session_start();
require_once 'config/koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['username'])) {
  header("Location: auth/login.php");
  exit;
}

// Ambil nama lengkap atau username untuk dipasang di sidebar/profile area
$nama_admin = $_SESSION['nama'] ?? $_SESSION['username'];
$current_page = 'hasil_topsis.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hasil Analisis TOPSIS</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.php">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
            <span class="brand-title">Smartphone</span>
            <span class="brand-subtitle">Smartphone Evaluation System</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a class="nav-link" href="index.php">
          <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>
        <a class="nav-link" href="users.php">
          <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
          <span class="nav-text">Users</span>
        </a>
        <a class="nav-link" href="add-user.php">
          <span class="nav-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
          <span class="nav-text">Add User</span>
        </a>
        <div class="nav-item-dropdown">
          <a class="nav-link dropdown-toggle" href="#tablesDropdown" data-bs-toggle="collapse" role="button"
            aria-expanded="true">
            <span class="nav-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
            <span class="nav-text">Tables</span>
          </a>
          <div class="collapse show" id="tablesDropdown">
            <div class="dropdown-sub-menu ps-4">
              <a class="nav-link-sub py-2 d-block text-decoration-none small text-secondary" href="kriteria.php">
                <i class="bi bi-bookmark-star me-2"></i>Data Kriteria
              </a>
              <a class="nav-link-sub py-2 d-block text-decoration-none small text-secondary" href="alternatif.php">
                <i class="bi bi-phone me-2"></i>Data Alternatif
              </a>
            </div>
          </div>
        </div>
        <a class="nav-link" href="penilaian.php">
          <span class="nav-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
          <span class="nav-text">Matriks Penilaian</span>
        </a>
        <a class="nav-link active" href="hasil_topsis.php" aria-current="page">
          <span class="nav-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
          <span class="nav-text">Hasil TOPSIS</span>
        </a>
      </nav>
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle>
            <span></span><span></span><span></span>
          </button>
          <div class="navbar-actions ms-auto">
            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="bi bi-person-circle fs-5 me-2 text-secondary"></i>
                <span class="profile-name d-none d-sm-inline">Smartphone</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                <li><a class="dropdown-item" href="users.php"><i class="bi bi-gear me-2"></i>Manage Users</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item text-danger" href="auth/logout.php"><i
                      class="bi bi-box-arrow-right me-2"></i>Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">

          <div class="page-heading mb-4">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-calculator" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Perhitungan Analisis</p>
                <h1 class="h3 mb-1">Hasil Perhitungan Analisis TOPSIS</h1>
                <p class="text-muted mb-0">Tahapan penentuan nilai solusi ideal, pemisahan jarak Euclidean, serta
                  perangkingan kedekatan relatif.</p>
              </div>
            </div>
          </div>

          <?php
          // 1. AMBIL DATA KRITERIA & ALTERNATIF
          $kriteria = [];
          $q_kriteria = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
          while ($row = mysqli_fetch_assoc($q_kriteria)) {
            $kriteria[$row['id_kriteria']] = [
              'nama_kriteria' => $row['nama_kriteria'],
              'bobot' => $row['bobot'],
              'tipe' => strtolower(trim($row['tipe'])) // benefit / cost
            ];
          }

          $alternatif = [];
          $q_alternatif = mysqli_query($koneksi, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");
          while ($row = mysqli_fetch_assoc($q_alternatif)) {
            $alternatif[$row['id_alternatif']] = $row['nama_alternatif'];
          }

          $matrix_x = [];
          $q_penilaian = mysqli_query($koneksi, "SELECT * FROM penilaian");
          while ($row = mysqli_fetch_assoc($q_penilaian)) {
            $matrix_x[$row['id_alternatif']][$row['id_kriteria']] = $row['nilai'];
          }

          if (empty($matrix_x) || empty($kriteria)) {
            echo "<div class='alert alert-warning shadow-sm'><i class='bi bi-exclamation-triangle me-2'></i>Data penilaian atau kriteria masih kosong! Selesaikan pengisian matriks penilaian terlebih dahulu.</div>";
          } else {

          // 2. HITUNG PEMBAGI NORMALISASI
            $pembagi = [];
            foreach ($kriteria as $id_k => $detail) {
              $total_kuadrat = 0;
              foreach ($alternatif as $id_alt => $nama) {
                $nilai = $matrix_x[$id_alt][$id_k] ?? 0;
                $total_kuadrat += pow($nilai, 2);
              }
              $pembagi[$id_k] = sqrt($total_kuadrat);
            }

            // 3. HITUNG MATRIKS TERNORMALISASI TERBOBOT (Y)
            $y = [];
            foreach ($alternatif as $id_alt => $nama) {
              foreach ($kriteria as $id_k => $detail) {
                $nilai_awal = $matrix_x[$id_alt][$id_k] ?? 0;
                $r = ($pembagi[$id_k] > 0) ? ($nilai_awal / $pembagi[$id_k]) : 0;
                $y[$id_alt][$id_k] = $r * $detail['bobot']; // Tetap dikali bobot asli kriteria
              }
            }

            // 4. HITUNG SOLUSI IDEAL POSITIF (A+) & NEGATIF (A-)
            
            $ideal_positif = [];
            $ideal_negatif = [];

            foreach ($kriteria as $id_k => $detail) {
              $nilai_kolom = [];
              foreach ($alternatif as $id_alt => $nama) {
                if (isset($y[$id_alt][$id_k])) {
                  $nilai_kolom[] = $y[$id_alt][$id_k];
                }
              }

              if (!empty($nilai_kolom)) {
                // Mengecek tipe kriteria (apakah mengandung unsur kata 'benefit' atau 'keuntungan')
                if ($detail['tipe'] == 'benefit' || $detail['tipe'] == 'keuntungan') {
                  $ideal_positif[$id_k] = max($nilai_kolom);
                  $ideal_negatif[$id_k] = min($nilai_kolom);
                } else { // Jika cost / biaya
                  $ideal_positif[$id_k] = min($nilai_kolom);
                  $ideal_negatif[$id_k] = max($nilai_kolom);
                }
              } else {
                $ideal_positif[$id_k] = 0;
                $ideal_negatif[$id_k] = 0;
              }
            }

            
            // 5. HITUNG JARAK EUCLIDEAN (D+ & D-) DAN KEDEKATAN RELATIF (V)
            
            $jarak_dan_kedekatan = [];
            foreach ($alternatif as $id_alt => $nama_alt) {
              $sum_positif = 0;
              $sum_negatif = 0;

              foreach ($kriteria as $id_k => $detail) {
                $y_sekarang = $y[$id_alt][$id_k] ?? 0;

                $sum_positif += pow($y_sekarang - $ideal_positif[$id_k], 2);
                $sum_negatif += pow($y_sekarang - $ideal_negatif[$id_k], 2);
              }

              $d_plus = sqrt($sum_positif);
              $d_min = sqrt($sum_negatif);

              // Rumus Nilai Kedekatan Relatif: V = D- / (D- + D+)
              $v_skor = ($d_min + $d_plus > 0) ? ($d_min / ($d_min + $d_plus)) : 0;

              $jarak_dan_kedekatan[$id_alt] = [
                'nama' => $nama_alt,
                'd_plus' => $d_plus,
                'd_min' => $d_min,
                'v' => $v_skor
              ];
            }

            // Simpan salinan asli data untuk tabel Pengukuran Jarak Solusi agar urutannya tidak teracak sebelum di-ranking
            // Simpan salinan asli data untuk tabel Jarak Solusi agar tetap sesuai urutan database
            $tabel_jarak = $jarak_dan_kedekatan;

            // Buat array bantuan khusus untuk menghitung peringkat berdasarkan nilai 'v'
            $skor_v = [];
            foreach ($jarak_dan_kedekatan as $id_alt => $data) {
              $skor_v[$id_alt] = $data['v'];
            }
            // Urutkan nilai V dari yang terbesar ke terkecil (Descending) tanpa merusak key alternatif
            arsort($skor_v);

            // Buat pemetaan rank dinamis (ID Alternatif => Peringkatnya)
            $rank_mapping = [];
            $rank_counter = 1;
            foreach ($skor_v as $id_alt => $v_value) {
              $rank_mapping[$id_alt] = $rank_counter++;
            }
            ;
            ?>

            <div class="panel shadow-sm mb-4 bg-white rounded-3">
              <div class="panel-header p-3 border-bottom bg-light">
                <div>
                  <h2 class="h5 mb-1 fw-bold text-dark"><i class="bi bi-grid-3x3-gap me-2 text-primary"></i>Tabel Solusi
                    Ideal</h2>
                  <p class="text-muted mb-0 small">Hasil ekstraksi nilai optimal berdasarkan karakteristik masing-masing
                    atribut kriteria.</p>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                  <thead>
                    <tr class="table-light">
                      <th style="width: 200px;" class="ps-3">Solusi Ideal</th>
                      <?php foreach ($kriteria as $id_k => $detail): ?>
                        <th class="text-center">
                          <?php echo strtoupper($detail['nama_kriteria']); ?><br>
                          <small class="text-muted fw-normal">(<?php echo ucfirst($detail['tipe']); ?>)</small>
                        </th>
                      <?php endforeach; ?>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="ps-3">
                        <span class="badge text-bg-success px-3 py-2"><i class="bi bi-plus-circle me-1"></i> Positif
                          </span>
                      </td>
                      <?php foreach ($kriteria as $id_k => $detail): ?>
                        <td class="text-center fw-bold text-success" style="font-size: 1.05rem;">
                          <?php echo number_format($ideal_positif[$id_k], 4); ?>
                        </td>
                      <?php endforeach; ?>
                    </tr>
                    <tr>
                      <td class="ps-3">
                        <span class="badge text-bg-danger px-3 py-2"><i class="bi bi-dash-circle me-1"></i> Negatif
                          </span>
                      </td>
                      <?php foreach ($kriteria as $id_k => $detail): ?>
                        <td class="text-center fw-bold text-danger" style="font-size: 1.05rem;">
                          <?php echo number_format($ideal_negatif[$id_k], 4); ?>
                        </td>
                      <?php endforeach; ?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="panel shadow-sm mb-4 bg-white rounded-3">
              <div class="panel-header p-3 border-bottom bg-light">
                <div>
                  <h2 class="h5 mb-1 fw-bold text-dark"><i
                      class="bi bi-bounding-box-circles me-2 text-warning"></i>Pengukuran Jarak Solusi (Separation
                    Measure)</h2>
                  <p class="text-muted mb-0 small">Besar nilai jarak Euclidean alternatif smartphone terhadap matriks
                    solusi ideal positif dan negatif.</p>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="ps-4">Nama Smartphone</th>
                      <th>Jarak Ideal Positif ($D^+$)</th>
                      <th>Jarak Ideal Negatif ($D^-$)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tabel_jarak as $id_alt => $data) { ?>
                      <tr>
                        <td class="ps-4 fw-semibold text-dark"><i
                            class="bi bi-phone text-muted me-2"></i><?= $data['nama']; ?></td>
                        <td class="text-danger fw-semibold"><?= number_format($data['d_plus'], 4); ?></td>
                        <td class="text-success fw-semibold"><?= number_format($data['d_min'], 4); ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="panel shadow-sm bg-white rounded-3">
              <div class="panel-header p-3 border-bottom bg-light">
                <div>
                  <h2 class="h5 mb-1 fw-bold text-dark"><i class="bi bi-trophy me-2 text-primary"></i>Kedekatan Relatif &
                    Perangkingan Akhir</h2>
                  <p class="text-muted mb-0 small">Nilai preferensi tertinggi mendekati angka 1 menentukan smartphone
                    terbaik rekomendasi sistem.</p>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="ps-4" style="width: 120px;">Ranking</th>
                      <th>Nama Smartphone</th>
                      <th>Nilai Kedekatan ($V$)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($jarak_dan_kedekatan as $id_alt => $data) {
                      $badge_class = ($no == 1) ? 'bg-primary' : (($no == 2) ? 'bg-secondary' : 'bg-light text-dark border');
                      ?>
                      <tr>
                        <td class="ps-4"><span
                            class="badge <?= $badge_class; ?> rounded-pill px-3 py-1 fs-6">#<?= $no++; ?></span></td>
                        <td class="fw-bold text-dark"><?= $data['nama']; ?></td>
                        <td class="text-primary fw-bold fs-5"><?= number_format($data['v'], 4); ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>

          <?php } // End of Else ?>

        </div>
      </main>

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright 2026 Smartphone<br> Developed by <a target="_blank" class="fw-bold text-success"
              href="https://github.com/ginaa07">Chaerul Umam Maulana & Regina Safarina</a></span>
          <span>Kelompok 10</span>
        </div>
      </footer>
    </div>
  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>