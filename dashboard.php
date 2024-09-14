<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'db_connection.php'; // Database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Payroll System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-toggle" onclick="toggleSidebar()">
            <span>&#9776;</span>
        </div>
        <h2>Menu</h2>
        <a href="dashboard.php?page=dashboard">Dashboard</a>
        <a href="dashboard.php?page=employees">Manage Employees</a>
        <a href="dashboard.php?page=payroll">Payroll</a>
        <a href="dashboard.php?page=reports">Reports</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="content" id="content">
        <h1>Dashboard</h1>
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
        switch ($page) {
            case 'employees':
                include 'manage_employees.php';
                break;
            case 'payroll':
                include 'manage_payroll.php';
                break;
            case 'reports':
                include 'reports.php';
                break;
            default:
                echo "<p>Welcome to the Payroll System Dashboard!</p>";
                break;
        }
        ?>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('expanded');
        }
    </script>
</body>
</html>
