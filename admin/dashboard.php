<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('/index.php');
}

// Menghitung jumlah anggota
$query_members = "SELECT COUNT(*) as total_members FROM members";
$result_members = mysqli_query($conn, $query_members);
$total_members = mysqli_fetch_assoc($result_members)['total_members'];

// Menghitung jumlah kritik dan saran yang belum ditanggapi
$query_kritik = "SELECT COUNT(*) as total_kritik FROM kritik_saran WHERE status = 'Belum Ditanggapi'";
$result_kritik = mysqli_query($conn, $query_kritik);
$total_kritik = mysqli_fetch_assoc($result_kritik)['total_kritik'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <?= $_SESSION['user']['username'] ?>!</p>
    <h3>Statistik</h3>
    <ul>
        <li>Jumlah Member: <?= $total_members ?></li>
        <li>Jumlah Kritik & Saran Belum Ditanggapi: <?= $total_kritik ?></li>
    </ul>
    <h3>Menu</h3>
    <ul>
        <li><a href="members.php">Kelola Member</a></li>
        <li><a href="kritik_saran.php">Kelola Kritik & Saran</a></li>
        <li><a href="tampil_user_admin.php">Informasi Admin</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>