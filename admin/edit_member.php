<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('/index.php');
}

$id = $_GET['id'];
$query = "SELECT * FROM members WHERE id_member = $id";
$result = mysqli_query($conn, $query);
$member = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat = $_POST['alamat'];
    $status = $_POST['status'];
    $no_hp = $_POST['no_hp'];
    $jenis_member = $_POST['jenis_member'];
    $berlaku_s_d = $_POST['berlaku_s_d'];

    $update_query = "UPDATE members 
                     SET alamat = '$alamat', 
                         status = '$status', 
                         no_hp = '$no_hp', 
                         jenis_member = '$jenis_member', 
                         berlaku_s_d = '$berlaku_s_d' 
                     WHERE id_member = $id";
    if (mysqli_query($conn, $update_query)) {  
        echo "Data berhasil diupdate";
    } else {
        $error = "Gagal mengedit member!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/edit_member.css" rel="stylesheet">
    
</head>
<body>
    <a href="members_perpanjang_membership.php" class="btn btn-warning">Kembali</a>
    <h2>Edit Member</h2>
    <form method="post">
        <label>Nama :</label><br>
        <input type="text" name="nama" value="<?= $member['username'] ?>" readonly required><br>
        <label>Jenis Kelamin :</label><br>
        <input type="text" value="<?= $member['jenis_kelamin'] ?>" readonly required><br>
        <label>Alamat :</label><br>
        <textarea name="alamat" required><?= $member['alamat'] ?></textarea><br>
        <label>Status Member :</label><br>
        <select name="status" id="status" required>
            <option value="Umum" <?= $member['status'] == 'Umum' ? 'selected' : '' ?>>Umum</option>
            <option value="Mahasiswa" <?= $member['status'] == 'Mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
        </select><br>
        <label>No HP :</label><br>
        <input type="text" name="no_hp" value="<?= $member['no_hp'] ?>" required><br>
        <label>Jenis Member :</label><br>
        <select name="jenis_member" required>
            <option value="Member Bulanan" <?= $member['jenis_member'] == 'Member Bulanan' ? 'selected' : '' ?>>Member Bulanan</option>
            <option value="Paket 3 Bulan" <?= $member['jenis_member'] == 'Paket 3 Bulan' ? 'selected' : '' ?>>Paket 3 Bulan</option>
            <option value="Paket 6 Bulan" <?= $member['jenis_member'] == 'Paket 6 Bulan' ? 'selected' : '' ?>>Paket 6 Bulan</option>
            <option value="Paket 1 Tahun" <?= $member['jenis_member'] == 'Paket 1 Tahun' ? 'selected' : '' ?>>Paket 1 Tahun</option>
        </select><br>
        <label>Berlaku Sampai :</label><br>
        <input type="date" name="berlaku_s_d" value="<?= $member['berlaku_s_d'] ?>" required><br><br>
        <button type="submit">Simpan</button>|                                  |
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    
</body>
</html>