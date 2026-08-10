<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id = '$id'");
$row  = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nisn  = mysqli_real_escape_string($koneksi, $_POST['nisn']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);

    mysqli_query($koneksi, "UPDATE siswa SET nama='$nama', nisn='$nisn', kelas='$kelas' WHERE id='$id'");
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
</head>
<body>
    <h2>Edit Data Siswa</h2>
    <form method="POST" action="">
        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']); ?>" required><br><br>
        <label>NISN:</label><br>
        <input type="text" name="nisn" value="<?= htmlspecialchars($row['nisn']); ?>" required><br><br>
        <label>Kelas:</label><br>
        <input type="text" name="kelas" value="<?= htmlspecialchars($row['kelas']); ?>" required><br><br>
        <button type="submit" name="update">Update</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>