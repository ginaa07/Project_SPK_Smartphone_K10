<?php
// 1. Hubungkan ke database dan proteksi session halaman
include 'config/koneksi.php';


if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// 2. Ambil data hitungan dari database secara dinamis
$query_alt  = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM alternatif");
$total_alt  = ($query_alt) ? mysqli_fetch_assoc($query_alt)['total'] : 0;

$query_krit = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kriteria");
$total_krit = ($query_krit) ? mysqli_fetch_assoc($query_krit)['total'] : 0;

$query_pen  = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penilaian");
$total_pen  = ($query_pen) ? mysqli_fetch_assoc($query_pen)['total'] : 0;

$query_user = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users");
$total_user = ($query_user) ? mysqli_fetch_assoc($query_user)['total'] : 0;

// Ambil nama lengkap atau username untuk dipasang di sidebar/profile area
$nama_admin = $_SESSION['nama'] ?? $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="smartphone professional smartphone evaluation dashboard">
  <title>Dashboard</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.php" aria-label="smartphone evaluation dashboard">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
          <span class="brand-copy">
            <span class="brand-title">Smartphone</span>
            <span class="brand-subtitle">Smartphone Evaluation System</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a class="nav-link active" href="index.php" aria-current="page">
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
        <a class="nav-link" href="penilaian.php">
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
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="smartphone" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span><span></span><span></span>
          </button>
          <div class="navbar-actions ms-auto">
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
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Overview</p>
                <h1 class="h3 mb-1">Dashboard SPK TOPSIS</h1>
                <p class="text-muted mb-0">Sistem Pendukung Keputusan Penentuan Smartphone Terbaik Berbasis Metode TOPSIS.</p>
              </div>
            </div>
          </div>

          <section class="row g-3 mt-1" aria-label="Dashboard metrics">
            <div class="col-12 col-sm-6 col-xl-3">
              <article class="metric-card metric-primary">
                <div class="metric-top">
                  <span class="metric-label">Total Kriteria</span>
                  <span class="metric-icon"><i class="bi bi-sliders" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><?php echo $total_krit; ?></div>
                <div class="metric-meta"><span>Parameter Penilaian</span></div>
              </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
              <article class="metric-card metric-success">
                <div class="metric-top">
                  <span class="metric-label">Total Alternatif</span>
                  <span class="metric-icon"><i class="bi bi-phone" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><?php echo $total_alt; ?></div>
                <div class="metric-meta"><span>Smartphone Terdaftar</span></div>
              </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
              <article class="metric-card metric-warning">
                <div class="metric-top">
                  <span class="metric-label">Data Penilaian</span>
                  <span class="metric-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><?php echo $total_pen; ?></div>
                <div class="metric-meta"><span>Nilai Terinput</span></div>
              </article>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
              <article class="metric-card metric-danger">
                <div class="metric-top">
                  <span class="metric-label">Total Users</span>
                  <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value"><?php echo $total_user; ?></div>
                <div class="metric-meta"><span>Administrator Sistem</span></div>
              </article>
            </div>
          </section>

          <section class="row g-3 mt-1">
            <div class="col-12 col-xl-8">
              <div class="panel">
                <div class="panel-header">
                  <div>
                    <h2 class="h5 mb-1 section-title"><i class="bi bi-info-circle" aria-hidden="true"></i><span>Langkah Penggunaan Sistem</span></h2>
                    <p class="text-muted mb-0">Alur perhitungan menggunakan algoritma TOPSIS.</p>
                  </div>
                </div>
                <div class="p-3">
                  <ol class="list-group list-group-numbered list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                      <div class="ms-2 me-auto">
                        <div class="fw-bold">Tentukan Parameter Kriteria</div>
                        Input bobot prioritas dan jenis atribut (Cost atau Benefit) di halaman Tables.
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                      <div class="ms-2 me-auto">
                        <div class="fw-bold">Input Alternatif Smartphone</div>
                        Masukkan nama-nama tipe smartphone yang akan dievaluasi dan dibandingkan.
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                      <div class="ms-2 me-auto">
                        <div class="fw-bold">Isi Matriks Penilaian</div>
                        Berikan nilai kecocokan performa smartphone pada setiap kriteria di menu Penilaian.
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                      <div class="ms-2 me-auto">
                        <div class="fw-bold">Lihat Hasil & Rekomendasi</div>
                        Buka menu Hasil TOPSIS untuk melihat hasil perangkingan otomatis beserta grafik evaluasinya.
                      </div>
                    </li>
                  </ol>
                </div>
              </div>
            </div>

            <div class="col-12 col-xl-4">
              <div class="panel h-100">
                <div class="panel-header">
                  <div>
                    <h2 class="h5 mb-1 section-title"><i class="bi bi-activity" aria-hidden="true"></i><span>Sistem Info</span></h2>
                    <p class="text-muted mb-0">Status pengerjaan project.</p>
                  </div>
                </div>
                <div class="activity-list">
                  <div class="activity-item"><span class="activity-dot bg-primary"></span><div><p class="mb-1 fw-semibold">Database Terkoneksi</p><p class="text-muted small mb-0">Berhasil tersambung ke mysql `spk_smartphone`.</p></div></div>
                  <div class="activity-item"><span class="activity-dot bg-success"></span><div><p class="mb-1 fw-semibold">Algoritma TOPSIS Aktif</p><p class="text-muted small mb-0">Script penghitung matriks ideal positif & negatif siap digunakan.</p></div></div>
                </div>
              </div>
            </div>
          </section>

          <section class="panel mt-3">
            <div class="panel-header">
              <div>
                <h2 class="h5 mb-1 section-title"><i class="bi bi-people" aria-hidden="true"></i><span>Active Administrators</span></h2>
                <p class="text-muted mb-0">Daftar akun pengguna yang mengelola dashboard ini.</p>
              </div>
              <a class="btn btn-outline-secondary btn-sm" href="users.php">Manage Users</a>
            </div>
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead>
                  <tr>
                    <th scope="col">Username</th>
                    <th scope="col">Nama Pengguna</th>
                    <th scope="col">Email</th>
                    <th scope="col" class="text-end">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $q_users = mysqli_query($koneksi, "SELECT * FROM users LIMIT 5");
                  while($r_user = mysqli_fetch_assoc($q_users)):
                  ?>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                          <?php echo strtoupper(substr($r_user['username'], 0, 2)); ?>
                        </div>
                        <div>
                          <p class="fw-semibold mb-0"><?php echo $r_user['username']; ?></p>
                        </div>
                      </div>
                    </td>
                    <td><?php echo $r_user['nama'] ?? 'Administrator'; ?></td>
                    <td><?php echo $r_user['email'] ?? '-'; ?></td>
                    <td class="text-end"><span class="badge text-bg-success">Active</span></td>
                  </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          </section>
        </div>
      </main>

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright 2026 Smartphone<br> Developed by <a target="_blank" class="fw-bold text-success" href="https://github.com/ginaa07">Chaerul Umam Maulana & Regina Safarina</a> • Distributed by <a target="_blank" class="fw-bold text-success" href="https://themewagon.com">TI SE 2</a> </span>
          <span>Kelompok 10</span>
        </div>
      </footer>
    </div>
  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>