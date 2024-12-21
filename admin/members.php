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
</head>
<body>
    <h2>Daftar Member</h2>

    <!-- Form Pencarian -->
    <form method="post" action="">
        <input type="text" name="search" placeholder="Nama (Username)" value="<?= htmlspecialchars($search_username) ?>">
        <button type="submit">Cari</button>
    </form>

    <!-- Form Filter -->
    <form method="post" action="">
        <label for="filter">Filter:</label>
        <select name="filter" id="filter" onchange="this.form.submit()">
            <option value="all" <?= $filter === 'all' ? 'selected' : '' ?>>Semua</option>
            <option value="kurang_7_hari" <?= $filter === 'kurang_7_hari' ? 'selected' : '' ?>>Masa Berlaku Kurang dari 7 Hari</option>
            <option value="habis" <?= $filter === 'habis' ? 'selected' : '' ?>>Masa Berlaku Sudah Habis</option>
        </select>
    </form>

    <table border="1">
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
            <th>Kelola Data Member Gym</th>
        </tr>
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
                    echo "Masa Berlaku Membership Telah Habis!";
                } else {
                    echo $days_remaining . " Hari";
                }
                ?>
            </td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <a href="edit_member.php?id=<?= $row['id_member'] ?>">Perpanjang Membership</a> ||
                <a href="delete_member.php?id=<?= $row['id_member'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus member ini?');">Hapus Member</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <?php
    // Menutup statement dan koneksi
    $stmt->close();
    $conn->close();
    ?>
    <a href="add_member.php">Tambah Member Baru</a><br>
    <a href="dashboard.php">Kembali</a>
</body>
</html>