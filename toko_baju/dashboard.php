<?php
require 'config.php';
if (!isset($_SESSION['login'])) header("Location: login.php");

$total_baju = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM baju"))['total'];
$total_pelanggan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM pelanggan"))['total'];
$total_transaksi = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penjualan"))['total'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_harga) as total FROM penjualan WHERE status_pembayaran = 'Lunas'"))['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Toko Baju</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <nav class="bg-slate-800 text-white px-6 py-4 flex justify-between items-center shadow">
        <h1 class="font-bold text-xl">TOKO BAJU PRO</h1>
        <div class="flex items-center gap-4">
            <span>Halo, <b><?= $_SESSION['nama'] ?></b> (<?= strtoupper($_SESSION['role']) ?>)</span>
            <a href="logout.php" class="bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded text-sm font-semibold">Logout</a>
        </div>
    </nav>

    <div class="flex">
        <div class="w-64 bg-white min-h-screen shadow-md p-4 space-y-2">
            <a href="dashboard.php" class="block py-2.5 px-4 bg-blue-50 text-blue-600 rounded font-semibold">Dashboard</a>
            <a href="baju.php" class="block py-2.5 px-4 text-slate-600 hover:bg-slate-100 rounded">Data Baju (Utama)</a>
            <a href="pelanggan.php" class="block py-2.5 px-4 text-slate-600 hover:bg-slate-100 rounded">Data Pelanggan</a>
            <a href="transaksi.php" class="block py-2.5 px-4 text-slate-600 hover:bg-slate-100 rounded">Transaksi Penjualan</a>
        </div>

        <div class="flex-1 p-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-6">Ringkasan Sistem</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
                    <p class="text-sm">Total Jenis Baju</p>
                    <p class="text-3xl font-bold mt-2"><?= $total_baju ?></p>
                </div>
                <div class="bg-emerald-500 text-white p-6 rounded-lg shadow">
                    <p class="text-sm">Total Pelanggan</p>
                    <p class="text-3xl font-bold mt-2"><?= $total_pelanggan ?></p>
                </div>
                <div class="bg-indigo-500 text-white p-6 rounded-lg shadow">
                    <p class="text-sm">Total Transaksi</p>
                    <p class="text-3xl font-bold mt-2"><?= $total_transaksi ?></p>
                </div>
                <div class="bg-amber-500 text-white p-6 rounded-lg shadow">
                    <p class="text-sm">Total Pendapatan</p>
                    <p class="text-2xl font-bold mt-2">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>