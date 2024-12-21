<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('../index.php');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']); 
    $role = 'admin';

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "Semua field harus diisi.";
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $error = "Username harus antara 3 dan 20 karakter.";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter.";
    } elseif ($password !== $confirm_password) { 
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $check_query = "SELECT * FROM users WHERE username = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Username sudah ada, silakan pilih username lain.";
        } else {
            // Hash password
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);

            // Siapkan dan bind untuk menambahkan user
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password_hashed, $role);
            if ($stmt->execute()) {
                $success = "Admin baru berhasil ditambahkan!";
                redirect('tampil_user_admin.php'); // Redirect ke halaman members setelah berhasil
            } else {
                $error = "Gagal menambahkan admin: " . htmlspecialchars($stmt->error);
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin</title>
</head>
<body>
    <h1>Tambah Admin</h1>

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

        <button type="submit">Tambah Admin</button>
    </form>
    <a href="members.php">Kembali</a>
</body>
</html>