<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();

$username = $_SESSION['user']['username'];

// Menggunakan prepared statement untuk keamanan
$query = "SELECT * FROM members WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $members = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $members = []; // Jika tidak ada member ditemukan
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil User</title>
    <h2>Profil Member</h2>
    <p>Selamat datang, <?= $_SESSION['user']['username'] ?>!</p>
</head>
<body>
    <?php if (!empty($members)) : ?>
        <?php foreach ($members as $member) : ?>
            <p>Nama : <?= htmlspecialchars($member['username']) ?></p>
            <p>Jenis Kelamin : <?= htmlspecialchars($member['jenis_kelamin']) ?></p>
            <p>Alamat : <?= htmlspecialchars($member['alamat']) ?></p>
            <p>Status Member : <?= htmlspecialchars($member['status']) ?></p>
            <p>No HP : <?= htmlspecialchars($member['no_hp']) ?></p>
            <p>Jenis Member : <?= htmlspecialchars($member['jenis_member']) ?></p>
            <p>Berlaku sampai Dengan : <?= htmlspecialchars($member['berlaku_s_d']) ?></p>
            <p>Sisa Masa Berlaku :
                <?php
                // Hitung mundur hari
                $berlaku_s_d = new DateTime($member['berlaku_s_d']);
                $today = new DateTime();
                $interval = $today->diff($berlaku_s_d);
                $days_remaining = $interval->days;

                if ($berlaku_s_d < $today) {
                    echo "Masa Berlaku Membership Telah Habis!";
                } else {
                    echo $days_remaining . "Hari";
                }
                ?>
            </p>
        <?php endforeach; ?>
        <a href="tampil_kritik_saran.php">Kritik & Saran</a><br>
        <a href="logout.php">Logout</a>
    <?php else : ?>
        <p>Member tidak ditemukan.</p>
    <?php endif; ?>
</body>
</html>