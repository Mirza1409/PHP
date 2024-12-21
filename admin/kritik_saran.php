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
    redirect('kritik_saran.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Kritik & Saran</title>
</head>
<body>
    <h2>Kritik & Saran</h2>

    <!-- Form Filter -->
    <form method="post" action="">
        <label for="status_filter">Filter:</label>
        <select name="status_filter" id="status_filter" onchange="this.form.submit()">
            <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Semua</option>
            <option value="belum" <?= $status_filter === 'belum' ? 'selected' : '' ?>>Belum Ditanggapi</option>
            <option value="sudah" <?= $status_filter === 'sudah' ? 'selected' : '' ?>>Sudah Ditanggapi</option>
        </select>
    </form>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama Member</th>
            <th>Isi</th>
            <th>Status</th>
            <th>Tanggapan</th>
            <th>Kirim</th>
            <th>Hapus Kritik Saran</th>
        </tr>
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
                        <button type="submit" name="submit">Tanggapi</button>
                    </form>
                <?php else: ?>
                    Sudah Ditanggapi
                <?php endif; ?>
            <td><a href=" ../admin/delete_kritik_saran.php?id=<?=$row['id_kritiksaran']?>">Hapus</a><td>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="dashboard.php">Kembali</a>
</body>
</html>