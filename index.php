<?php 
// Start session
session_start();

// Include database connection
include('includes/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; }
        h1, h2, h3, h4 { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <?php include('includes/navbar.php'); ?>

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700">
        <!-- Background decoration -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-24 -left-24 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-16 w-80 h-80 rounded-full bg-indigo-400/20 blur-3xl"></div>
        </div>

        <div class="relative max-w-6xl mx-auto px-6 py-20 sm:py-24 lg:py-28 flex flex-col lg:flex-row items-center gap-12">
            <!-- Text -->
            <div class="flex-1 text-center lg:text-left">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-blue-100 border border-white/20 mb-4">
                    CSE415 • Task Management System
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight">
                    Stay organized. <span class="text-yellow-300">Every task.</span> Every day.
                </h1>
                <p class="text-base sm:text-lg text-blue-100 mb-8 max-w-xl">
                    A role-based Task Manager for admins and users — built with PHP, MySQL, and Tailwind CSS
                    as part of the Web Engineering Lab.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                    <a href="login.php" class="inline-flex items-center justify-center px-7 py-3 rounded-full text-sm sm:text-base font-semibold bg-white text-blue-700 shadow-lg hover:bg-blue-50 transition">
                        Login to Dashboard
                    </a>
                    <a href="register.php" class="inline-flex items-center justify-center px-7 py-3 rounded-full text-sm sm:text-base font-semibold border border-blue-100 text-white hover:bg-white/10 transition">
                        Create an Account
                    </a>
                </div>

                <div class="mt-6 flex flex-wrap items-center justify-center lg:justify-start gap-4 text-xs sm:text-sm text-blue-100/80">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        Real login & role-based access
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-300"></span>
                        Full CRUD on tasks
                    </div>
                </div>
            </div>

            <!-- Right side “card” -->
            <div class="flex-1 w-full">
                <div class="bg-white/95 backdrop-blur shadow-2xl rounded-2xl p-6 sm:p-8 max-w-md mx-auto">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Overview</h3>
                    <p class="text-sm text-gray-600 mb-6">
                        The system supports both <span class="font-semibold">admins</span> and <span class="font-semibold">users</span>.
                        Admins can manage users and tasks, while users can view and update their assigned tasks.
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div class="p-4 rounded-xl bg-blue-50">
                            <p class="text-xs uppercase tracking-wide text-blue-500 font-semibold mb-1">Admins</p>
                            <p class="font-medium text-gray-800">Manage tasks & users</p>
                        </div>
                        <div class="p-4 rounded-xl bg-green-50">
                            <p class="text-xs uppercase tracking-wide text-green-500 font-semibold mb-1">Users</p>
                            <p class="font-medium text-gray-800">View & update tasks</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>Built with PHP + MySQL</span>
                        <span>Tailwind UI demo</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About This Task Manager -->
    <section class="py-16">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">About This Task Manager</h2>
            <p class="text-lg text-gray-600 mb-3">
                This Task Manager is a course project for <span class="font-semibold">CSE415: Web Engineering Lab</span>.
            </p>
            <p class="text-base text-gray-600">
                It demonstrates a complete dynamic web application with <span class="font-semibold">login authentication</span>,
                <span class="font-semibold">role-based access control</span>, and <span class="font-semibold">CRUD operations</span> on tasks.
                Users can add, track, and update their tasks seamlessly in a clean and intuitive interface.
            </p>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">How It Works</h2>
            <p class="text-lg text-gray-600 mb-10">
                The Task Manager separates responsibilities between admins and users, making it simple yet powerful.
                Here’s the basic workflow:
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="bg-blue-50 p-8 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Add New Tasks</h3>
                    <p class="text-gray-600 text-sm">
                        Admins create tasks with titles, descriptions, deadlines, and priorities to organize work clearly.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-green-50 p-8 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-green-600 text-white flex items-center justify-center text-2xl font-bold">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Assign & Track</h3>
                    <p class="text-gray-600 text-sm">
                        Tasks are assigned to users. Each user can see their tasks categorized as Upcoming, In Progress, or Completed.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-yellow-50 p-8 rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transition">
                    <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-yellow-500 text-white flex items-center justify-center text-2xl font-bold">
                        3
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Update & Complete</h3>
                    <p class="text-gray-600 text-sm">
                        Users update task status as they work. Admins can monitor progress and keep everyone on schedule.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Features -->
    <section id="key-features" class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-semibold text-gray-800 mb-4">Key Features</h2>
            <p class="text-lg text-gray-600 mb-10">
                The system supports both <span class="font-semibold">admins</span> and <span class="font-semibold">users</span> with different capabilities.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-left">
                <!-- Admin Features -->
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a5 5 0 015 5v1h1a3 3 0 013 3v4a7 7 0 01-7 7h-4a7 7 0 01-7-7v-4a3 3 0 013-3h1V7a5 5 0 015-5z"/></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Admin Features</h3>
                    </div>
                    <ul class="space-y-3 text-gray-600 text-sm">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Access an admin dashboard with an overview of users and tasks.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Perform full CRUD operations on tasks (Create, Read, Update, Delete).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Assign tasks to specific users and monitor their progress.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-blue-500"></span>
                            <span>Manage user accounts and roles (admin/user).</span>
                        </li>
                    </ul>
                </div>

                <!-- User Features -->
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center text-white">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a5 5 0 00-5 5v1H6a3 3 0 00-3 3v4a7 7 0 007 7h4a7 7 0 007-7v-4a3 3 0 00-3-3h-1V7a5 5 0 00-5-5z"/></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">User Features</h3>
                    </div>
                    <ul class="space-y-3 text-gray-600 text-sm">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-green-500"></span>
                            <span>View a personalized user dashboard with assigned tasks.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-green-500"></span>
                            <span>See tasks categorized by status (Upcoming, In Progress, Completed).</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-green-500"></span>
                            <span>Update task status and details as work progresses.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 rounded-full bg-green-500"></span>
                            <span>Keep track of deadlines and priorities to stay organized.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-blue-700 text-white text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-3xl sm:text-4xl font-semibold mb-4">Ready to Get Started?</h2>
            <p class="text-lg text-blue-100 mb-8">
                Create an account and start managing your tasks with a clean, role-based Task Management System.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="register.php" class="px-8 py-3 rounded-full bg-white text-blue-700 font-semibold text-base shadow-lg hover:bg-blue-50 transition">
                    Sign Up Now
                </a>
                <a href="login.php" class="px-8 py-3 rounded-full border border-blue-100 text-white font-semibold text-base hover:bg-white/10 transition">
                    I already have an account
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

</body>
</html>

<?php
$conn->close();  // Close the database connection
?>
