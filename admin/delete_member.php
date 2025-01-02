<?php
include '../includes/db.php';
include '../includes/functions.php';
is_logged_in();
if (!is_admin()) {
    redirect('/index.php');
}

// Check if the member ID is provided in the URL
if (isset($_GET['id'])) {
    $member_id = intval($_GET['id']);

    // Prepare the SQL statement to get the username from the members table
    $query = "SELECT username FROM members WHERE id_member = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $username = $row['username'];
    $stmt->close();

    // Prepare the SQL statement to delete the member from the members table
    $query = "DELETE FROM members WHERE id_member = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $member_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Prepare the SQL statement to delete the user from the users table
        $query = "DELETE FROM users WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to the members management page with a success message
            redirect('/admin/members_delete_member.php?status=success');
        } else {
            // Redirect back with an error message
            redirect('/admin/members_delete_member.php?status=error');
        }
    } else {
        // Redirect back with an error message
        redirect('/admin/members_delete_member.php?status=error');
    }

    // Close the statement
    $stmt->close();
} else {
    // If no ID is provided, redirect back to the members management page
    redirect('/admin/members_delete_member.php');
}

// Close the database connection
$conn->close();
?>