<?php
include 'config/koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$success = "";
$error   = "";

// 1. Proses INSERT data kriteria
if (isset($_POST['add_kriteria'])) {
    $id_kriteria   = mysqli_real_escape_string($koneksi, $_POST['id_kriteria']); 
    $nama_kriteria = mysqli_real_escape_string($koneksi, $_POST['nama_kriteria']);
    $bobot         = mysqli_real_escape_string($koneksi, $_POST['bobot']);
    $tipe          = mysqli_real_escape_string($koneksi, $_POST['tipe']);

    $insert = mysqli_query($koneksi, "INSERT INTO kriteria (id_kriteria, nama_kriteria, bobot, tipe) VALUES ('$id_kriteria', '$nama_kriteria', '$bobot', '$tipe')");
    if ($insert) { 
        $success = "Kriteria parameter baru berhasil ditambahkan."; 
    } else { 
        $error = "Gagal menambah kriteria. Pastikan ID kriteria belum digunakan."; 
    }
}

// 2. PROSES UPDATE/EDIT DATA KRITERIA (FITUR BARU)
if (isset($_POST['edit_kriteria'])) {
    $id_kriteria   = mysqli_real_escape_string($koneksi, $_POST['id_kriteria']);
    $nama_kriteria = mysqli_real_escape_string($koneksi, $_POST['nama_kriteria']);
    $bobot         = mysqli_real_escape_string($koneksi, $_POST['bobot']);
    $tipe          = mysqli_real_escape_string($koneksi, $_POST['tipe']);

    $update = mysqli_query($koneksi, "UPDATE kriteria SET nama_kriteria='$nama_kriteria', bobot='$bobot', tipe='$tipe' WHERE id_kriteria='$id_kriteria'");
    if ($update) {
        $success = "Data kriteria $id_kriteria berhasil diperbarui.";
    } else {
        $error = "Gagal memperbarui data kriteria.";
    }
}

// 3. Alur DELETE data kriteria
if (isset($_GET['del_kriteria'])) {
    $id_kriteria = mysqli_real_escape_string($koneksi, $_GET['del_kriteria']);
    $delete = mysqli_query($koneksi, "DELETE FROM kriteria WHERE id_kriteria='$id_kriteria'");
    if ($delete) { 
        $success = "Kriteria berhasil dihapus dari sistem."; 
    } else { 
        $error = "Gagal menghapus kriteria parameter."; 
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
  <title>Data Kriteria</title>
  
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
              <a class="nav-link-sub py-2 d-block text-decoration-none small fw-bold text-primary" href="kriteria.php">
                <i class="bi bi-bookmark-star-fill me-2"></i>Data Kriteria
              </a>
              <a class="nav-link-sub py-2 d-block text-decoration-none small text-secondary" href="alternatif.php">
                <i class="bi bi-phone me-2"></i>Data Alternatif
              </a>
            </div>
          </div>
        </div>

        <a class="nav-link" href="penilaian.php">
          <span class="nav-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
          <span class="nav-text">Penilaian (Matriks)</span>
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
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
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
          
          <div class="page-heading mb-4">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-bookmark-star" aria-hidden="true"></i></span>
              <div>
                <p class="eyebrow mb-1">TOPSIS Core Parameters</p>
                <h1 class="h3 mb-1">Manajemen Kriteria</h1>
                <p class="text-muted mb-0">Atur bobot prioritas kepentingan serta tipe preferensi kriteria penilaian smartphone.</p>
              </div>
            </div>
          </div>

          <?php if(!empty($success)): ?>
            <div class="alert alert-success border-0 shadow-sm small py-2 px-3 mb-3"><?php echo $success; ?></div>
          <?php endif; ?>
          <?php if(!empty($error)): ?>
            <div class="alert alert-danger border-0 shadow-sm small py-2 px-3 mb-3"><?php echo $error; ?></div>
          <?php endif; ?>

          <div class="row g-4">
            <div class="col-12 col-lg-4">
              <div class="card border-0 shadow-sm p-4 bg-white rounded-3 h-100">
                <h3 class="h5 mb-2 fw-bold text-dark"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Kriteria</h3>
                <p class="text-muted small mb-4">Tambahkan indikator penilaian baru beserta parameter perhitungan pembobotan.</p>
                
                <form method="POST" action="kriteria.php">
                  <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Kode Kriteria</label>
                    <input type="text" name="id_kriteria" class="form-control bg-light" placeholder="Contoh: C1" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama Parameter</label>
                    <input type="text" name="nama_kriteria" class="form-control bg-light" placeholder="Contoh: Kapasitas RAM" required>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nilai Bobot (W)</label>
                    <input type="number" step="0.01" name="bobot" class="form-control bg-light" placeholder="Contoh: 0.25 atau 5" required>
                  </div>
                  <div class="mb-4">
                    <label class="form-label small fw-semibold text-secondary">Jenis Tipe</label>
                    <select name="tipe" class="form-select bg-light" required>
                      <option value="benefit">Benefit (Semakin besar semakin baik)</option>
                      <option value="cost">Cost (Semakin kecil semakin baik)</option>
                    </select>
                  </div>
                  <button type="submit" name="add_kriteria" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm"><i class="bi bi-save me-2"></i>Simpan Kriteria</button>
                </form>
              </div>
            </div>

            <div class="col-12 col-lg-8">
              <section class="panel shadow-sm border-0 bg-white rounded-3">
                <div class="panel-header p-3 border-bottom bg-light">
                  <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-sliders text-primary fs-5"></i>
                    <h2 class="h5 mb-0 fw-bold text-dark">Matriks Parameter Kriteria</h2>
                  </div>
                </div>

                <div class="table-responsive">
                  <table class="table align-middle mb-0 table-hover">
                    <thead class="table-light text-uppercase fs-7 tracking-wider">
                      <tr>
                        <th class="ps-4" style="width: 100px;">Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Bobot (W)</th>
                        <th>Jenis Tipe</th>
                        <th class="text-end pe-4">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $qk = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria ASC");
                      if (mysqli_num_rows($qk) > 0) {
                          while($rk = mysqli_fetch_assoc($qk)) {
                          ?>
                          <tr>
                            <td class="ps-4">
                              <span class="badge bg-primary-subtle text-primary fw-bold px-2.5 py-1.5 border border-primary-subtle">
                                <?php echo $rk['id_kriteria']; ?>
                              </span>
                            </td>
                            <td class="fw-semibold text-dark"><?php echo $rk['nama_kriteria']; ?></td>
                            <td class="text-secondary fw-bold"><?php echo $rk['bobot']; ?></td>
                            <td>
                              <?php if($rk['tipe'] == 'benefit'): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-graph-up-arrow me-1"></i>Benefit</span>
                              <?php else: ?>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="bi bi-graph-down-arrow me-1"></i>Cost</span>
                              <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                              <button type="button" class="btn btn-outline-primary btn-sm border-0 shadow-none px-2 py-1 me-1" 
                                      data-bs-toggle="modal" 
                                      data-bs-target="#editModal<?php echo $rk['id_kriteria']; ?>">
                                <i class="bi bi-pencil-square"></i>
                              </button>
                              
                              <a href="kriteria.php?del_kriteria=<?php echo $rk['id_kriteria']; ?>" class="btn btn-outline-danger btn-sm border-0 shadow-none px-2 py-1" onclick="return confirm('Hapus parameter kriteria <?php echo $rk['nama_kriteria']; ?>?')">
                                <i class="bi bi-trash3-fill"></i>
                              </a>
                            </td>
                          </tr>

                          <div class="modal fade" id="editModal<?php echo $rk['id_kriteria']; ?>"  tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content border-0 shadow rounded-3">
                                <div class="modal-header bg-light border-bottom">
                                  <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Kriteria <?php echo $rk['id_kriteria']; ?></h5>
                                  <button type="button" class="btn-close shadow-none" data-bs-shadow="none" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="kriteria.php">
                                  <div class="modal-body p-4">
                                    <input type="hidden" name="id_kriteria" value="<?php echo $rk['id_kriteria']; ?>">
                                    
                                    <div class="mb-3">
                                      <label class="form-label small fw-semibold text-secondary">Nama Parameter</label>
                                      <input type="text" name="nama_kriteria" class="form-control bg-light" value="<?php echo $rk['nama_kriteria']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                      <label class="form-label small fw-semibold text-secondary">Nilai Bobot (W)</label>
                                      <input type="number" step="0.01" name="bobot" class="form-control bg-light" value="<?php echo $rk['bobot']; ?>" required>
                                    </div>
                                    <div class="mb-2">
                                      <label class="form-label small fw-semibold text-secondary">Jenis Tipe</label>
                                      <select name="tipe" class="form-select bg-light" required>
                                        <option value="benefit" <?php echo ($rk['tipe'] == 'benefit') ? 'selected' : ''; ?>>Benefit (Semakin besar semakin baik)</option>
                                        <option value="cost" <?php echo ($rk['tipe'] == 'cost') ? 'selected' : ''; ?>>Cost (Semakin kecil semakin baik)</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="modal-footer bg-light border-top p-3">
                                    <button type="button" class="btn btn-secondary border-0 btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" name="edit_kriteria" class="btn btn-primary btn-sm px-3 fw-semibold"><i class="bi bi-check-circle me-1"></i>Simpan Perubahan</button>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <?php 
                          }
                      } else {
                          echo '<tr><td colspan="5" class="text-center py-4 text-muted small"><i class="bi bi-folder-x fs-4 d-block mb-2"></i>Belum ada data kriteria yang terdaftar.</td></tr>';
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
    </div>
  </div>

  <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>