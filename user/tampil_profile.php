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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Membership </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="../template/profile.css" rel="stylesheet">
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
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
                <i class="fas fa-comments"></i> Kritik&Saran Anda
            </a>
        </li>
        <li>
            <a href="../user/kritik_saran.php" target="frame">
                <i class="fas fa-pencil-alt"></i> Tambah Kritik&Saran
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
        <div class="header">
            <h1>Profil Member</h1>
        </div>
        <div class="profile">
            <?php if (!empty($members)) : ?>
                <?php foreach ($members as $member) : ?>
                    <h3>Selamat datang, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h3>
                    <p><i class="fas fa-user"></i> <strong>Nama :</strong> <?= htmlspecialchars($member['username']) ?></p>
                    <p><i class="fas fa-venus"></i> <strong>Jenis Kelamin :</strong> <?= htmlspecialchars($member['jenis_kelamin']) ?></p>
                    <p><i class="fas fa-map-marker-alt"></i> <strong>Alamat :</strong> <?= htmlspecialchars($member['alamat']) ?></p>
                    <p><i class="fas fa-id-badge"></i> <strong>Status Member :</strong> <?= htmlspecialchars($member['status']) ?></p>
                    <p><i class="fas fa-phone"></i> <strong>No HP :</strong> <?= htmlspecialchars($member['no_hp']) ?></p>
                    <p><i class="fas fa-user-tag"></i> <strong>Jenis Member :</strong> <?= htmlspecialchars($member['jenis_member']) ?></p>
                    <p><i class="fas fa-calendar-alt"></i> <strong>Berlaku sampai Dengan :</strong> <?= htmlspecialchars($member['berlaku_s_d']) ?></p>
                    <p><i class="fas fa-hourglass-half"></i> <strong>Sisa Masa Berlaku :</strong>
                        <?php
                        $berlaku_s_d = new DateTime($member['berlaku_s_d']);
                        $today = new DateTime();
                        if ($berlaku_s_d < $today) {
                            echo "Masa Berlaku Membership Telah Habis!";
                        } else {
                            echo $berlaku_s_d->diff($today)->format('%a Hari');
                        }
                        ?>
                    </p>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Member tidak ditemukan.</p>
            <?php endif; ?>

        </div>
        <div id="frame-container">
            <iframe id="frame" src="" frameborder="0" style="width: 100%; height: 100%;"></iframe>
        </div>
    </div>
</body>

</html>