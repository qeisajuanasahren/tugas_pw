<?php
require 'config.php';

// Proteksi Halaman Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Fitur Tambah Pelanggan
if (isset($_POST['tambah'])) {
    $kode_pelanggan = mysqli_real_escape_string($koneksi, $_POST['kode_pelanggan']);
    $nama           = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat         = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    mysqli_query($koneksi, "INSERT INTO pelanggan VALUES(NULL, '$kode_pelanggan', '$nama', '$no_hp', '$alamat')");
    header("Location: pelanggan.php");
    exit;
}

// Fitur Hapus Pelanggan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $id");
    header("Location: pelanggan.php");
    exit;
}

// Fitur Edit Pelanggan (Proses Update Data)
if (isset($_POST['update'])) {
    $id             = $_POST['id'];
    $kode_pelanggan = mysqli_real_escape_string($koneksi, $_POST['kode_pelanggan']);
    $nama           = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat         = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    mysqli_query($koneksi, "UPDATE pelanggan SET kode_pelanggan='$kode_pelanggan', nama='$nama', no_hp='$no_hp', alamat='$alamat' WHERE id=$id");
    header("Location: pelanggan.php");
    exit;
}

// Ambil Data Edit (jika ada tombol edit dipencet)
$data_edit = null;
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $result_edit = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id = $id_edit");
    $data_edit = mysqli_fetch_assoc($result_edit);
}

// Fitur Pencarian Data Pelanggan (Nilai Plus)
$keyword = $_GET['cari'] ?? '';
$query = "SELECT * FROM pelanggan WHERE nama LIKE '%$keyword%' OR kode_pelanggan LIKE '%$keyword%' OR no_hp LIKE '%$keyword%' ORDER BY id DESC";
$pelanggan = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Data Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-slate-800 min-h-screen text-white p-4 space-y-2">
            <a href="dashboard.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Dashboard</a>
            <a href="sepatu.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Data Sepatu</a>
            <a href="pelanggan.php" class="block py-2.5 px-4 bg-blue-600 rounded font-semibold">Data Pelanggan</a>
            <a href="transaksi.php" class="block py-2.5 px-4 text-slate-300 hover:bg-slate-700 rounded">Transaksi</a>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6 text-slate-800">Kelola Data Pelanggan (Pendukung)</h2>

            <!-- Form Tambah / Edit Data -->
            <form method="POST" class="bg-white p-6 rounded-lg shadow mb-6 grid grid-cols-2 gap-4">
                <input type="hidden" name="id" value="<?= $data_edit['id'] ?? '' ?>">
                
                <div>
                    <label class="block text-sm text-slate-600 mb-1">Kode Pelanggan</label>
                    <input type="text" name="kode_pelanggan" placeholder="e.g. PLG-003" value="<?= $data_edit['kode_pelanggan'] ?? '' ?>" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-slate-600 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Nama Pelanggan" value="<?= $data_edit['nama'] ?? '' ?>" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-slate-600 mb-1">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" placeholder="08xxxxxxxxxx" value="<?= $data_edit['no_hp'] ?? '' ?>" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm text-slate-600 mb-1">Alamat</label>
                    <input type="text" name="alamat" placeholder="Alamat Pelanggan" value="<?= $data_edit['alamat'] ?? '' ?>" required class="w-full border p-2 rounded focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="col-span-2">
                    <?php if ($data_edit): ?>
                        <button type="submit" name="update" class="bg-amber-500 text-white px-6 py-2 rounded hover:bg-amber-600 font-semibold">Update Data</button>
                        <a href="pelanggan.php" class="bg-slate-400 text-white px-6 py-2 rounded hover:bg-slate-500 font-semibold inline-block">Batal</a>
                    <?php else: ?>
                        <button type="submit" name="tambah" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-semibold">Simpan Data Pelanggan</button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Fitur Pencarian (Fitur Tambahan) -->
            <form method="GET" class="mb-4 flex gap-2">
                <input type="text" name="cari" value="<?= htmlspecialchars($keyword) ?>" placeholder="Cari nama, kode, no hp..." class="border p-2 rounded w-1/3">
                <button type="submit" class="bg-slate-700 text-white px-4 py-2 rounded hover:bg-slate-800">Cari</button>
            </form>

            <!-- Tabel Data Pelanggan -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-200">
                        <tr>
                            <th class="p-3">No</th>
                            <th class="p-3">Kode Pelanggan</th>
                            <th class="p-3">Nama</th>
                            <th class="p-3">No HP</th>
                            <th class="p-3">Alamat</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($pelanggan)): ?>
                        <tr class="border-t hover:bg-slate-50">
                            <td class="p-3"><?= $no++ ?></td>
                            <td class="p-3 font-semibold text-blue-600"><?= $row['kode_pelanggan'] ?></td>
                            <td class="p-3"><?= $row['nama'] ?></td>
                            <td class="p-3"><?= $row['no_hp'] ?></td>
                            <td class="p-3"><?= $row['alamat'] ?></td>
                            <td class="p-3 space-x-2">
                                <a href="pelanggan.php?edit=<?= $row['id'] ?>" class="text-amber-600 hover:underline">Edit</a>
                                <a href="pelanggan.php?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus data pelanggan ini?')" class="text-red-600 hover:underline">Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</body>
</html>