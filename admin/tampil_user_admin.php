<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('../index.php');
}

// Mengambil data user dengan role admin
$query = "SELECT * FROM users WHERE role = 'admin'";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Admin</title>
</head>
<body>
    <h2>Daftar Admin</h2>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Kelola Admin</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id_user'] ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <a href="ganti_password_admin.php?id=<?= $row['id_user'] ?>">Ganti Password</a> ||
                <a href="delete_user_admin.php?id=<?= $row['id_user'] ?>">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="add_user_admin.php">Tambah Admin</a><br>
    <a href="dashboard.php">Kembali ke Dashboard</a><br>
</body>
</html>