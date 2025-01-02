<?php
include '../includes/db.php';
include '../includes/functions.php';

// Periksa apakah pengguna sudah login
is_logged_in();

// Periksa apakah pengguna adalah admin
if (!is_admin()) {
    redirect('../index.php');
}

// Mengambil data user dengan role admin
$query = "SELECT * FROM users WHERE role = 'admin'";
$result = mysqli_query($conn, $query);

// Validasi query
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Admin</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/tampil_delete_admin.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Hapus Admin</h2>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Kelola Admin</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id_user']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <a class="btn btn-danger" href="delete_user_admin.php?username=<?= urlencode($row['username']) ?>" onclick="return confirm('Yakin ingin menghapus admin ini?');">Hapus Admin</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn btn-warning">Kembali</a>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script> <!-- Pastikan path benar -->
</body>
</html>
