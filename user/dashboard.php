<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");  // Redirect to login if not logged in
    exit();
}

// Fetch user details (including name) based on user_id from the session
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT name FROM users WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $user_name = $user['name'];  // Store user's name
} else {
    // If user not found (which shouldn't happen), redirect to login
    header("Location: ../login.php");
    exit();
}

// Fetch tasks categorized by status
// In-Progress Tasks
$sql_in_progress = "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'in-progress' ORDER BY due_date ASC";
$in_progress_result = $conn->query($sql_in_progress);

// Upcoming Tasks (Pending status)
$sql_upcoming = "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'pending' ORDER BY due_date ASC";
$upcoming_result = $conn->query($sql_upcoming);

// Completed Tasks
$sql_completed = "SELECT * FROM tasks WHERE user_id = $user_id AND status = 'completed' ORDER BY due_date ASC";
$completed_result = $conn->query($sql_completed);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 pb-12">

        <!-- Header -->
        <header class="pt-10 pb-6">
            <p class="text-xs uppercase tracking-wide text-blue-500 font-semibold mb-1">
                User Dashboard
            </p>
            <h1 class="text-3xl font-bold text-gray-800">
                Welcome, <?= htmlspecialchars($user_name); ?> 👋
            </h1>
            <p class="text-sm text-gray-500 mt-2">
                Here’s an overview of your tasks by status. Stay on top of your work!
            </p>
        </header>

        <!-- Column Titles -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="text-center md:text-left">
                <h2 class="text-lg font-semibold text-gray-700 flex items-center justify-center md:justify-start gap-2">
                    <i class="fas fa-spinner text-orange-500"></i>
                    In Progress
                </h2>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-lg font-semibold text-gray-700 flex items-center justify-center md:justify-start gap-2">
                    <i class="fas fa-clock text-blue-500"></i>
                    Upcoming
                </h2>
            </div>
            <div class="text-center md:text-left">
                <h2 class="text-lg font-semibold text-gray-700 flex items-center justify-center md:justify-start gap-2">
                    <i class="fas fa-check-circle text-green-500"></i>
                    Completed
                </h2>
            </div>
        </div>

        <!-- Task Columns -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- In-progress Tasks -->
            <div class="bg-white p-5 rounded-2xl shadow-md border border-orange-100">
                <?php if ($in_progress_result->num_rows > 0): ?>
                    <?php while ($task = $in_progress_result->fetch_assoc()): ?>
                        <div class="mb-5 pb-4 border-b border-gray-100 last:border-b-0 last:mb-0 last:pb-0">
                            <div class="flex items-start gap-2 mb-1">
                                <span class="mt-1 w-2.5 h-2.5 rounded-full bg-orange-500"></span>
                                <h3 class="text-base font-semibold text-gray-800">
                                    <?= htmlspecialchars($task['title']); ?>
                                </h3>
                            </div>
                            <?php if (!empty($task['description'])): ?>
                                <p class="text-xs text-gray-500 mb-1">
                                    <?= nl2br(htmlspecialchars($task['description'])); ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-600 mb-2">
                                <span class="font-medium">Due:</span>
                                <?= htmlspecialchars($task['due_date']); ?>
                            </p>
                            <a href="edit_task.php?id=<?= $task['id']; ?>"
                               class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800">
                                Edit Task
                                <i class="fas fa-edit ml-1 text-[11px]"></i>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center text-sm text-gray-500 mt-4">
                        No in-progress tasks.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Upcoming Tasks -->
            <div class="bg-white p-5 rounded-2xl shadow-md border border-blue-100">
                <?php if ($upcoming_result->num_rows > 0): ?>
                    <?php while ($task = $upcoming_result->fetch_assoc()): ?>
                        <div class="mb-5 pb-4 border-b border-gray-100 last:border-b-0 last:mb-0 last:pb-0">
                            <div class="flex items-start gap-2 mb-1">
                                <span class="mt-1 w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                                <h3 class="text-base font-semibold text-gray-800">
                                    <?= htmlspecialchars($task['title']); ?>
                                </h3>
                            </div>
                            <?php if (!empty($task['description'])): ?>
                                <p class="text-xs text-gray-500 mb-1">
                                    <?= nl2br(htmlspecialchars($task['description'])); ?>
                                </p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-600 mb-2">
                                <span class="font-medium">Due:</span>
                                <?= htmlspecialchars($task['due_date']); ?>
                            </p>
                            <a href="edit_task.php?id=<?= $task['id']; ?>"
                               class="inline-flex items-center text-xs font-semibold text-blue-600 hover:text-blue-800">
                                View / Edit
                                <i class="fas fa-arrow-right ml-1 text-[11px]"></i>
                            </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center text-sm text-gray-500 mt-4">
                        No upcoming tasks.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Completed Tasks -->
            <div class="bg-white p-5 rounded-2xl shadow-md border border-green-100">
                <?php if ($completed_result->num_rows > 0): ?>
                    <?php while ($task = $completed_result->fetch_assoc()): ?>
                        <div class="mb-5 pb-4 border-b border-gray-100 last:border-b-0 last:mb-0 last:pb-0">
                            <div class="flex items-start gap-2 mb-1">
                                <span class="mt-1 w-2.5 h-2.5 rounded-full bg-green-500"></span>
                                <h3 class="text-base font-semibold text-gray-800 line-through">
                                    <?= htmlspecialchars($task['title']); ?>
                                </h3>
                            </div>
                            <p class="text-xs text-gray-600">
                                <span class="font-medium">Completed on:</span>
                                <?= htmlspecialchars($task['due_date']); ?>
                            </p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center text-sm text-gray-500 mt-4">
                        No completed tasks yet.
                    </p>
                <?php endif; ?>
            </div>

        </div>
    </div>

</body>
</html>

<?php
$conn->close();
?>
