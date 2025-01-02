<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('../index.php');
}

// Check if the username is provided in the URL
if (isset($_GET['username'])) {
    $username = mysqli_real_escape_string($conn, $_GET['username']);

    // Prepare the SQL statement to delete the user from the users table
    $query = "DELETE FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect back to the admin management page with a success message
        redirect('../admin/tampil_delete_admin.php?status=success');
    } else {
        // Redirect back with an error message
        redirect('../admin/tampil_delete_admin.php?status=error');
    }

    // Close the statement
    $stmt->close();
} else {
    // If no username is provided, redirect back to the admin management page
    redirect('../admin/tampil_delete_admin.php');
}

// Close the database connection
$conn->close();
?>