<?php
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
  header("Location: auth/login.php");
  exit;
}

$success = "";
$error = "";

if (isset($_POST['add_alternatif'])) {
  // Disamakan menggunakan nama_alternatif sesuai struktur database Anda
  $nama_smartphone = mysqli_real_escape_string($koneksi, $_POST['nama_smartphone']);
  $insert = mysqli_query($koneksi, "INSERT INTO alternatif (nama_alternatif) VALUES ('$nama_smartphone')");
  if ($insert) {
    $success = "Alternatif smartphone baru berhasil ditambahkan.";
  } else {
    $error = "Gagal menambah alternatif ke database.";
  }
}
// PROSES EDIT ALTERNATIF
if (isset($_POST['edit_alternatif'])) {

  $id_alternatif = mysqli_real_escape_string(
    $koneksi,
    $_POST['id_alternatif']
  );

  $nama_alternatif = mysqli_real_escape_string(
    $koneksi,
    $_POST['nama_alternatif']
  );

  $update = mysqli_query(
    $koneksi,
    "UPDATE alternatif
        SET nama_alternatif='$nama_alternatif'
        WHERE id_alternatif='$id_alternatif'"
  );

  if ($update) {
    $success = "Data alternatif berhasil diperbarui.";
  } else {
    $error = "Gagal memperbarui data alternatif.";
  }
}
if (isset($_GET['del_alternatif'])) {
  $id_alternatif = mysqli_real_escape_string($koneksi, $_GET['del_alternatif']);
  $delete = mysqli_query($koneksi, "DELETE FROM alternatif WHERE id_alternatif='$id_alternatif'");
  if ($delete) {
    $success = "Alternatif berhasil dihapus.";
  } else {
    $error = "Gagal menghapus alternatif.";
  }
}

$nama_admin = $_SESSION['nama'] ?? $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="smartphone professional smartphone evaluation dashboard">
  <title>Data Alternatif</title>

  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.php" aria-label="smartphone dashboard">
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
        <a class="nav-link" href="add-user.php">
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
              <a class="nav-link-sub py-2 d-block text-decoration-none small fw-bold text-primary"
                href="alternatif.php">
                <i class="bi bi-bookmark-star-fill me-2"></i>Data Alternatif
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
            <input class="form-control search-input" type="search" placeholder="Search parameters, alternatives..."
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

          <div class="page-heading mb-4">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-phone" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">TOPSIS Parameters</p>
                <h1 class="h3 mb-1">Manajemen Alternatif</h1>
                <p class="text-muted mb-0">Kelola objek smartphone yang akan dinilai dalam Sistem Pendukung Keputusan.
                </p>
              </div>
            </div>
          </div>

          <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3 py-2 px-3 small"
              role="alert">
              <i class="bi bi-check-circle-fill me-2"></i> <?php echo $success; ?>
              <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3 py-2 px-3 small"
              role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $error; ?>
              <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <div class="row g-4">
            <div class="col-12 col-lg-4">
              <div class="card border-0 shadow-sm p-4 h-100 bg-white rounded-3">
                <h3 class="h5 mb-2 fw-bold text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Objek
                </h3>
                <p class="text-muted small mb-4">Masukkan nama smartphone seri terbaru untuk menambah opsi alternatif
                  matriks.</p>

                <form method="POST" action="alternatif.php">
                  <div class="mb-4">
                    <label for="nama_smartphone" class="form-label fw-semibold text-secondary small">Nama Alternatif /
                      Smartphone</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-end-0 text-muted"><i
                          class="bi bi-phone-vibrate"></i></span>
                      <input type="text" id="nama_smartphone" name="nama_smartphone"
                        class="form-control bg-light border-start-0 ps-1" placeholder="Contoh: iPhone 15 Pro Max"
                        required>
                    </div>
                  </div>
                  <button type="submit" name="add_alternatif"
                    class="btn btn-primary w-100 py-2 fw-semibold shadow-sm"><i class="bi bi-plus-lg me-2"></i>Simpan
                    Alternatif</button>
                </form>
              </div>
            </div>

            <div class="col-12 col-lg-8">
              <section class="panel shadow-sm border-0 bg-white rounded-3">
                <div class="panel-header d-flex justify-content-between align-items-center p-3 border-bottom bg-light">
                  <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-list-stars text-primary fs-5"></i>
                    <h2 class="h5 mb-0 fw-bold text-dark">Daftar Smartphone</h2>
                  </div>
                  <input class="form-control form-control-sm w-auto shadow-sm" type="search"
                    placeholder="Cari smartphone..." id="searchAlt" aria-label="Search">
                </div>

                <div class="table-responsive">
                  <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light text-uppercase fs-7 tracking-wider">
                      <tr>
                        <th class="ps-4" style="width: 120px;">ID Alternatif</th>
                        <th>Nama Objek Smartphone</th>
                        <th class="text-end pe-4">Aksi Kontrol</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $qa = mysqli_query($koneksi, "SELECT * FROM alternatif ORDER BY id_alternatif ASC");
                      if (mysqli_num_rows($qa) > 0) {
                        while ($ra = mysqli_fetch_assoc($qa)) {
                          ?>
                          <tr>
                            <td class="ps-4">
                              <span class="badge bg-light text-primary fw-bold px-2.5 py-1.5 border">
                                #A-<?php echo $ra['id_alternatif']; ?>
                              </span>
                            </td>
                            <td>
                              <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary-subtle text-primary rounded px-2 py-1 small fw-bold">
                                  <i class="bi bi-phone small"></i>
                                </div>
                                <span class="fw-semibold text-dark"><?php echo $ra['nama_alternatif']; ?></span>
                              </div>
                            </td>
                            <td class="text-end pe-4">
                              <div class="d-inline-flex gap-1">
                                <button type="button"
                                  class="btn btn-outline-primary btn-sm border-0 shadow-none px-2 py-1 me-1"
                                  data-bs-toggle="modal" data-bs-target="#editModal<?php echo $ra['id_alternatif']; ?>">

                                  <i class="bi bi-pencil-square"></i>

                                </button>
                                </a>
                                <a href="alternatif.php?del_alternatif=<?php echo $ra['id_alternatif']; ?>"
                                  class="btn btn-outline-danger btn-sm border-0 shadow-none px-2 py-1" title="Hapus Objek"
                                  onclick="return confirm('Apakah Anda yakin ingin menghapus alternatif <?php echo $ra['nama_alternatif']; ?>?');">
                                  <i class="bi bi-trash3-fill"></i>
                                </a>
                              </div>
                            </td>
                          </tr>
                          <div class="modal fade" id="editModal<?php echo $ra['id_alternatif']; ?>" tabindex="-1"
                            aria-hidden="true">

                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content border-0 shadow rounded-3">

                                <div class="modal-header bg-light border-bottom">
                                  <h5 class="modal-title fw-bold text-dark">
                                    <i class="bi bi-pencil-square me-2 text-primary"></i>
                                    Edit Alternatif
                                  </h5>

                                  <button type="button" class="btn-close" data-bs-dismiss="modal">
                                  </button>
                                </div>

                                <form method="POST" action="alternatif.php">

                                  <div class="modal-body p-4">

                                    <input type="hidden" name="id_alternatif" value="<?php echo $ra['id_alternatif']; ?>">

                                    <div class="mb-3">
                                      <label class="form-label fw-semibold">
                                        Nama Smartphone
                                      </label>

                                      <input type="text" name="nama_alternatif" class="form-control bg-light"
                                        value="<?php echo $ra['nama_alternatif']; ?>" required>
                                    </div>

                                  </div>

                                  <div class="modal-footer bg-light">

                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                      Batal
                                    </button>

                                    <button type="submit" name="edit_alternatif" class="btn btn-primary">
                                      <i class="bi bi-check-circle me-1"></i>
                                      Simpan Perubahan
                                    </button>

                                  </div>

                                </form>

                              </div>
                            </div>

                          </div>
                        <?php
                        }
                      } else {
                        echo '<tr><td colspan="3" class="text-center py-4 text-muted small"><i class="bi bi-folder-x fs-4 d-block mb-2"></i>Belum ada data alternatif smartphone yang terdaftar.</td></tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </section>
            </div>
          </div>

        </div>
      </main>

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          <span>Copyright 2026 Smartphone. <br> Developed by <a target="_blank" class="fw-bold text-success"
              href="https://github.com/ginaa07">Kelompok 10</a> • Distributed by <a target="_blank"
              class="fw-bold text-success" href="https://themewagon.com">ThemeWagon</a> </span>
          <span>Sistem Pendukung Keputusan | TI SE 2</span>
        </div>
      </footer>
    </div>
  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/js/main.js"></script>

  <script>
    document.getElementById('searchAlt').addEventListener('keyup', function () {
      let value = this.value.toLowerCase();
      let rows = document.querySelectorAll('tbody tr');
      rows.forEach(row => {
        if (row.innerText.toLowerCase().indexOf(value) > -1) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    });
  </script>
</body>

</html>