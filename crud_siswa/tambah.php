<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nisn  = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);

    mysqli_query($koneksi, "INSERT INTO siswa (nama, nisn, kelas) VALUES ('$nama', '$nisn', '$kelas')");
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data</title>
</head>
<body>
    <h2>Tambah Data Siswa</h2>
    <form method="POST" action="">
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br><br>
        <label>NISN:</label><br>
        <input type="text" name="nisn" required><br><br>
        <label>Kelas:</label><br>
        <input type="text" name="kelas" required><br><br>
        <button type="submit" name="simpan">Simpan</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>