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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
    </style>
</head>
<body>
    <div class="sidebar">
        <h1>Admin</h1>
        <ul>
            
        <ul>
            <li>
                <a href="members_perpanjang_membership.php">
                    <i class="fas fa-user-clock"></i> Perpanjang Membership
                </a>
            </li>
            <li>
                <a href="members_tambah_member.php">
                    <i class="fas fa-user-plus"></i> Tambah Member
                </a>
            </li>
            <li>
                <a href="members_ganti_password.php">
                    <i class="fas fa-key"></i> Mengganti Password Member
                </a>
            </li>
            <li>
                <a href="members_delete_member.php">
                    <i class="fas fa-user-minus"></i> Hapus Member
                </a>
            </li>
            <li>
                <a href="kritik_saran_tanggapan.php">
                    <i class="fas fa-comment-dots"></i> Tanggapi Kritik & Saran
                </a>
            </li>
            <li>
                <a href="kritik_saran_hapus.php">
                    <i class="fas fa-comment-slash"></i> Hapus Kritik & Saran
                </a>
            </li>
            <li>
                <a href="tampil_ganti_password_admin.php">
                    <i class="fas fa-shield-alt"></i> Ganti Password Admin
                </a>
            </li>
            <li>
                <a href="tampil_tambah_admin.php">
                    <i class="fas fa-user-shield"></i> Tambah Admin
                </a>
            </li>
            <li>
                <a href="tampil_delete_admin.php">
                    <i class="fas fa-user-shield"></i> Hapus Admin
                </a>
            </li>
            <li>
                <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header class="header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <?= htmlspecialchars($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8') ?>!</p>
        </header>

        <main>
            <section class="stats">
                <div class="card">
                    <p>Jumlah Member: <?= $total_members ?></p>
                </div>
                <div class="card">
                    <p>Kritik&Saran Belum Ditanggapi: <?= $total_kritik ?></p>
                </div>
            </section>
        </main>
    </div>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
