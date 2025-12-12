<?php
session_start();  // Start the session

include('../includes/db.php'); // Include the database connection

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();
}

// Ensure we have a task id
if (!isset($_GET['id'])) {
    echo "Task ID is missing.";
    exit();
}

$task_id = (int) $_GET['id'];

// Fetch task details securely
$sql  = "SELECT * FROM tasks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $task = $result->fetch_assoc();
} else {
    echo "Task not found.";
    exit();
}

// Fetch all users (you may restrict to role='user' if you prefer)
$user_sql    = "SELECT id, username FROM users";
$user_result = $conn->query($user_sql);
$users       = [];
while ($row = $user_result->fetch_assoc()) {
    $users[] = $row;
}

$success_message = null;
$error_message   = null;

// Handle task update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title         = $_POST['title'];
    $description   = $_POST['description'];
    $due_date      = $_POST['due_date'];
    $status        = $_POST['status'];
    $assigned_user = $_POST['assigned_user'];

    // Update the task with new data using prepared statement
    $update_sql  = "UPDATE tasks 
                    SET title = ?, description = ?, due_date = ?, status = ?, user_id = ? 
                    WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);

    if ($update_stmt) {
        $update_stmt->bind_param("ssssii", $title, $description, $due_date, $status, $assigned_user, $task_id);

        if ($update_stmt->execute()) {
            $success_message = "The task has been successfully updated.";

            // Refresh local $task array so the form reflects current values
            $task['title']       = $title;
            $task['description'] = $description;
            $task['due_date']    = $due_date;
            $task['status']      = $status;
            $task['user_id']     = $assigned_user;
        } else {
            $error_message = "Error updating task: " . $update_stmt->error;
        }
    } else {
        $error_message = "Error preparing update statement: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task | Admin | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <!-- Main Wrapper -->
    <div class="max-w-4xl mx-auto px-6 pb-12">

        <!-- Page Header -->
        <header class="pt-10 pb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-blue-500 font-semibold mb-1">
                    Admin • Edit Task
                </p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Edit Task
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Modify the task details, status, or assigned user.
                </p>
            </div>
            <a href="manage_tasks.php"
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
                ← Back to Manage Tasks
            </a>
        </header>

        <!-- Edit Task Form Card -->
        <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
            <!-- Success and Error Messages -->
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 text-green-700 border border-green-300 p-4 rounded-md mb-6 text-sm">
                    <?= htmlspecialchars($success_message); ?>
                </div>
            <?php elseif (!empty($error_message)): ?>
                <div class="bg-red-100 text-red-700 border border-red-300 p-4 rounded-md mb-6 text-sm">
                    <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form action="edit_task.php?id=<?= (int) $task['id']; ?>" method="POST" class="space-y-5">

                <!-- Task Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="<?= htmlspecialchars($task['title']); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <!-- Task Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Task Description</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Optional details about the task..."><?= htmlspecialchars($task['description']); ?></textarea>
                </div>

                <!-- Two-column row: Due Date & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input
                            type="date"
                            name="due_date"
                            id="due_date"
                            value="<?= htmlspecialchars($task['due_date']); ?>"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required
                        >
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select
                            name="status"
                            id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="pending"     <?= $task['status'] === 'pending'     ? 'selected' : ''; ?>>Pending</option>
                            <option value="in-progress" <?= $task['status'] === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed"   <?= $task['status'] === 'completed'   ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Assign User -->
                <div>
                    <label for="assigned_user" class="block text-sm font-medium text-gray-700 mb-1">Assign User</label>
                    <select
                        name="assigned_user"
                        id="assigned_user"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Select User</option>
                        <?php foreach ($users as $user): ?>
                            <option
                                value="<?= (int) $user['id']; ?>"
                                <?= $user['id'] == $task['user_id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($user['username']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between mt-4">
                    <a href="manage_tasks.php"
                       class="text-sm text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition">
                        Update Task
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
