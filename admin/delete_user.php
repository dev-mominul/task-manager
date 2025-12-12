<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();
}

// Delete user
if (isset($_GET['delete'])) {
    $user_id = (int) $_GET['delete'];  // cast to int for extra safety

    if ($user_id > 0) {

        // OPTIONAL: prevent admin from deleting their own account
        // if ($user_id === (int) $_SESSION['user_id']) {
        //     echo "You cannot delete your own account while logged in as admin.";
        //     exit();
        // }

        // Delete user using prepared statement
        $sql  = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $user_id);

            if ($stmt->execute()) {
                // Redirect to manage_users.php with success message
                header("Location: manage_users.php?deleted=1");
                exit();
            } else {
                echo "Error deleting user: " . $stmt->error;
            }
        } else {
            echo "Error preparing delete statement: " . $conn->error;
        }
    } else {
        echo "Invalid user ID.";
    }
} else {
    echo "No user ID provided.";
}

$conn->close();
?>
