<?php
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $sql = "INSERT INTO tasks (description, due_date, status) VALUES ('$description', '$due_date', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "New task added successfully";
        header("Location: index.php"); // Redirect to homepage
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
</head>
<body>
    <h1>Add a New Task</h1>

    <form action="add_task.php" method="POST">
        <label for="description">Task Description:</label>
        <input type="text" name="description" required><br>

        <label for="due_date">Due Date:</label>
        <input type="date" name="due_date" required><br>

        <label for="status">Status:</label>
        <select name="status">
            <option value="pending">Pending</option>
            <option value="in-progress">In Progress</option>
            <option value="completed">Completed</option>
        </select><br>

        <button type="submit">Add Task</button>
    </form>
</body>
</html>
