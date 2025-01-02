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
    <iframe src="../user/profile.php" height="0" width="0" style="display:noneflex;visibility:hidden"></iframe>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tampil Kritik & Saran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/profile.css" rel="stylesheet">
</head>

<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
        <li>
            <a href="../user/profile.php" target="frame">
                <i class="fas fa-user"></i> Profil
            </a>
        </li>
        <li>
            <a href="../user/tampil_kritik_saran.php" target="frame">
                <i class="fas fa-comments"></i> Kritik & Saran Anda
            </a>
        </li>
        <li>
            <a href="../user/kritik_saran.php" target="frame">
                <i class="fas fa-pencil-alt"></i> Tambah Kritik & Saran
            </a>
        </li>
        <li>
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>

        </ul>
    </div>
    <div class="container">
        <h2 class="text-center mb-4">Kritik & Saran Anda</h2>
        <!-- Form Filter -->
        <form method="post" action="" class="mb-4">
            <div class="filter-container">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <label for="status_filter" class="form-label">Filter</label>
                        <select name="status_filter" id="status_filter" class="form-select" onchange="this.form.submit()">
                            <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Semua</option>
                            <option value="belum" <?= $status_filter === 'belum' ? 'selected' : '' ?>>Belum Ditanggapi</option>
                            <option value="sudah" <?= $status_filter === 'sudah' ? 'selected' : '' ?>>Sudah Ditanggapi</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>

        <div class="form-kritik">
            <?php if (!empty($kritik_saran)) : ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th >ID Kritik&Saran</th>
                            <th>Isi Kritik&Saran</th>
                            <th>Status</th>
                            <th>Tanggapan</th>
                            <th>Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kritik_saran as $ks) : ?>
                            <tr>
                                <td><?= htmlspecialchars($ks['id_kritiksaran']) ?></td>
                                <td><?= htmlspecialchars($ks['isi_kritiksaran']) ?></td>
                                <td><?= htmlspecialchars($ks['status']) ?></td>
                                <td><?= htmlspecialchars($ks['tanggapan'] ?? 'Belum ada tanggapan') ?></td>
                                <td>
                                    <a href="../user/delete_kritik_saran.php?id=<?= $ks['id_kritiksaran'] ?>" class="btn btn-danger btn-sm">
                                        Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
        </div>
    <?php else : ?>
        <p class="text-center text-warning">Kritik & Saran tidak ditemukan!</p>
    <?php endif; ?>
    </div>

    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>

</html>

