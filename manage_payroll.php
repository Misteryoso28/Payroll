<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'db_connection.php'; // Includes your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Payroll Generation
    $payroll_date = mysqli_real_escape_string($conn, $_POST['payroll_date']);
    $employee_id = intval($_POST['employee_id']);
    $salary = floatval($_POST['salary']);
    $working_hours = intval($_POST['working_hours']);
    $overtime_hours = intval($_POST['overtime_hours']);
    $overtime_rate = floatval($_POST['overtime_rate']);
    
    // Calculate payroll components
    $gross_pay = $salary + ($overtime_hours * $overtime_rate);
    $tax = $gross_pay * 0.1; // Example tax rate
    $net_pay = $gross_pay - $tax;

    // Adjusted column names based on your actual table schema
    $stmt = $conn->prepare("INSERT INTO payroll (employee_id, payroll_date, base_salary, overtime, tax, net_pay, date_generated) VALUES (?, ?, ?, ?, ?, ?, NOW())");

    // Bind parameters (i = integer, s = string, d = double)
    $base_salary = $salary;
    $overtime = $overtime_hours * $overtime_rate;
    
    $stmt->bind_param("isddd", $employee_id, $payroll_date, $base_salary, $overtime, $tax, $net_pay);

    if ($stmt->execute()) {
        echo "Payroll generated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch Employees
$employees = $conn->query("SELECT * FROM employees");

// Fetch Payroll History
$payrolls = $conn->query("SELECT payroll.*, employees.name FROM payroll JOIN employees ON payroll.employee_id = employees.employee_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payroll</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h2>Manage Payroll</h2>
    <div class="payroll-form">
        <h3>Generate Payroll</h3>
        <form method="POST" action="">
            <label>Employee:</label>
            <select name="employee_id" required>
                <?php while ($employee = $employees->fetch_assoc()) { ?>
                    <option value="<?= $employee['employee_id'] ?>"><?= htmlspecialchars($employee['name']) ?></option>
                <?php } ?>
            </select><br>
            <label>Payroll Date:</label><input type="date" name="payroll_date" required><br>
            <label>Salary:</label><input type="number" name="salary" step="0.01" required><br>
            <label>Working Hours:</label><input type="number" name="working_hours" required><br>
            <label>Overtime Hours:</label><input type="number" name="overtime_hours" required><br>
            <label>Overtime Rate:</label><input type="number" name="overtime_rate" step="0.01" required><br>
            <button type="submit">Generate Payroll</button>
        </form>
    </div>

    <h3>Payroll History</h3>
    <table class="payroll-history">
        <tr>
            <th>Employee</th>
            <th>Date</th>
            <th>Gross Pay</th>
            <th>Tax</th>
            <th>Net Pay</th>
        </tr>
        <?php while ($payroll = $payrolls->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($payroll['name']) ?></td>
            <td><?= htmlspecialchars($payroll['payroll_date']) ?></td>
            <td>₱<?= htmlspecialchars($payroll['gross_pay']) ?></td>
            <td>₱<?= htmlspecialchars($payroll['tax']) ?></td>
            <td>₱<?= htmlspecialchars($payroll['net_pay']) ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
