<?php
// 1. Hubungkan ke database dan mulai session
include '../config/koneksi.php';

// Jika user sudah dalam keadaan login, langsung alihkan ke dashboard (index.php)
if (isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$error = "";

// 2. Cek apakah tombol Sign In diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userInput = mysqli_real_escape_string($koneksi, $_POST['email_or_username']);
    $password  = $_POST['password'];

$query  = "SELECT * FROM users WHERE username='$userInput'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if ($password === $row['password']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama']     = $row['nama'] ?? 'Admin';
            
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Username atau Email tidak terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Workspace | adminHMD</title>
  <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  
  <style>
    /* Custom Styling untuk Mengganti Layout Asli Template */
    body.custom-auth {
      background-color: #f4f7f6;
      height: 100vh;
      overflow: hidden;
    }
    .split-container {
      height: 100vh;
    }
    /* Sisi Kiri: Visual Brand */
    .auth-sidebar-visual {
      background: linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(30, 41, 59, 0.8)), 
                  url('../assets/images/png/dasher-ui-bootstrap-5.jpg') no-repeat center center;
      background-size: cover;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #ffffff;
      padding: 3rem;
      position: relative;
    }
    .brand-wrapper {
      text-align: center;
      max-width: 400px;
    }
    .brand-large-icon {
      font-size: 3.5rem;
      color: #3b82f6;
      margin-bottom: 1rem;
      display: inline-block;
      animation: pulse 2s infinite;
    }
    /* Sisi Kanan: Form Box */
    .auth-form-container {
      background-color: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 3rem;
      box-shadow: -10px 0 30px rgba(0,0,0,0.03);
    }
    .form-box {
      max-width: 400px;
      width: 100%;
      margin: 0 auto;
    }
    .input-group-custom {
      position: relative;
    }
    .input-group-custom i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      z-index: 10;
    }
    .input-group-custom .form-control {
      padding-left: 45px;
      height: 48px;
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      background-color: #f8fafc;
    }
    .input-group-custom .form-control:focus {
      background-color: #ffffff;
      border-color: #3b82f6;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .btn-login-custom {
      height: 48px;
      font-weight: 600;
      border-radius: 8px;
      background-color: #3b82f6;
      border: none;
      transition: all 0.2s ease;
    }
    .btn-login-custom:hover {
      background-color: #2563eb;
      transform: translateY(-1px);
    }
    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
    /* Mengatasi tampilan HP agar tetap responsif */
    @media (max-width: 767.98px) {
      .auth-sidebar-visual {
        display: none !important;
      }
      .auth-form-container {
        padding: 2rem 1.5rem;
      }
    }
  </style>
</head>
<body class="custom-auth">

  <div class="container-fluid p-0">
    <div class="row g-0 split-container">
      
      <div class="col-md-6 col-lg-7 auth-sidebar-visual d-none d-md-flex">
        <div class="brand-wrapper">
          <div class="brand-large-icon">
            <i class="bi bi-grid-1x2-fill"></i>
          </div>
          <h2 class="fw-bold mb-2">Sistem Pendukung Keputusan</h2>
          <p class="text-white-50 fs-6">Pemilihan Smartphone Terbaik Menggunakan Metode TOPSIS Berbasis Web.</p>
        </div>
      </div>
      
      <div class="col-md-6 col-lg-5 auth-form-container">
        <div class="form-box">
          
          <div class="mb-4">
            <div class="d-flex align-items-center mb-2 d-md-none">
              <span class="text-primary fs-3 me-2"><i class="bi bi-grid-1x2-fill"></i></span>
              <span class="fw-bold h4 m-0">adminHMD</span>
            </div>
            <h1 class="h3 fw-bold text-dark mb-1">Selamat Datang</h1>
            <p class="text-muted small">Silakan masuk menggunakan akun workspace admin Anda.</p>
          </div>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger py-2 px-3 small border-0 d-flex align-items-center" role="alert" style="border-radius: 8px;">
              <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
              <div><?php echo $error; ?></div>
            </div>
          <?php endif; ?>

          <form class="needs-validation" method="POST" action="" novalidate>
            
            <div class="mb-3">
              <label class="form-label small fw-semibold text-secondary" style="letter-spacing: 0.5px;">USERNAME OR EMAIL</label>
              <div class="input-group-custom">
                <i class="bi bi-person"></i>
                <input class="form-control" type="text" name="email_or_username" placeholder="Masukkan nama pengguna..." required autocomplete="off">
                <div class="invalid-feedback">Masukkan email atau username dengan benar.</div>
              </div>
            </div>

            <div class="mb-3">
              <div class="d-flex justify-content-between mb-1">
                <label class="form-label small fw-semibold text-secondary m-0" style="letter-spacing: 0.5px;">PASSWORD</label>
                <a class="small text-decoration-none text-primary" href="forgot-password.html">Lupa?</a>
              </div>
              <div class="input-group-custom">
                <i class="bi bi-lock"></i>
                <input class="form-control" type="password" name="password" placeholder="••••••••" minlength="6" required>
                <div class="invalid-feedback">Kata sandi minimal berisi 6 karakter.</div>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label small text-muted" for="rememberMe">Ingat saya di perangkat ini</label>
              </div>
            </div>

            <button class="btn btn-primary btn-login-custom w-100 text-white d-flex align-items-center justify-content-center gap-2" type="submit">
              <span>Masuk Ke Sistem</span><i class="bi bi-arrow-right-short fs-4"></i>
            </button>
          </form>
          
          <div class="text-center mt-4 text-muted small">
            Belum memiliki akun? <a href="register.php" class="text-primary text-decoration-none fw-semibold">Daftar Sekarang</a>
          </div>

        </div>
      </div>

    </div>
  </div>

  <script src="../assets/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/main.js"></script>
</body>
</html>