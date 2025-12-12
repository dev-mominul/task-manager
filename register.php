<?php
session_start();

// Redirect logged-in users
if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        header("Location: user/dashboard.php");
        exit();
    }
}

include('includes/db.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $username = $_POST['username'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = 'user';

    // Check if username/email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Username or Email is already taken.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $username, $email, $password, $role);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['role']     = $role;
            $_SESSION['user_id']  = $conn->insert_id;

            header("Location: user/dashboard.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 flex items-center justify-center px-4">

    <!-- Registration Card -->
    <div class="bg-white/95 backdrop-blur-sm shadow-2xl rounded-2xl p-8 sm:p-10 w-full max-w-xl">

        <!-- Header -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Create an Account</h1>
            <p class="text-gray-500 text-sm mt-1">
                Register to begin managing your tasks.
            </p>
        </div>

        <!-- Error Message -->
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <strong>Error:</strong> <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form action="register.php" method="POST" class="space-y-5">

            <!-- Full Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2">
                    <svg class="w-5 h-5 text-gray-400 mr-2" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                    </svg>
                    <input type="text" name="name" required placeholder="Enter your full name" class="w-full outline-none text-sm">
                </div>
            </div>

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.7 0 5-2.3 5-5s-2.3-5-5-5-5 2.3-5 5 2.3 5 5 5zm0 2c-3.3 0-10 1.7-10 5v3h20v-3c0-3.3-6.7-5-10-5z"/>
                    </svg>
                    <input type="text" name="username" required placeholder="Choose a username" class="w-full outline-none text-sm">
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.11 0-2-.9-2-2V6c0-1.1.89-2 2-2zm0 2v.01L12 13l8-6.99V6H4zm0 12h16V9l-8 7-8-7v9z"/>
                    </svg>
                    <input type="email" name="email" required placeholder="Enter your email" class="w-full outline-none text-sm">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <div class="flex items-center border border-gray-300 rounded-lg px-3 py-2">
                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17 8V7c0-2.8-2.2-5-5-5S7 4.2 7 7v1H5v14h14V8h-2zm-5 9c-1.1 0-2-.9-2-2 0-.7.4-1.4 1-1.7V12c0-.6.5-1 1-1s1 .4 1 1v1.3c.6.3 1 1 1 1.7 0 1.1-.9 2-2 2z"/>
                    </svg>
                    <input type="password" name="password" required placeholder="Create a password" class="w-full outline-none text-sm">
                </div>
            </div>

            <!-- Register Button -->
            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold shadow-md transition">
                Register
            </button>
        </form>

        <!-- Footer Links -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Already have an account?
                <a href="login.php" class="text-blue-600 font-medium hover:underline">Login here</a>
            </p>

            <a href="index.php" class="text-gray-400 hover:text-gray-600 text-xs hover:underline mt-2 block">
                ← Back to Home
            </a>
        </div>

    </div>

</body>
</html>
