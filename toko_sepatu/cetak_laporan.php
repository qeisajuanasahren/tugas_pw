<?php
require 'config.php';
if (!isset($_SESSION['login'])) header("Location: login.php");

$transaksi = mysqli_query($koneksi, "SELECT p.*, c.nama as nama_pelanggan, s.nama_sepatu FROM penjualan p JOIN pelanggan c ON p.id_pelanggan = c.id JOIN sepatu s ON p.id_sepatu = s.id ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Sepatu</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #333; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body onload="window.print()">
    <h1>LAPORAN TRANSAKSI PENJUALAN SEPATU</h1>
    <p>Tanggal Cetak: <?= date('d-m-Y H:i') ?></p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No Faktur</th>
                <th>Pelanggan</th>
                <th>Sepatu</th>
                <th>Qty</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($transaksi)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['no_faktur'] ?></td>
                <td><?= $row['nama_pelanggan'] ?></td>
                <td><?= $row['nama_sepatu'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td><?= $row['status_pembayaran'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>