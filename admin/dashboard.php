<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();
}

// Fetch tasks and users from the database for the dashboard overview
$tasks_query = "SELECT * FROM tasks";
$tasks_result = $conn->query($tasks_query);

$users_query = "SELECT * FROM users";
$users_result = $conn->query($users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <!-- Page Wrapper -->
    <div class="max-w-6xl mx-auto px-6 pb-10">

        <!-- Header Section -->
        <header class="mt-10 mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-blue-500 font-semibold mb-1">Admin Panel</p>
                <h1 class="text-3xl font-bold text-gray-800">
                    Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 👋
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Here’s an overview of your task management system.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="manage_tasks.php"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 shadow-md transition">
                    <i class="fas fa-tasks"></i>
                    Manage Tasks
                </a>
                <a href="manage_users.php"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 border border-gray-200 transition">
                    <i class="fas fa-users-cog"></i>
                    Manage Users
                </a>
            </div>
        </header>

        <!-- Summary Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <!-- Total Tasks -->
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center gap-4 border-l-4 border-blue-500">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Tasks</p>
                    <p class="text-2xl font-bold text-gray-800">
                        <?= $tasks_result->num_rows; ?>
                    </p>
                    <a href="manage_tasks.php" class="text-xs text-blue-600 hover:text-blue-800 font-semibold mt-1 inline-flex items-center gap-1">
                        View & manage
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center gap-4 border-l-4 border-green-500">
                <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Total Users</p>
                    <p class="text-2xl font-bold text-gray-800">
                        <?= $users_result->num_rows; ?>
                    </p>
                    <a href="manage_users.php" class="text-xs text-green-600 hover:text-green-800 font-semibold mt-1 inline-flex items-center gap-1">
                        View & manage
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Add Task -->
            <div class="bg-white rounded-2xl shadow-md p-6 flex items-center gap-4 border-l-4 border-yellow-400">
                <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-500">
                    <i class="fas fa-plus-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Quick Action</p>
                    <p class="text-lg font-semibold text-gray-800">Add New Task</p>
                    <a href="add_task.php" class="text-xs text-yellow-600 hover:text-yellow-700 font-semibold mt-1 inline-flex items-center gap-1">
                        Go to Add Task
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Two-Column Extra Info (optional, simple placeholders) -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tasks Info -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-clipboard-list text-blue-500"></i>
                    Task Management Overview
                </h2>
                <p class="text-sm text-gray-600 mb-2">
                    From here, you can create, update, assign, and delete tasks. Use the
                    <span class="font-semibold text-gray-800">Manage Tasks</span> page to control all task-related operations.
                </p>
                <p class="text-xs text-gray-500">
                    Tip: You can assign tasks to specific users and track their status (Upcoming, In Progress, Completed).
                </p>
            </div>

            <!-- Users Info -->
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-user-shield text-green-500"></i>
                    User & Role Overview
                </h2>
                <p class="text-sm text-gray-600 mb-2">
                    The <span class="font-semibold text-gray-800">Manage Users</span> section lets you view all registered users.
                    By default, new accounts are created with the <span class="font-semibold">user</span> role.
                </p>
                <p class="text-xs text-gray-500">
                    This project demonstrates role-based access control where only admins can access this dashboard and management pages.
                </p>
            </div>
        </section>

    </div>

</body>
</html>

<?php
$conn->close();
?>
