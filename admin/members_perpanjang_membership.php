<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('/index.php');
}

// Inisialisasi variabel pencarian dan filter
$search_username = '';
$filter = isset($_POST['filter']) ? $_POST['filter'] : 'all';

// Mengambil data dari tabel members dan users
$query = "SELECT m.*, u.role FROM members m JOIN users u ON m.username = u.username";

if (isset($_POST['search'])) {
    $search_username = trim($_POST['search']);
    $query .= " WHERE m.username LIKE ?";
}

// Menambahkan filter berdasarkan masa berlaku
if ($filter === 'kurang_7_hari') {
    $query .= isset($_POST['search']) ? " AND m.berlaku_s_d BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)" : " WHERE m.berlaku_s_d BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)";
} elseif ($filter === 'habis') {
    $query .= isset($_POST['search']) ? " AND m.berlaku_s_d < NOW()" : " WHERE m.berlaku_s_d < NOW()";
}

// Siapkan statement
$stmt = $conn->prepare($query);

// Bind parameter jika ada pencarian
if (isset($_POST['search'])) {
    $search_param = "%" . $search_username . "%";
    $stmt->bind_param("s", $search_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kelola Member</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/members_perpanjang_membership.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
    <h2 class="text-center mb-4">Perpanjang Membership</h2>

    <!-- Form Pencarian -->
    <form method="post" action="" class="form-inline mb-3">
        <div class="form-group mx-sm-3 mb-2">
            <input type="text" name="search" class="form-control" placeholder="Nama (Username)" value="<?= htmlspecialchars($search_username) ?>">
        </div>
        <button type="submit" class="btn btn-green mb-2">Cari</button>
    </form>

    <!-- Form Filter -->
    <form method="post" action="" class="form-inline mb-4">
        <label for="filter" class="mr-2">Filter:</label>
        <select name="filter" id="filter" class="form-control mr-2" onchange="this.form.submit()">
            <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>Semua</option>
            <option value="kurang_7_hari" <?= $filter === 'kurang_7_hari' ? 'selected' : '' ?>>Masa Berlaku Kurang dari 7 Hari</option>
            <option value="habis" <?= $filter === 'habis' ? 'selected' : '' ?>>Masa Berlaku Sudah Habis</option>
        </select>
    </form>

    <!-- Tabel Data Member -->
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nama (Username)</th>
            <th>Jenis Kelamin</th>
            <th>Alamat</th>
            <th>Status Member</th>
            <th>No HP</th>
            <th>Jenis Member</th>
            <th>Berlaku Sampai Dengan</th>
            <th>Sisa Masa Berlaku</th>
            <th>Role</th>
            <th>Atur</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_member'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['jenis_kelamin']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['no_hp']) ?></td>
            <td><?= htmlspecialchars($row['jenis_member']) ?></td>
            <td><?= htmlspecialchars($row['berlaku_s_d']) ?></td>
            <td>
                <?php
                $berlaku_s_d = new DateTime($row['berlaku_s_d']);
                $today = new DateTime();
                $interval = $today->diff($berlaku_s_d);
                $days_remaining = $interval->days;
                if ($berlaku_s_d < $today) {
                    echo "<span class='text-danger'>Masa Berlaku Membership Telah Habis!</span>";
                } else {
                    echo "$days_remaining Hari";
                }
                ?>
            </td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>                
                <a href="edit_member.php?id=<?= $row['id_member'] ?>" class="btn btn-sm btn-primary">Perpanjang Membership</a>
            </td>
        </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-warning">Kembali</a>
</div>

<?php
// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>

<!-- Tambahkan link JavaScript Bootstrap -->
<script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
