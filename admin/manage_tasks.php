<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();
}

// Fetch all tasks from the database along with assigned users
$sql    = "SELECT tasks.*, users.username AS assigned_user FROM tasks LEFT JOIN users ON tasks.user_id = users.id";
$result = $conn->query($sql);

// Check for task deletion success message
$task_deleted = isset($_GET['deleted']) && $_GET['deleted'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks | Admin | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include FontAwesome for Icons -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <?php include('../includes/navbar.php'); ?>

    <!-- Main Wrapper -->
    <div class="max-w-6xl mx-auto px-6 pb-12">

        <!-- Page Header -->
        <header class="pt-10 pb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-blue-500 font-semibold mb-1">
                    Admin • Task Management
                </p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Manage Tasks
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    View, edit, or delete tasks assigned to users in the system.
                </p>
            </div>
            <a href="add_task.php"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 shadow-md transition">
                <i class="fas fa-plus-circle"></i>
                Add New Task
            </a>
        </header>

        <!-- Success Message for Task Deletion -->
        <?php if ($task_deleted): ?>
            <div class="bg-green-100 text-green-700 border border-green-300 px-4 py-3 rounded-md mb-6 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>Task deleted successfully.</span>
            </div>
        <?php endif; ?>

        <!-- Task Table -->
        <div class="bg-white shadow-md rounded-2xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-blue-600 text-white text-xs uppercase tracking-wide">
                        <tr>
                            <th class="py-3 px-4 sm:px-6">Task ID</th>
                            <th class="py-3 px-4 sm:px-6">Title</th>
                            <th class="py-3 px-4 sm:px-6">Due Date</th>
                            <th class="py-3 px-4 sm:px-6">Status</th>
                            <th class="py-3 px-4 sm:px-6">Assigned User</th>
                            <th class="py-3 px-4 sm:px-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>

                                <?php
                                // Status badge style
                                $status_color_classes = '';
                                $status_icon          = '';

                                switch ($row['status']) {
                                    case 'in-progress':
                                        $status_color_classes = 'bg-orange-50 text-orange-700 border-orange-200';
                                        $status_icon          = 'fas fa-spinner';
                                        break;
                                    case 'completed':
                                        $status_color_classes = 'bg-green-50 text-green-700 border-green-200';
                                        $status_icon          = 'fas fa-check-circle';
                                        break;
                                    case 'pending':
                                    default:
                                        $status_color_classes = 'bg-blue-50 text-blue-700 border-blue-200';
                                        $status_icon          = 'fas fa-clock';
                                        break;
                                }

                                $task_id       = (int) $row['id'];
                                $task_title    = htmlspecialchars($row['title']);
                                $task_due_date = htmlspecialchars($row['due_date']);
                                $task_status   = htmlspecialchars($row['status']);
                                $assigned_user = $row['assigned_user'] ? htmlspecialchars($row['assigned_user']) : '—';
                                ?>

                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 sm:px-6 align-top">
                                        <?= $task_id; ?>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top">
                                        <p class="font-semibold text-gray-800">
                                            <?= $task_title; ?>
                                        </p>
                                        <?php if (!empty($row['description'])): ?>
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                                <?= htmlspecialchars($row['description']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top text-gray-700">
                                        <?= $task_due_date; ?>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border text-xs font-semibold <?= $status_color_classes; ?>">
                                            <i class="<?= $status_icon; ?>"></i>
                                            <span class="capitalize"><?= $task_status; ?></span>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top text-gray-700">
                                        <?= $assigned_user; ?>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top">
                                        <div class="flex flex-wrap items-center gap-2 text-xs">
                                            <a href="edit_task.php?id=<?= $task_id; ?>"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded-md border border-blue-200 text-blue-600 hover:bg-blue-50">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            <a href="delete_task.php?id=<?= $task_id; ?>&delete=1"
                                               onclick="return confirm('Are you sure you want to delete this task?');"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded-md border border-red-200 text-red-600 hover:bg-red-50">
                                                <i class="fas fa-trash-alt"></i>
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>

                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-6 px-4 sm:px-6 text-center text-sm text-gray-500">
                                    No tasks found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>

<?php
$conn->close();
?>
