<?php
// 1. Hubungkan ke database dan mulai session
include '../config/koneksi.php';

// Jika user sudah login, alihkan langsung ke dashboard
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

$error   = "";
$success = "";

// 2. Proses pendaftaran saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $email    = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $password = $_POST['password'];

    // Validasi apakah username atau email sudah pernah digunakan
    $cek_user = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' OR email='$email'");
    
    if (mysqli_num_rows($cek_user) > 0) {
        $error = "Username atau Email sudah terdaftar! Silakan gunakan yang lain.";
    } else {
        // Simpan data user baru ke database (menggunakan plain text sesuai alur login sebelumnya)
        $query_insert = "INSERT INTO users (username, password, nama, email) VALUES ('$username', '$password', '$nama', '$email')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $success = "Akun berhasil dibuat! Silakan <a href='login.php' class='alert-link'>Sign In</a>.";
        } else {
            $error = "Gagal mendaftarkan akun. Silakan coba lagi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD authentication page">
  <title>Register | adminHMD</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="auth-body">
  <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
  </button>
  <main class="auth-page">
    <section class="auth-card">
      <a class="auth-brand" href="index.php">
        <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
        <span><strong>adminHMD</strong><small>Create your adminHMD account.</small></span>
      </a>
      <div class="auth-visual"><img src="assets/images/png/dasher-ui-bootstrap-5.jpg" alt="adminHMD dashboard interface"></div>
      
      <form class="needs-validation" method="POST" action="" novalidate>
        <div class="mb-4">
          <p class="eyebrow mb-1">Secure Access</p>
          <h1 class="h3 mb-1">Register</h1>
          <p class="text-muted mb-0">Create your adminHMD account.</p>
        </div>

        <?php if (!empty($error)): ?>
          <div class="alert alert-danger alert-dismissible fade show py-2 px-3 small" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="alert alert-success alert-dismissible fade show py-2 px-3 small" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label class="form-label" for="registerName">Full name</label>
          <input class="form-control" id="registerName" type="text" name="nama" required autocomplete="off">
          <div class="invalid-feedback">Full name is required.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="registerUsername">Username</label>
          <input class="form-control" id="registerUsername" type="text" name="username" required autocomplete="off">
          <div class="invalid-feedback">Username is required.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="registerEmail">Email address</label>
          <input class="form-control" id="registerEmail" type="email" name="email" required autocomplete="off">
          <div class="invalid-feedback">Enter a valid email.</div>
        </div>

        <div class="mb-3">
          <label class="form-label" for="registerPassword">Password</label>
          <input class="form-control" id="registerPassword" type="password" name="password" minlength="6" required>
          <div class="invalid-feedback">Password must be at least 6 characters.</div>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="terms" required>
          <label class="form-check-label" for="terms">I agree to the terms</label>
          <div class="invalid-feedback">You must agree before continuing.</div>
        </div>

        <button class="btn btn-primary w-100" type="submit">
          <i class="bi bi-person-plus" aria-hidden="true"></i> Create Account
        </button>
      </form>
      
      <div class="auth-footer">Already have an account? <a href="login.php">Sign in</a></div>
    </section>
  </main>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>