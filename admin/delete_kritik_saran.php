<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('/index.php');
}

$id = $_GET['id'];
$query = "DELETE FROM kritik_saran WHERE id_kritiksaran = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
if (mysqli_stmt_execute($stmt)) {
    redirect('../admin/kritik_saran.php');
} else {
    echo "Gagal menghapus member!";
}
mysqli_stmt_close($stmt);
