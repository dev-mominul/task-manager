<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Check if task ID is provided in the URL
if (isset($_GET['id'])) {
    $task_id = (int) $_GET['id']; // cast to int for extra safety

    if ($task_id > 0) {
        // Delete task from the database using prepared statement
        $sql  = "DELETE FROM tasks WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $task_id);
            if ($stmt->execute()) {
                // Redirect to manage_tasks.php with success message
                header("Location: manage_tasks.php?deleted=1");
                exit();
            } else {
                echo "Error deleting task: " . $stmt->error;
            }
        } else {
            echo "Error preparing delete statement: " . $conn->error;
        }
    } else {
        echo "Invalid task ID.";
    }
} else {
    echo "No task ID provided.";
}

$conn->close();
?>
