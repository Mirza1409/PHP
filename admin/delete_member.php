<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('/index.php');
}

$id = $_GET['id'];
$username = '';

// Memastikan ID valid
if (isset($id) && is_numeric($id)) {
    // Ambil username dari database berdasarkan ID
    $query_username = "SELECT username FROM members WHERE id_member = ?";
    $stmt_username = mysqli_prepare($conn, $query_username);
    mysqli_stmt_bind_param($stmt_username, 'i', $id);
    mysqli_stmt_execute($stmt_username);
    mysqli_stmt_bind_result($stmt_username, $username);
    mysqli_stmt_fetch($stmt_username);
    mysqli_stmt_close($stmt_username);

    // Menampilkan konfirmasi sebelum menghapus
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            // Memulai transaksi
            mysqli_begin_transaction($conn);
            try {
                // Menghapus user terkait dari tabel users
                // Ambil username dari tabel members berdasarkan id_member
                $stmt_member = $conn->prepare("DELETE FROM members WHERE id_member = ?");
                $stmt_member->bind_param("i", $id);
                $stmt_member->execute();

                // Menghapus user terkait dari tabel users
                $stmt_user = $conn->prepare("DELETE FROM users WHERE username = ?");
                $stmt_user->bind_param("s", $username);
                $stmt_user->execute();

                // Commit transaksi jika kedua penghapusan berhasil
                mysqli_commit($conn);
                redirect('members.php');
            } catch (Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                mysqli_rollback($conn);
                echo "Gagal menghapus member: " . htmlspecialchars($e->getMessage());
            }

            // Menutup statement
            $stmt_member->close();
            $stmt_user->close();
        } else {
            // Jika tidak dikonfirmasi, redirect kembali
            redirect('members.php');
        }
    }
} else {
    echo "ID tidak valid.";
    exit; // Menghentikan eksekusi jika ID tidak valid
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Hapus Member</title>
</head>
<body>
    <h2>Konfirmasi Hapus Member</h2>
    <p>Apakah Anda yakin ingin menghapus member dengan ID: <?= htmlspecialchars($id) ?> dan Username: <?= htmlspecialchars($username) ?>?</p>
    <form method="post">
        <button type="submit" name="confirm" value="yes">Ya, Hapus</button>
        <a href="members.php">Batal</a>
    </form>
</body>
</html>