<?php
require 'config.php';
if (!isset($_SESSION['login'])) header("Location: login.php");

// Tambah Sepatu
if (isset($_POST['tambah'])) {
    $kode   = mysqli_real_escape_string($koneksi, $_POST['kode_sepatu']);
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_sepatu']);
    $merk   = mysqli_real_escape_string($koneksi, $_POST['merk']);
    $ukuran = $_POST['ukuran'];
    $harga  = $_POST['harga'];
    $stok   = $_POST['stok'];

    mysqli_query($koneksi, "INSERT INTO sepatu VALUES(NULL, '$kode', '$nama', '$merk', '$ukuran', '$harga', '$stok')");
    header("Location: sepatu.php");
}

// Hapus Sepatu
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM sepatu WHERE id = $id");
    header("Location: sepatu.php");
}

// Fitur Pencarian Data
$keyword = $_GET['cari'] ?? '';
$query = "SELECT * FROM sepatu WHERE nama_sepatu LIKE '%$keyword%' OR merk LIKE '%$keyword%' OR kode_sepatu LIKE '%$keyword%' ORDER BY id DESC";
$sepatu = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Sepatu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="flex">
        <div class="w-64 bg-slate-800 min-h-screen text-white p-4 space-y-2">
            <a href="dashboard.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Dashboard</a>
            <a href="sepatu.php" class="block py-2.5 px-4 bg-blue-600 rounded font-semibold">Data Sepatu</a>
            <a href="pelanggan.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Data Pelanggan</a>
            <a href="transaksi.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Transaksi</a>
        </div>

        <div class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6">Kelola Data Sepatu (Utama)</h2>

            <!-- Form Tambah -->
            <form method="POST" class="bg-white p-6 rounded-lg shadow mb-6 grid grid-cols-3 gap-4">
                <input type="text" name="kode_sepatu" placeholder="Kode Sepatu (e.g. SPT-004)" required class="border p-2 rounded">
                <input type="text" name="nama_sepatu" placeholder="Nama Sepatu" required class="border p-2 rounded">
                <input type="text" name="merk" placeholder="Merk (e.g. Nike)" required class="border p-2 rounded">
                <input type="number" name="ukuran" placeholder="Ukuran (e.g. 42)" required class="border p-2 rounded">
                <input type="number" name="harga" placeholder="Harga (Rp)" required class="border p-2 rounded">
                <input type="number" name="stok" placeholder="Stok" required class="border p-2 rounded">
                <button type="submit" name="tambah" class="col-span-3 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">Simpan Data</button>
            </form>

            <!-- Fitur Pencarian (Fitur Tambahan) -->
            <form method="GET" class="mb-4">
                <input type="text" name="cari" value="<?= $keyword ?>" placeholder="Cari nama/merk/kode..." class="border p-2 rounded w-1/3">
                <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded">Cari</button>
            </form>

            <!-- Tabel Data -->
            <table class="w-full bg-white rounded-lg shadow overflow-hidden">
                <thead class="bg-slate-200">
                    <tr class="text-left">
                        <th class="p-3">Kode</th>
                        <th class="p-3">Nama</th>
                        <th class="p-3">Merk</th>
                        <th class="p-3">Ukuran</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($sepatu)): ?>
                    <tr class="border-t">
                        <td class="p-3 font-semibold"><?= $row['kode_sepatu'] ?></td>
                        <td class="p-3"><?= $row['nama_sepatu'] ?></td>
                        <td class="p-3"><?= $row['merk'] ?></td>
                        <td class="p-3"><?= $row['ukuran'] ?></td>
                        <td class="p-3">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td class="p-3"><?= $row['stok'] ?></td>
                        <td class="p-3">
                            <a href="sepatu.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin hapus?')" class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>