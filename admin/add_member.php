<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('../index.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction(); // Mulai transaksi

    try {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm_password']); 
        $role = 'user';

        // Validasi input user
        if (empty($username) || empty($password) || empty($confirm_password)) {
            throw new Exception("Semua field harus diisi.");
        } elseif (strlen($username) < 3 || strlen($username) > 20) {
            throw new Exception("Username harus antara 3 dan 20 karakter.");
        } elseif (strlen($password) < 6) {
            throw new Exception("Password minimal 6 karakter.");
        } elseif ($password !== $confirm_password) { 
            throw new Exception("Password dan konfirmasi password tidak cocok.");
        }

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        // Cek apakah username sudah ada
        $check_query = "SELECT * FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            throw new Exception("Username sudah ada, silakan pilih username lain.");
        }

        $nama = htmlspecialchars(trim($_POST['nama']));
        if ($nama !== $username) {
            throw new Exception("Nama member harus sesuai dengan username.");
        }

        // Tambah user
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password_hashed, $role);
        if (!$stmt->execute()) {
            throw new Exception("Gagal menambah user: " . htmlspecialchars($stmt->error));
        }

        // Validasi dan tambah member
        $jenis_kelamin = htmlspecialchars(trim($_POST['jenis_kelamin']));
        $alamat = htmlspecialchars(trim($_POST['alamat']));
        $status = htmlspecialchars(trim($_POST['status']));
        $no_hp = htmlspecialchars(trim($_POST['no_hp']));
        $jenis_member = htmlspecialchars(trim($_POST['jenis_member']));
        $berlaku_s_d = htmlspecialchars(trim($_POST['berlaku_s_d']));

        if (empty($jenis_kelamin) || empty($alamat) || empty($status) || empty($no_hp) || empty($jenis_member) || empty($berlaku_s_d)) {
            throw new Exception("Semua field harus diisi.");
        } elseif (!is_numeric($no_hp)) {
            throw new Exception("No HP harus berupa angka.");
        } elseif (strtotime($berlaku_s_d) <= time()) {
            throw new Exception("Tanggal berlaku harus lebih besar dari hari ini.");
        }

        $query = "INSERT INTO members (username, jenis_kelamin, alamat, status, no_hp, jenis_member, berlaku_s_d) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_member = $conn->prepare($query);
        $stmt_member->bind_param("sssssss", $username, $jenis_kelamin, $alamat, $status, $no_hp, $jenis_member, $berlaku_s_d);
        if (!$stmt_member->execute()) {
            throw new Exception("Gagal menambah member: " . htmlspecialchars($stmt_member->error));
        }

        $conn->commit(); // Commit transaksi jika semua berhasil
        $success = "User dan member berhasil ditambahkan!";
    } catch (Exception $e) {
        $conn->rollback(); // Rollback transaksi jika ada yang gagal
        $error = $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/add_member.css" rel="stylesheet">
</head>
<body>
    <h1>Tambah User</h1>

    <?php if (!empty($error)): ?>
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="color: green;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="username">Username:</label><br>
        <input type="text" name="username" id="username" required><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" id="password" required><br>

        <label for="confirm_password">Konfirmasi Password:</label><br>
        <input type="password" name="confirm_password" id="confirm_password" required></br> 

        <h1>Tambah Member Gym</h1>

        <label for="nama">Nama ( Sesuai Username) :</label><br>
        <input type="text" name="nama" id="nama" required><br>

        <label for="jenis_kelamin">Jenis Kelamin :</label><br>
        <select name="jenis_kelamin" id="jenis_kelamin" required>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select><br>

        <label for="alamat">Alamat :</label><br>
        <input type="text" name="alamat" id="alamat" required><br>
        
        <label>Status Member :</label><br>
        <select name="status" id="status" required>
            <option value="Umum">Umum</option>
            <option value="Mahasiswa">Mahasiswa</option>
        </select><br>

        <label for="no_hp">No HP :</label><br>
        <input type="text" name="no_hp" id="no_hp" required><br>
        
        <label>Jenis Member :</label><br>
        <select name="jenis_member" id="jenis_member" required>
            <option value="Member Bulanan">Member Bulanan</option>
            <option value="Paket 3 Bulan">Paket 3 Bulan</option>
            <option value="Paket 6 Bulan">Paket 6 Bulan</option>
            <option value="Paket 1 Tahun">Paket 1 Tahun</option>
        </select><br>

        <label for="berlaku_s_d">Berlaku S/D :</label><br>
        <input type="date" name="berlaku_s_d" id="berlaku_s_d" required><br>
        <button type="submit" class="btn btn-success">Tambah Pengguna</button>

        <!-- <button type="submit">Tambah Pengguna</button> -->
    </form>
    <a href="members_tambah_member.php" class="btn btn-warning">Kembali</a>
</body>
</html>