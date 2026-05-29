<?php
// 1. Hubungkan ke database dan proteksi session halaman
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$success = "";
$error   = "";

// Proses Simpan / Update Nilai Matriks jika form dikirimkan
if (isset($_POST['simpan_penilaian'])) {
    $id_alternatif = $_POST['id_alternatif'];
    $nilai_kriteria = $_POST['nilai']; // Berupa array [id_kriteria => nilai]

    $is_success = true;
    foreach ($nilai_kriteria as $id_kriteria => $nilai) {
        $id_kriteria = mysqli_real_escape_string($koneksi, $id_kriteria);
        $nilai       = mysqli_real_escape_string($koneksi, $nilai);
        
        // Cek apakah data penilaian untuk alternatif & kriteria ini sudah ada
        $cek = mysqli_query($koneksi, "SELECT * FROM penilaian WHERE id_alternatif='$id_alternatif' AND id_kriteria='$id_kriteria'");
        
        if (mysqli_num_rows($cek) > 0) {
            // Jika sudah ada, lakukan update nilai
            $query = "UPDATE penilaian SET nilai='$nilai' WHERE id_alternatif='$id_alternatif' AND id_kriteria='$id_kriteria'";
        } else {
            // Jika belum ada, masukkan data penilaian baru
            $query = "INSERT INTO penilaian (id_alternatif, id_kriteria, nilai) VALUES ('$id_alternatif', '$id_kriteria', '$nilai')";
        }
        
        if (!mysqli_query($koneksi, $query)) {
            $is_success = false;
        }
    }

    if ($is_success) {
        $success = "Matriks penilaian berhasil diperbarui.";
    } else {
        $error = "Terjadi kesalahan saat menyimpan matriks penilaian.";
    }
}

// Ambil nama lengkap atau username untuk dipasang di sidebar/profile area
$nama_admin = $_SESSION['nama'] ?? $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Matriks Penilaian</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.php" aria-label="adminHMD dashboard">
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
          <a class="nav-link dropdown-toggle" href="#tablesDropdown" data-bs-toggle="collapse" role="button" aria-expanded="true" aria-controls="tablesDropdown">
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
        
        <a class="nav-link active" href="penilaian.php" aria-current="page">
          <span class="nav-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
          <span class="nav-text">Matriks Penilaian</span>
        </a>
        <a class="nav-link" href="charts.php">
          <span class="nav-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
          <span class="nav-text">Hasil TOPSIS</span>
        </a>
      </nav>

      <div class="sidebar-user">
        <img class="avatar-img avatar-md sidebar-user-avatar" src="assets/images/avatar/avatar.jpg" alt="<?php echo $nama_admin; ?>">
        <strong><?php echo $nama_admin; ?></strong>
        <small>Active Workspace</small>
      </div>

      <div class="sidebar-footer">
        <span class="status-dot"></span>
        <span class="sidebar-footer-text">System running smoothly</span>
      </div>
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>

          <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
            <input class="form-control search-input" type="search" placeholder="Search parameters, smartsphones, reports" aria-label="Search">
          </form>

          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>
            
            <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="avatar-img avatar-sm" src="assets/images/avatar/avatar.jpg" alt="<?php echo $nama_admin; ?>">
                <span class="profile-name d-none d-sm-inline"><?php echo $nama_admin; ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="users.php">Manage Users</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>

      <main class="dashboard-content">
        <div class="container-fluid px-3 px-lg-4 py-4">
          
          <div class="page-heading mb-4">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Evaluation Matrix</p>
                <h1 class="h3 mb-1">Matriks Penilaian</h1>
                <p class="text-muted mb-0">Isi nilai kecocokan objek alternatif smartphone pada masing-masing kriteria parameter TOPSIS.</p>
              </div>
            </div>
          </div>

          <?php if(!empty($success)): ?>
            <div class="alert alert-success border-0 shadow-sm small py-2 px-3 mb-3"><?php echo $success; ?></div>
          <?php endif; ?>
          <?php if(!empty($error)): ?>
            <div class="alert alert-danger border-0 shadow-sm small py-2 px-3 mb-3"><?php echo $error; ?></div>
          <?php endif; ?>

          <div class="card border-0 shadow-sm bg-white rounded-3 mb-4">
            <div class="card-body p-4">
              <h3 class="h5 mb-3 fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Input / Sesuaikan Nilai</h3>
              <form method="POST" action="penilaian.php">
                <div class="row g-3 align-items-end">
                  <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Pilih Smartphone</label>
                    <select name="id_alternatif" class="form-select bg-light" required>
                      <option value="">-- Pilih Alternatif --</option>
                      <?php 
                      $opt_alt = mysqli_query($koneksi, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");
                      while($row_alt = mysqli_fetch_assoc($opt_alt)) {
                          echo "<option value='".$row_alt['id_alternatif']."'>".$row_alt['nama_alternatif']."</option>";
                      }
                      ?>
                    </select>
                  </div>
                  
                  <?php 
                  $opt_krit = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                  while($row_kr = mysqli_fetch_assoc($opt_krit)) {
                  ?>
                    <div class="col-6 col-md-2">
                      <label class="form-label small fw-semibold text-secondary"><?php echo $row_kr['nama_kriteria']; ?> (<?php echo $row_kr['id_kriteria']; ?>)</label>
                      <input type="number" step="any" name="nilai[<?php echo $row_kr['id_kriteria']; ?>]" class="form-control bg-light" placeholder="Nilai" required>
                    </div>
                  <?php } ?>

                  <div class="col-12 col-md-1">
                    <button type="submit" name="simpan_penilaian" class="btn btn-primary w-100 fw-semibold"><i class="bi bi-save"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <section class="panel shadow-sm border-0 bg-white rounded-3">
            <div class="panel-header p-3 border-bottom bg-light">
              <div class="d-flex align-items-center gap-2">
                <i class="bi bi-grid-3x3-gap text-primary fs-5"></i>
                <h2 class="h5 mb-0 fw-bold text-dark">Matriks Keputusan (X)</h2>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table align-middle mb-0 table-hover">
                <thead class="table-light text-uppercase fs-7 tracking-wider">
                  <tr>
                    <th class="ps-4">Nama Smartphone</th>
                    <?php
                    // Render judul kriteria secara dinamis dari database, memanggil field id_kriteria untuk menggantikan kode_kriteria
                    $qk = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                    while($rk = mysqli_fetch_assoc($qk)) {
                        echo "<th>" . $rk['nama_kriteria'] . " (" . $rk['id_kriteria'] . ")</th>";
                    }
                    ?>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $qa = mysqli_query($koneksi, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");
                  if (mysqli_num_rows($qa) > 0) {
                      while($ra = mysqli_fetch_assoc($qa)) {
                          $id_alt = $ra['id_alternatif'];
                  ?>
                      <tr>
                        <td class="ps-4 fw-semibold text-dark">
                          <i class="bi bi-phone text-muted me-2"></i><?php echo $ra['nama_alternatif']; ?>
                        </td>
                        <?php
                        // Tarik data nilai berdasarkan relasi kriteria saat ini
                        $qk_inner = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                        while($rk_inner = mysqli_fetch_assoc($qk_inner)) {
                            $id_kr = $rk_inner['id_kriteria'];
                            
                            $qp = mysqli_query($koneksi, "SELECT nilai FROM penilaian WHERE id_alternatif='$id_alt' AND id_kriteria='$id_kr'");
                            $rp = mysqli_fetch_assoc($qp);
                            $nilai_tampil = ($rp) ? $rp['nilai'] : '<span class="text-danger small italic">Belum diisi</span>';
                            
                            echo "<td>" . $nilai_tampil . "</td>";
                        }
                        ?>
                      </tr>
                  <?php 
                      }
                  } else {
                      $total_kolom = mysqli_num_rows($qk) + 1;
                      echo "<tr><td colspan='".$total_kolom."' class='text-center py-4 text-muted small'><i class='bi bi-folder-x fs-4 d-block mb-2'></i>Belum ada data matriks alternatif smartphone.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </section>

        </div>
      </main>

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright 2026 Smartphone<br> Developed by <a target="_blank" class="fw-bold text-success" href="https://github.com/ginaa07">TI SE 2</a> • Distributed by <a target="_blank" class="fw-bold text-success" href="https://themewagon.com">Kelompok 10</a> </span>
          <span>sistem pendukung keputusan | TOPSIS</span>
        </div>
      </footer>
    </div>
  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>