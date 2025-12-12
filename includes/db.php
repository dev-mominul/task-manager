<?php
// includes/db.php
$servername = "localhost";
$username = "mominul_task_management";  // Default XAMPP username
$password = "Ss@350930";      // Default XAMPP password is empty
$dbname = "mominul_task_management";  // Updated database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
