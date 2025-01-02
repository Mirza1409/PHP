<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('../index.php');
}

$error = '';
$success = '';

// Ambil username dari parameter GET
$username = $_GET['username'] ?? '';

// Validasi jika username tidak ada
if (empty($username)) {
    $error = "Username tidak valid.";
}

// Ambil password lama dari database
$old_password_query = "SELECT password FROM users WHERE username = ?";
$old_password_stmt = $conn->prepare($old_password_query);
$old_password_stmt->bind_param("s", $username);
$old_password_stmt->execute();
$old_password_stmt->bind_result($old_password_hashed);
$old_password_stmt->fetch();
$old_password_stmt->close();

// Proses ganti password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validasi input
    if (empty($new_password) || empty($confirm_password)) {
        $error = "Semua field harus diisi.";
    } elseif (strlen($new_password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif (password_verify($new_password, $old_password_hashed)) { // Bandingkan dengan password lama
        $error = "Password baru tidak boleh sama dengan password lama.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Hash password baru
        $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password di database
        $update_query = "UPDATE users SET password = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $new_password_hashed, $username);

        if ($update_stmt->execute()) {
            $success = "Password berhasil diubah!";
        } else {
            $error = "Gagal mengubah password: " . htmlspecialchars($update_stmt->error);
        }
        $update_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Password Member</title>
    <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link href="../template/ganti_password_member.css" rel="stylesheet">
    
</head>
<body>
    <h1>Ganti Password Member</h1>

    <?php if (!empty($error)): ?>
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="color: green;"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" readonly required><br>

        <label for="new_password">Password Baru:</label><br>
        <input type="password" name="new_password" id="new_password" required><br>

        <label for="confirm_password">Konfirmasi Password Baru:</label><br>
        <input type="password" name="confirm_password" id="confirm_password" required><br>

        <button type="submit">Ganti Password</button>
    </form>
    <a href="members_ganti_password.php" class="btn btn-warning">Kembali</a>
</body>
</html>