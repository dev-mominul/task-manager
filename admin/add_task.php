<?php
session_start();
include('../includes/db.php'); // Include the database connection

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();
}

$success_message = null;
$error_message   = null;

// Handle task creation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = $_POST['title'];        // Task Title
    $description = $_POST['description'];  // Task Description (optional)
    $due_date    = $_POST['due_date'];
    $status      = $_POST['status'];
    $user_id     = $_POST['user_id'];      // User selected to assign the task to

    // Insert task into database using prepared statement
    $sql  = "INSERT INTO tasks (title, description, due_date, status, user_id) 
             VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssi", $title, $description, $due_date, $status, $user_id);
        if ($stmt->execute()) {
            $success_message = "The task has been successfully added.";
        } else {
            $error_message = "Error inserting task: " . $stmt->error;
        }
    } else {
        $error_message = "Error preparing statement: " . $conn->error;
    }
}

// Fetch all users (role = 'user') to assign tasks to
$users = [];
$users_sql    = "SELECT id, username FROM users WHERE role = 'user'";
$users_result = $conn->query($users_sql);

if ($users_result && $users_result->num_rows > 0) {
    while ($user = $users_result->fetch_assoc()) {
        $users[] = $user;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task | Admin | Task Manager</title>
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
                    Admin • Task Management
                </p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Add New Task
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Create a new task and assign it to a user in the system.
                </p>
            </div>
            <a href="dashboard.php"
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
                ← Back to Dashboard
            </a>
        </header>

        <!-- Form Card -->
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

            <form action="add_task.php" method="POST" class="space-y-5">

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter task title"
                        required
                    >
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        name="description"
                        id="description"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Optional: add more details about this task"></textarea>
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
                            <option value="pending">Pending</option>
                            <option value="in-progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Assign to User -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Assign to User</label>
                    <select
                        name="user_id"
                        id="user_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="">Select User</option>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= (int) $user['id']; ?>">
                                    <?= htmlspecialchars($user['username']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">No users available</option>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition">
                        Add Task
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
