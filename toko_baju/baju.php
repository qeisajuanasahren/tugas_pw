<?php
require 'config.php';
if (!isset($_SESSION['login'])) header("Location: login.php");

if (isset($_POST['tambah'])) {
    $kode   = mysqli_real_escape_string($koneksi, $_POST['kode_baju']);
    $nama   = mysqli_real_escape_string($koneksi, $_POST['nama_baju']);
    $merk   = mysqli_real_escape_string($koneksi, $_POST['merk']);
    $ukuran = $_POST['ukuran'];
    $harga  = $_POST['harga'];
    $stok   = $_POST['stok'];

    mysqli_query($koneksi, "INSERT INTO baju VALUES(NULL, '$kode', '$nama', '$merk', '$ukuran', '$harga', '$stok')");
    header("Location: baju.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM baju WHERE id = $id");
    header("Location: baju.php");
    exit;
}

$keyword = $_GET['cari'] ?? '';
$query = "SELECT * FROM baju WHERE nama_baju LIKE '%$keyword%' OR merk LIKE '%$keyword%' OR kode_baju LIKE '%$keyword%' ORDER BY id DESC";
$baju = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Baju</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="flex">
        <div class="w-64 bg-slate-800 min-h-screen text-white p-4 space-y-2">
            <a href="dashboard.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Dashboard</a>
            <a href="baju.php" class="block py-2.5 px-4 bg-blue-600 rounded font-semibold">Data Baju</a>
            <a href="pelanggan.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Data Pelanggan</a>
            <a href="transaksi.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Transaksi</a>
        </div>

        <div class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6">Kelola Data Baju (Utama)</h2>

            <!-- Form Tambah Data -->
            <form method="POST" class="bg-white p-6 rounded-lg shadow mb-6 grid grid-cols-3 gap-4">
                <input type="text" name="kode_baju" placeholder="Kode Baju (e.g. BJU-001)" required class="border p-2 rounded">
                <input type="text" name="nama_baju" placeholder="Nama Baju" required class="border p-2 rounded">
                <input type="text" name="merk" placeholder="Merk (e.g. Erigo)" required class="border p-2 rounded">
                <select name="ukuran" required class="border p-2 rounded">
                    <option value="">-- Pilih Ukuran --</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                    <option value="XXL">XXL</option>
                </select>
                <input type="number" name="harga" placeholder="Harga (Rp)" required class="border p-2 rounded">
                <input type="number" name="stok" placeholder="Stok" required class="border p-2 rounded">
                <button type="submit" name="tambah" class="col-span-3 bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">Simpan Data Baju</button>
            </form>

            <!-- Pencarian -->
            <form method="GET" class="mb-4">
                <input type="text" name="cari" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama/merk/kode..." class="border p-2 rounded w-1/3">
                <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded">Cari</button>
            </form>

            <!-- Tabel Data -->
            <table class="w-full bg-white rounded-lg shadow overflow-hidden">
                <thead class="bg-slate-200">
                    <tr class="text-left">
                        <th class="p-3">Kode</th>
                        <th class="p-3">Nama Baju</th>
                        <th class="p-3">Merk</th>
                        <th class="p-3">Ukuran</th>
                        <th class="p-3">Harga</th>
                        <th class="p-3">Stok</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($baju)): ?>
                    <tr class="border-t">
                        <td class="p-3 font-semibold"><?= $row['kode_baju'] ?></td>
                        <td class="p-3"><?= $row['nama_baju'] ?></td>
                        <td class="p-3"><?= $row['merk'] ?></td>
                        <td class="p-3"><?= $row['ukuran'] ?></td>
                        <td class="p-3">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                        <td class="p-3"><?= $row['stok'] ?></td>
                        <td class="p-3">
                            <a href="baju.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>