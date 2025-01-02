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
    <link href="../template/tampil_tambah_admin.css" rel="stylesheet">
    </head>
<body>
    <div class="container">
        <h2 class="text-center">Tambah Admin</h2>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $row['id_user'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="add_user_admin.php" class="btn btn-success">Tambah Admin</a>||
        <a href="dashboard.php"class="btn btn-warning">Kembali</a>
    </div>
    <script src="../js/bootstrap.bundle.min.js"></script> <!-- Pastikan path benar -->
</body>
</html>
