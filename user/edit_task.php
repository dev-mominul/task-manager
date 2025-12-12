<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");  // Redirect to login if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the task ID from the URL
if (isset($_GET['id'])) {
    $task_id = (int) $_GET['id'];

    // Fetch task details from the database, ensuring the task belongs to the logged-in user
    $sql  = "SELECT * FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $task_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Task not found or you don't have permission to edit it.";
        exit();
    }
} else {
    echo "Task ID is missing.";
    exit();
}

// Handle task status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title       = $_POST['title'];
    $description = $_POST['description'];
    $due_date    = $_POST['due_date'];
    $status      = $_POST['status'];

    // Update task details in the database (only if it belongs to this user)
    $update_sql  = "UPDATE tasks SET title = ?, description = ?, due_date = ?, status = ? WHERE id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssii", $title, $description, $due_date, $status, $task_id, $user_id);

    if ($update_stmt->execute()) {
        $success_message = "The task has been updated!";
        // Refresh task data for the form
        $task['title']       = $title;
        $task['description'] = $description;
        $task['due_date']    = $due_date;
        $task['status']      = $status;
    } else {
        $error_message = "Error updating task: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <!-- Main Content -->
    <div class="max-w-3xl mx-auto px-6 pb-12">
        <!-- Header -->
        <header class="pt-10 pb-6 flex items-center justify-between gap-3">
            <div>
                <p class="text-xs uppercase tracking-wide text-blue-500 font-semibold mb-1">
                    User Task
                </p>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    Edit Task
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Update the details, due date, or status of this task.
                </p>
            </div>
            <a href="dashboard.php"
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
                ← Back to Dashboard
            </a>
        </header>

        <!-- Form Card -->
        <div class="bg-white p-8 rounded-2xl shadow-md border border-gray-100">
            <!-- Success/Error Messages -->
            <?php if (isset($success_message)): ?>
                <div class="bg-green-100 text-green-700 border border-green-300 p-4 rounded-md mb-6 text-sm">
                    <strong>Success:</strong> <?= htmlspecialchars($success_message); ?>
                </div>
            <?php elseif (isset($error_message)): ?>
                <div class="bg-red-100 text-red-700 border border-red-300 p-4 rounded-md mb-6 text-sm">
                    <strong>Error:</strong> <?= htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- Edit Task Form -->
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
                        placeholder="Optional details about this task..."><?= htmlspecialchars($task['description']); ?></textarea>
                </div>

                <!-- Flex row: Due Date + Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                    <!-- Task Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Task Status</label>
                        <select
                            name="status"
                            id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <option value="pending"     <?= $task['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="in-progress" <?= $task['status'] === 'in-progress' ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed"   <?= $task['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between mt-4">
                    <a href="dashboard.php"
                       class="text-sm text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-md transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
