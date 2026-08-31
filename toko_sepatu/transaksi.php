<?php
require 'config.php';
if (!isset($_SESSION['login'])) header("Location: login.php");

// Proses Simpan Transaksi
if (isset($_POST['simpan_transaksi'])) {
    $no_faktur = "INV-" . time();
    $id_pelanggan = $_POST['id_pelanggan'];
    $id_sepatu = $_POST['id_sepatu'];
    $jumlah = $_POST['jumlah'];
    $bayar = $_POST['bayar'];
    $status = $_POST['status_pembayaran'];
    $id_user = $_SESSION['id'];

    // Ambil Harga & Stok Sepatu
    $query_sepatu = mysqli_query($koneksi, "SELECT harga, stok FROM sepatu WHERE id = $id_sepatu");
    $data_sepatu = mysqli_fetch_assoc($query_sepatu);

    if ($data_sepatu['stok'] < $jumlah) {
        echo "<script>alert('Stok tidak mencukupi!');</script>";
    } else {
        $total_harga = $data_sepatu['harga'] * $jumlah;
        $kembalian = $bayar - $total_harga;
        $tgl = date('Y-m-d H:i:s');

        // Insert Transaksi
        mysqli_query($koneksi, "INSERT INTO penjualan VALUES(NULL, '$no_faktur', '$tgl', '$id_pelanggan', '$id_sepatu', '$jumlah', '$total_harga', '$bayar', '$kembalian', '$status', '$id_user')");

        // Perubahan Otomatis Stok
        mysqli_query($koneksi, "UPDATE sepatu SET stok = stok - $jumlah WHERE id = $id_sepatu");

        header("Location: transaksi.php");
    }
}

$sepatu = mysqli_query($koneksi, "SELECT * FROM sepatu WHERE stok > 0");
$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
$transaksi = mysqli_query($koneksi, "SELECT p.*, c.nama as nama_pelanggan, s.nama_sepatu FROM penjualan p JOIN pelanggan c ON p.id_pelanggan = c.id JOIN sepatu s ON p.id_sepatu = s.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Penjualan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="flex">
        <div class="w-64 bg-slate-800 min-h-screen text-white p-4 space-y-2">
            <a href="dashboard.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Dashboard</a>
            <a href="sepatu.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Data Sepatu</a>
            <a href="pelanggan.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Data Pelanggan</a>
            <a href="transaksi.php" class="block py-2.5 px-4 bg-blue-600 rounded font-semibold">Transaksi</a>
        </div>

        <div class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Transaksi Penjualan</h2>
                <a href="cetak_laporan.php" target="_blank" class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700 font-semibold">Cetak Laporan (PDF)</a>
            </div>

            <!-- Form Transaksi Baru -->
            <form method="POST" class="bg-white p-6 rounded-lg shadow mb-8 grid grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 text-sm">Pilih Pelanggan</label>
                    <select name="id_pelanggan" required class="w-full border p-2 rounded">
                        <?php while ($p = mysqli_fetch_assoc($pelanggan)): ?>
                            <option value="<?= $p['id'] ?>"><?= $p['nama'] ?> (<?= $p['kode_pelanggan'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm">Pilih Sepatu</label>
                    <select name="id_sepatu" required class="w-full border p-2 rounded">
                        <?php while ($s = mysqli_fetch_assoc($sepatu)): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nama_sepatu'] ?> - Rp <?= number_format($s['harga']) ?> (Stok: <?= $s['stok'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label class="block mb-1 text-sm">Jumlah Beli</label>
                    <input type="number" name="jumlah" min="1" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block mb-1 text-sm">Uang Bayar (Rp)</label>
                    <input type="number" name="bayar" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block mb-1 text-sm">Status Pembayaran</label>
                    <select name="status_pembayaran" class="w-full border p-2 rounded">
                        <option value="Lunas">Lunas</option>
                        <option value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <button type="submit" name="simpan_transaksi" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">Proses Transaksi</button>
                </div>
            </form>

            <!-- Tabel Riwayat Transaksi -->
            <h3 class="text-lg font-bold mb-3">Riwayat Transaksi</h3>
            <table class="w-full bg-white rounded-lg shadow overflow-hidden">
                <thead class="bg-slate-200">
                    <tr class="text-left">
                        <th class="p-3">No Faktur</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Sepatu</th>
                        <th class="p-3">Qty</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Kembali</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($transaksi)): ?>
                    <tr class="border-t">
                        <td class="p-3 font-semibold"><?= $row['no_faktur'] ?></td>
                        <td class="p-3"><?= $row['nama_pelanggan'] ?></td>
                        <td class="p-3"><?= $row['nama_sepatu'] ?></td>
                        <td class="p-3"><?= $row['jumlah'] ?></td>
                        <td class="p-3">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td class="p-3">Rp <?= number_format($row['kembalian'], 0, ',', '.') ?></td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs text-white <?= $row['status_pembayaran'] === 'Lunas' ? 'bg-green-500' : 'bg-yellow-500' ?>">
                                <?= $row['status_pembayaran'] ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>