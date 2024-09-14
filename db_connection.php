<?php
// Database connection details
$servername = "localhost"; // Change if your MySQL server is on a different host
$username = "root";        // Replace with your MySQL username
$password = "";            // Replace with your MySQL password
$dbname = "payroll_system"; // Database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the charset to utf8 to ensure proper encoding
$conn->set_charset("utf8");
?>
