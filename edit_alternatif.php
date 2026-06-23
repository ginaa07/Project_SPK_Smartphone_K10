<?php
session_start();
include 'config/koneksi.php';

$id = $_GET['id'];

$query = mysqli_query($koneksi,
    "SELECT * FROM alternatif WHERE id_alternatif='$id'"
);

$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $nama_alternatif = mysqli_real_escape_string(
        $koneksi,
        $_POST['nama_alternatif']
    );

    mysqli_query(
        $koneksi,
        "UPDATE alternatif
        SET nama_alternatif='$nama_alternatif'
        WHERE id_alternatif='$id'"
    );

    header("Location: alternatif.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Alternatif</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">

    <div class="card">
        <div class="card-header">
            Edit Alternatif
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">
                    <label>Nama Smartphone</label>

                    <input
                        type="text"
                        name="nama_alternatif"
                        class="form-control"
                        value="<?= $data['nama_alternatif'] ?>"
                        required>
                </div>

                <button
                    type="submit"
                    name="update"
                    class="btn btn-primary">
                    Update
                </button>

                <a href="alternatif.php"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>

</div>

</body>
</html>