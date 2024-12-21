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
            redirect("tampil_kritik_saran.php");
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
    <meta charset="UTF-8">
    <title>Kritik & Saran</title>
</head>

<body>
    <h2>Kritik & Saran</h2>
    <?php if (isset($error)) : ?>
        <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>
    <?php if (isset($success)) : ?>
        <p style="color:green;"><?= $success; ?></p>
    <?php endif; ?>
    <form method="post">
        <label for="isi_kritiksaran">Isi Kritik & Saran:</label><br>
        <textarea name="isi_kritiksaran" required></textarea><br><br>
        <button type="submit">Kirim</button>
    </form><br>
    <a href="tampil_kritik_saran.php">Kembali</a>
</body>
</html>