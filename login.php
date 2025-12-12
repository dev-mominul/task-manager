<?php
session_start();  // Start the session

// 🔹 If the user is already logged in, redirect based on role
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");  // Redirect admin to the dashboard
        exit();
    } else {
        header("Location: user/dashboard.php");  // Redirect user to the dashboard
        exit();
    }
}

include('includes/db.php');

// 🔹 Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Login successful, store user info in session
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];    // Store role in session
            $_SESSION['user_id'] = $row['id'];   // Store user ID in session

            // Redirect to the appropriate page based on the user role
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin/dashboard.php");  // Redirect admin to the dashboard
                exit();
            } else {
                header("Location: user/dashboard.php");   // Redirect user to the dashboard
                exit();
            }
        } else {
            $error_message = "Incorrect password!";
        }
    } else {
        $error_message = "User not found!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 flex items-center justify-center px-4">

    <!-- Card -->
    <div class="bg-white/95 backdrop-blur-sm shadow-2xl rounded-2xl px-8 py-10 w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-500 text-sm mt-1">Login to your Task Manager account</p>
        </div>

        <!-- Error Message -->
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <strong class="font-semibold">Error:</strong> <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST" class="space-y-5">
            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                    </svg>
                    <input
                        type="text"
                        name="username"
                        id="username"
                        class="w-full outline-none text-sm"
                        placeholder="Enter your username"
                        required
                        autocomplete="username"
                    >
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2 focus-within:ring-2 focus-within:ring-blue-500">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 8V7c0-2.8-2.2-5-5-5S7 4.2 7 7v1H5v14h14V8h-2zm-5 9c-1.1 0-2-.9-2-2 0-.7.4-1.4 1-1.7V12c0-.6.5-1 1-1s1 .4 1 1v1.3c.6.3 1 1 1 1.7 0 1.1-.9 2-2 2z"/>
                    </svg>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="w-full outline-none text-sm"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                </div>
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold text-sm shadow-md transition">
                Login
            </button>
        </form>

        <!-- Links -->
        <div class="mt-6 text-center space-y-2">
            <p class="text-sm text-gray-600">
                Don't have an account?
                <a href="register.php" class="text-blue-600 hover:underline font-medium">Create an account</a>
            </p>
            <a href="index.php" class="text-gray-400 hover:text-gray-600 text-xs hover:underline">
                ← Back to Home
            </a>
        </div>
    </div>

</body>
</html>
