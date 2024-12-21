<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();

$id_member = $_SESSION['user']['id_user'];

// Inisialisasi variabel filter
$status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : 'all';

// Query untuk mengambil kritik dan saran berdasarkan filter
$query = "SELECT * FROM kritik_saran WHERE id_member = ?";
if ($status_filter === 'belum') {
    $query .= " AND status = 'Belum Ditanggapi'";
} elseif ($status_filter === 'sudah') {
    $query .= " AND status = 'Sudah Ditanggapi'";
}

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_member);
$stmt->execute();
$result = $stmt->get_result();

$kritik_saran = [];
if ($result->num_rows > 0) {
    $kritik_saran = $result->fetch_all(MYSQLI_ASSOC);
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tampil Kritik & Saran</title>
</head>
<body>
    <h2>Kritik & Saran Anda</h2>

    <!-- Form Filter -->
    <form method="post" action="">
        <label for="status_filter">Filter:</label>
        <select name="status_filter" id="status_filter" onchange="this.form.submit()">
            <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Semua</option>
            <option value="belum" <?= $status_filter === 'belum' ? 'selected' : '' ?>>Belum Ditanggapi</option>
            <option value="sudah" <?= $status_filter === 'sudah' ? 'selected' : '' ?>>Sudah Ditanggapi</option>
        </select>
    </form>
    <?php if (!empty($kritik_saran)) : ?>
        <table border="1">
            <tr>
                <th>id_kritiksaran</th>
                <th>Isi Kritik & Saran</th>
                <th>Status</th>
                <th>Tanggapan</th>
                <th>Hapus</th>
            </tr>
            <?php foreach ($kritik_saran as $ks) : ?>
            <tr>
                <td><?= htmlspecialchars($ks['id_kritiksaran']) ?></td>
                <td><?= htmlspecialchars($ks['isi_kritiksaran']) ?></td>
                <td><?= htmlspecialchars($ks['status']) ?></td>
                <td><?= htmlspecialchars($ks['tanggapan'] ?? 'Belum ada tanggapan') ?></td>
                <td><a href=" ../user/delete_kritik_saran.php?id=<?=$ks['id_kritiksaran']?>">Hapus</a><td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else : ?>
        <p>Kritik & Saran tidak ditemukan!</p>
    <?php endif; ?><br>
    <a href="../user/kritik_saran.php">Tambahkan Kritik saran</a><br>
    <a href="profile.php">Kembali</a>
    
</body>
</html>