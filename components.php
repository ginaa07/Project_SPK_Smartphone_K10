<?php
include 'config/koneksi.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$nama_admin = $_SESSION['nama'] ?? $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Components | adminHMD</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar">
      <div class="sidebar-header">
        <a class="brand-mark" href="index.php">
          <span class="brand-icon"><i class="bi bi-grid-1x2-fill"></i></span>
          <span class="brand-copy">
            <span class="brand-title">adminHMD</span>
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a class="nav-link" href="index.php"><span class="nav-icon"><i class="bi bi-speedometer2"></i></span><span class="nav-text">Dashboard</span></a>
        <a class="nav-link" href="users.php"><span class="nav-icon"><i class="bi bi-people"></i></span><span class="nav-text">Users</span></a>
        <a class="nav-link" href="add-user.php"><span class="nav-icon"><i class="bi bi-person-plus"></i></span><span class="nav-text">Add User</span></a>
        <a class="nav-link" href="tables.php"><span class="nav-icon"><i class="bi bi-table"></i></span><span class="nav-text">Kriteria & Alternatif</span></a>
        <a class="nav-link" href="penilaian.php"><span class="nav-icon"><i class="bi bi-ui-checks-grid"></i></span><span class="nav-text">Penilaian (Matriks)</span></a>
        <a class="nav-link" href="charts.php"><span class="nav-icon"><i class="bi bi-bar-chart-line"></i></span><span class="nav-text">Hasil TOPSIS</span></a>
        <a class="nav-link active" href="components.php"><span class="nav-icon"><i class="bi bi-grid-3x3-gap"></i></span><span class="nav-text">UI Kits</span></a>
      </nav>
      
      ```

### 2. Gunakan Isinya Saat Membangun `charts.php` atau `tables.php`
Ketika nanti kamu menyusun visualisasi perangkingan rekomendasi smartphone terbaik di halaman **`charts.php`** (Hasil TOPSIS), kamu bisa langsung membuka halaman `components.php` ini di browser untuk menyalin baris kode komponennya.

* **Contoh Kasus:** Jika ingin menampilkan persentase nilai preferensi Kedekatan Relatif ($C_i$) dalam bentuk grafis, kamu tinggal menyalin struktur blok kelas `<div class="progress">` dari file ini, lalu memasukkan variabel nilai perhitungan TOPSIS PHP-mu ke dalam atribut `style="width: <?php echo $nilai_ci; ?>%;"`.

Apakah kamu ingin kita menyimpan komponen UI ini terlebih dahulu dan langsung fokus beralih membedah file utama manajemen data smartphone di **`tables.html`**?