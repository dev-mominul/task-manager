<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();
}

// Fetch all users from the database including the 'name' field (Full Name)
$sql    = "SELECT * FROM users";
$result = $conn->query($sql);

// Check for user deletion success message (?deleted=1)
$user_deleted = isset($_GET['deleted']) && $_GET['deleted'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome (for the success icon) -->
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
                    Admin • User Management
                </p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Manage Users
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    View all registered users, their roles, and manage their accounts.
                </p>
            </div>
            <a href="dashboard.php"
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
                ← Back to Dashboard
            </a>
        </header>

        <!-- Success Message for User Deletion -->
        <?php if ($user_deleted): ?>
            <div class="bg-green-100 text-green-700 border border-green-300 px-4 py-3 rounded-md mb-6 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                <span>The user has been deleted successfully.</span>
            </div>
        <?php endif; ?>

        <!-- User Table -->
        <div class="bg-white shadow-md rounded-2xl border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-blue-600 text-white text-xs uppercase tracking-wide">
                        <tr>
                            <th class="py-3 px-4 sm:px-6">Username</th>
                            <th class="py-3 px-4 sm:px-6">Full Name</th>
                            <th class="py-3 px-4 sm:px-6">Email</th>
                            <th class="py-3 px-4 sm:px-6">Role</th>
                            <th class="py-3 px-4 sm:px-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                    $user_id   = (int) $row['id'];
                                    $username  = htmlspecialchars($row['username']);
                                    $full_name = htmlspecialchars($row['name']);
                                    $email     = htmlspecialchars($row['email']);
                                    $role      = htmlspecialchars($row['role']);
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 sm:px-6 align-top font-semibold text-gray-800">
                                        <?= $username; ?>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top text-gray-700">
                                        <?= $full_name ?: '—'; ?>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top text-gray-700">
                                        <?= $email; ?>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-xs font-semibold
                                            <?= $role === 'admin'
                                                ? 'bg-purple-50 text-purple-700 border-purple-200'
                                                : 'bg-gray-50 text-gray-700 border-gray-200'; ?>">
                                            <i class="fas <?= $role === 'admin' ? 'fa-user-shield' : 'fa-user'; ?> mr-1"></i>
                                            <?= ucfirst($role); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 sm:px-6 align-top">
                                        <div class="flex flex-wrap items-center gap-2 text-xs">
                                            <a href="edit_user.php?id=<?= $user_id; ?>"
                                               class="inline-flex items-center gap-1 px-2 py-1 rounded-md border border-blue-200 text-blue-600 hover:bg-blue-50">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>
                                            <a href="delete_user.php?delete=<?= $user_id; ?>"
                                               onclick="return confirm('Are you sure you want to delete this user?');"
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
                                <td colspan="5" class="py-6 px-4 sm:px-6 text-center text-sm text-gray-500">
                                    No users found.
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
