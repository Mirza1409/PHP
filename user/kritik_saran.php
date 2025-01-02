<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isi_kritiksaran = htmlspecialchars(trim($_POST['isi_kritiksaran']));
    $id_member = $_SESSION['user']['id_user']; // Asumsikan id_user disimpan dalam session

    // Validasi input
    if (empty($isi_kritiksaran)) {
        $error = "Isi kritik dan saran tidak boleh kosong.";
    } else {
        // Gunakan prepared statement untuk keamanan
        $query = "INSERT INTO kritik_saran (id_member, isi_kritiksaran, status) VALUES (?, ?, 'Belum Ditanggapi')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("is", $id_member, $isi_kritiksaran);

        if ($stmt->execute()) {
            $success = "Kritik dan saran berhasil dikirim!";

        } else {
            $error = "Gagal mengirim kritik dan saran: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <iframe src="../user/profile.php" height="0" width="0" style="display:noneflex;visibility:hidden"></iframe>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kritik & Saran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/profile.css" rel="stylesheet">
</head>

<body>
<div class="sidebar">
        <h2>Menu</h2>
        <ul>
        <li>
            <a href="../user/tampil_profile.php" target="frame">
                <i class="fas fa-user"></i> Profil
            </a>
        </li>
        <li>
            <a href="../user/tampil_kritik_saran.php" target="frame">
                <i class="fas fa-comments"></i> Kritik & Saran Anda
            </a>
        </li>
        <li>
            <a href="../user/kritik_saran.php" target="frame">
                <i class="fas fa-pencil-alt"></i> Tambah Kritik & Saran
            </a>
        </li>
        <li>
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
        </ul>
    </div>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
        <li>
            <a href="../user/tampil_profile.php" target="frame">
                <i class="fas fa-user"></i> Profil
            </a>
        </li>
        <li>
            <a href="../user/tampil_kritik_saran.php" target="frame">
                <i class="fas fa-comments"></i> Kritik & Saran Anda
            </a>
        </li>
        <li>
            <a href="../user/kritik_saran.php" target="frame">
                <i class="fas fa-pencil-alt"></i> Tambah Kritik & Saran
            </a>
        </li>
        <li>
            <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
        </ul>
    </div>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center">Kritik & Saran</h2>

            <?php if (isset($error)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($success)) : ?>
                <div class="alert alert-success" role="alert">
                    <?= $success; ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-data mb-3">
                    <label for="isi_kritiksaran" class="form-label">Isi Kritik & Saran:</label>
                    <textarea name="isi_kritiksaran" id="isi_kritiksaran" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>
    <script src="../bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
</body>
</html>
