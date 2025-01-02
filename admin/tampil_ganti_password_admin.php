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
    <title>Ganti Password Admin</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/tampil_ganti_password_admin.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center">Ganti Password Admin</h2>

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
                    <td><?= $row['id_user'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td>
                        <a class="btn btn-warning" href="ganti_password_admin.php?username=<?= htmlspecialchars($row['username']) ?>">Ganti Password</a>
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
