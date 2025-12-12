<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");  // Redirect to login if not admin
    exit();
}

if (!isset($_GET['id'])) {
    echo "User ID is missing.";
    exit();
}

$user_id = (int) $_GET['id'];

$success_message = null;
$error_message   = null;

// Fetch user details from the database based on the ID
$sql  = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Handle user update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $role     = $_POST['role'];
    $name     = $_POST['name'];  // Full name

    // Update user details in the database using prepared statement
    $update_sql  = "UPDATE users SET username = ?, email = ?, role = ?, name = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);

    if ($update_stmt) {
        $update_stmt->bind_param("ssssi", $username, $email, $role, $name, $user_id);

        if ($update_stmt->execute()) {
            // After successful update, re-fetch the updated user data to show on the form
            $refresh_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $refresh_stmt->bind_param("i", $user_id);
            $refresh_stmt->execute();
            $refreshed_result = $refresh_stmt->get_result();
            $user = $refreshed_result->fetch_assoc();

            $success_message = "The user information has been updated!";
        } else {
            $error_message = "Error updating user: " . $update_stmt->error;
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
    <title>Edit User | Admin | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons in messages -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                    Admin • Edit User
                </p>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
                    Edit User
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Update the user’s account details and role in the system.
                </p>
            </div>
            <a href="manage_users.php"
               class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
                ← Back to Manage Users
            </a>
        </header>

        <!-- Edit User Form Card -->
        <div class="max-w-lg bg-white p-8 shadow-md rounded-2xl border border-gray-100 mx-auto">
            <!-- Success and Error Messages -->
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 text-green-700 border border-green-300 p-4 rounded-md mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <span><?= htmlspecialchars($success_message); ?></span>
                </div>
            <?php elseif (!empty($error_message)): ?>
                <div class="bg-red-100 text-red-700 border border-red-300 p-4 rounded-md mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?= htmlspecialchars($error_message); ?></span>
                </div>
            <?php endif; ?>

            <!-- Form to Edit User -->
            <form action="edit_user.php?id=<?= (int) $user['id']; ?>" method="POST" class="space-y-5">

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        id="name"
                        value="<?= htmlspecialchars($user['name']); ?>"
                        class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        value="<?= htmlspecialchars($user['username']); ?>"
                        class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="<?= htmlspecialchars($user['email']); ?>"
                        class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select
                        name="role"
                        id="role"
                        class="w-full p-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>
                            Admin
                        </option>
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>
                            User
                        </option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between mt-4">
                    <a href="manage_users.php"
                       class="text-sm text-gray-500 hover:text-gray-700">
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-md transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
