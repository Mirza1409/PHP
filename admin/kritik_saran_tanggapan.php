<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('/index.php');
}

// Inisialisasi variabel filter
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : 'all';

// Query untuk mengambil kritik saran berdasarkan filter
$query = "SELECT k.*, u.username FROM kritik_saran k 
          JOIN users u ON k.id_member = u.id_user";

if ($status_filter === 'belum') {
    $query .= " WHERE k.status = 'Belum Ditanggapi'";
} elseif ($status_filter === 'sudah') {
    $query .= " WHERE k.status = 'Sudah Ditanggapi'";
}

$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $id_kritiksaran = $_POST['id_kritiksaran'];
    $tanggapan = $_POST['tanggapan'];
    $update_query = "UPDATE kritik_saran 
                     SET status = 'Sudah Ditanggapi', tanggapan = '$tanggapan' 
                     WHERE id_kritiksaran = $id_kritiksaran";
    mysqli_query($conn, $update_query);
    redirect('kritik_saran_tanggapan.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kritik & Saran</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/kritik_saran_tanggapan.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Tanggapi Kritik&Saran Member</h2>

        <!-- Form Filter -->
        <form method="post" action="" class="mb-4">
            <label for="status_filter" class="form-label">Filter:</label>
            <select name="status_filter" id="status_filter" class="form-select w-25 d-inline-block" onchange="this.form.submit()">
                <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Semua</option>
                <option value="belum" <?= $status_filter === 'belum' ? 'selected' : '' ?>>Belum Ditanggapi</option>
                <option value="sudah" <?= $status_filter === 'sudah' ? 'selected' : '' ?>>Sudah Ditanggapi</option>
            </select>
        </form>

        <!-- Tabel Kritik & Saran -->
        <table class="table table-bordered table-striped">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Nama Member</th>
                    <th>Isi</th>
                    <th>Status</th>
                    <th>Tanggapan</th>
                    <th>Masukkan Tanggapan</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id_kritiksaran'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['isi_kritiksaran'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['tanggapan'] ?></td>
                    <td>
                        <?php if ($row['status'] === 'Belum Ditanggapi'): ?>
                        <form method="post">
                            <textarea name="tanggapan" required></textarea>
                            <input type="hidden" name="id_kritiksaran" value="<?= $row['id_kritiksaran'] ?>">
                            <button type="submit" name="submit" class="btn btn-success btn-sm mt-2">Tanggapi</button>
                        </form>
                        <?php else: ?>
                        Sudah Ditanggapi
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-warning">Kembali</a>
    </div>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
