<?php
// 1. Mulai session dan hubungkan ke database dan proteksi session halaman admin
session_start();
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit;
}

$error = "";
$success = "";

// 2. Proses Insert Data saat form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
  $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
  $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));
  $password = $_POST['password']; 

  // Menggunakan Password Hashing (Bcrypt) agar penyimpanan kata sandi aman di database
  $password_hashed = password_hash($password, PASSWORD_BCRYPT);

  // Validasi duplikasi data di database
  $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' OR email='$email'");

  if (mysqli_num_rows($cek_user) > 0) {
    $error = "Username atau Email sudah terdaftar dalam sistem!";
  } else {
    // Memasukkan data user baru (Menggunakan password yang sudah di-hash)
    $query_insert = "INSERT INTO users (username, password, nama, email) VALUES ('$username', '$password_hashed', '$nama', '$email')";

    if (mysqli_query($koneksi, $query_insert)) {
      $success = "User baru berhasil ditambahkan ke sistem.";
    } else {
      $error = "Gagal menambahkan user. Silakan periksa kembali data Anda atau pastikan kolom 'email' ada di database.";
    }
  }
}

$nama_admin = $_SESSION['nama'] ?? $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Add User | Smartphone Evaluation System</title>

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
            <span class="brand-subtitle">Evaluation System</span>
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
        <a class="nav-link active" href="add-user.php" aria-current="page">
          <span class="nav-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
          <span class="nav-text">Add User</span>
        </a>
        <div class="nav-item-dropdown">
          <a class="nav-link dropdown-toggle" href="#tablesDropdown" data-bs-toggle="collapse" role="button"
            aria-expanded="true" aria-controls="tablesDropdown">
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
        <a class="nav-link" href="hasil_topsis.php">
          <span class="nav-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
          <span class="nav-text">Hasil TOPSIS</span>
        </a>
      </nav>
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar"
            aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>

          <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
            <input class="form-control search-input" type="search" placeholder="Search users, orders, reports"
              aria-label="Search">
          </form>

          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme"
              title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>

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
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">Management</p>
                <h1 class="h3 mb-1">Add User</h1>
                <p class="text-muted mb-0">Create a new user account with system data synchronization.</p>
              </div>
            </div>
            <div class="heading-actions"><a class="btn btn-outline-secondary btn-sm" href="users.php"><i
                  class="bi bi-arrow-left" aria-hidden="true"></i> Back to Users</a></div>
          </div>

          <section class="row g-3">
            <div class="col-12 col-xl-8">
              <form class="panel needs-validation" method="POST" action="" novalidate>
                <div class="panel-header">
                  <div>
                    <h2 class="h5 mb-1 section-title"><i class="bi bi-person-plus" aria-hidden="true"></i><span>User Information</span></h2>
                    <p class="text-muted mb-0">Create a user account with validated fields.</p>
                  </div>
                </div>

                <?php if (!empty($error)): ?>
                  <div class="alert alert-danger py-2 px-3 small mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
                  </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                  <div class="alert alert-success py-2 px-3 small mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
                  </div>
                <?php endif; ?>

                <div class="row g-3">
                  <div class="col-md-12">
                    <label class="form-label" for="fullName">Full Name</label>
                    <input class="form-control" id="fullName" type="text" name="nama" required autocomplete="off">
                    <div class="invalid-feedback">Full name is required.</div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label" for="username">Username</label>
                    <input class="form-control" id="username" type="text" name="username" required autocomplete="off">
                    <div class="invalid-feedback">Username is required.</div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-control" id="email" type="email" name="email" required autocomplete="off">
                    <div class="invalid-feedback">Enter a valid email address.</div>
                  </div>

                  <div class="col-md-12">
                    <label class="form-label" for="password">Password</label>
                    <input class="form-control" id="password" type="password" name="password" minlength="6" required>
                    <div class="invalid-feedback">Password must be at least 6 characters.</div>
                  </div>
                </div>

                <div class="d-flex flex-wrap justify-content-end gap-2 mt-4">
                  <a class="btn btn-outline-secondary" href="users.php">Cancel</a>
                  <button class="btn btn-primary" type="submit">
                    <i class="bi bi-person-check" aria-hidden="true"></i> Create User
                  </button>
                </div>
              </form>
            </div>

            <div class="col-12 col-xl-4">
              <div class="panel h-100">
                <h2 class="h5 mb-3 section-title"><i class="bi bi-list-check" aria-hidden="true"></i><span>Access Checklist</span></h2>
                <div class="activity-list">
                  <div class="activity-item">
                    <span class="activity-dot bg-success"></span>
                    <div>
                      <p class="mb-1 fw-semibold">Account Creation</p>
                      <p class="text-muted small mb-0">User parameters are instantly synced with credentials.</p>
                    </div>
                  </div>
                  <div class="activity-item">
                    <span class="activity-dot bg-primary"></span>
                    <div>
                      <p class="mb-1 fw-semibold">Database Alignment</p>
                      <p class="text-muted small mb-0">Records map directly to the system table schema.</p>
                    </div>
                  </div>
                  <div class="activity-item">
                    <span class="activity-dot bg-warning"></span>
                    <div>
                      <p class="mb-1 fw-semibold">Ready for Login</p>
                      <p class="text-muted small mb-0">Created users can immediately log into the workspace.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main>

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright 2026 Smartphone Evaluation System. <br> Developed by <a target="_blank" class="fw-bold text-success"
              href="https://github.com/ginaa07">Regina Safarina</a> • Distributed by <a target="_blank"
              class="fw-bold text-success" href="https://themewagon.com">ThemeWagon</a> </span>
        </div>
      </footer>
    </div>
  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>

</html>