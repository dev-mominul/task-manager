<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="bg-gradient-to-r from-blue-700 to-blue-800 shadow-lg">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <!-- Logo / Title -->
        <a href="/diu/6/index.php" class="text-white text-2xl font-bold tracking-wide hover:text-blue-200 transition">
            Task Manager
        </a>

        <!-- Mobile Menu Button -->
        <button id="nav-toggle" class="lg:hidden text-white focus:outline-none text-2xl">
            ☰
        </button>

        <!-- Menu Items -->
        <div id="nav-menu"
             class="hidden lg:flex flex-col lg:flex-row items-start lg:items-center gap-4 lg:gap-6 absolute lg:static top-16 left-0 w-full lg:w-auto bg-blue-800 lg:bg-transparent px-6 lg:px-0 py-4 lg:py-0 shadow-lg lg:shadow-none">

            <?php if (isset($_SESSION['username'])): ?>

                <!-- Role-based nav -->
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="/diu/6/admin/dashboard.php"
                       class="text-gray-200 hover:text-white font-medium px-4 py-2 rounded lg:hover:bg-blue-600 transition">
                        Admin Dashboard
                    </a>

                    <a href="/diu/6/admin/manage_tasks.php"
                       class="text-gray-200 hover:text-white font-medium px-4 py-2 rounded lg:hover:bg-blue-600 transition">
                        Manage Tasks
                    </a>

                    <a href="/diu/6/admin/manage_users.php"
                       class="text-gray-200 hover:text-white font-medium px-4 py-2 rounded lg:hover:bg-blue-600 transition">
                        Manage Users
                    </a>

                <?php else: ?>
                    <a href="/diu/6/user/dashboard.php"
                       class="text-gray-200 hover:text-white font-medium px-4 py-2 rounded lg:hover:bg-blue-600 transition">
                        Dashboard
                    </a>
                <?php endif; ?>

                <!-- Logout -->
                <a href="/diu/6/logout.php"
                   class="bg-red-500 hover:bg-red-600 text-white font-medium px-5 py-2 rounded-full shadow transition">
                    Logout
                </a>

            <?php else: ?>
                <!-- Not logged in -->
                <a href="/diu/6/login.php"
                   class="text-gray-200 hover:text-white font-medium px-4 py-2 rounded lg:hover:bg-blue-600 transition">
                    Login
                </a>

                <a href="/diu/6/register.php"
                   class="bg-white text-blue-700 font-semibold px-5 py-2 rounded-full hover:bg-blue-50 shadow transition">
                    Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Mobile toggle script -->
<script>
    const navToggle = document.getElementById('nav-toggle');
    const navMenu   = document.getElementById('nav-menu');

    navToggle.addEventListener('click', () => {
        navMenu.classList.toggle('hidden');
    });
</script>
