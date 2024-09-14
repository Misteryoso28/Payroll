<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'db_connection.php'; // Includes your DB connection
// Handle Report Generation
$report_type = isset($_GET['report']) ? $_GET['report'] : 'employee_list';
switch ($report_type) {
    case 'employee_list':
        $result = $conn->query("SELECT * FROM employees");
        $report_title = "Employee List";
        break;
    case 'payroll_summary':
        $result = $conn->query("SELECT payrolls.*, employees.name FROM payrolls JOIN employees ON payrolls.employee_id = employees.employee_id");
        $report_title = "Payroll Summary";
        break;
    default:
        $result = $conn->query("SELECT * FROM employees");
        $report_title = "Employee List";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h2>Reports</h2>
    <h3><?= $report_title ?></h3>
    <table>
        <tr>
            <?php if ($report_type === 'employee_list') { ?>
                <th>ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Salary</th>
            <?php } elseif ($report_type === 'payroll_summary') { ?>
                <th>Employee</th>
                <th>Date</th>
                <th>Gross Pay</th>
                <th>Tax</th>
                <th>Net Pay</th>
            <?php } ?>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <?php if ($report_type === 'employee_list') { ?>
                <td><?= htmlspecialchars($row['employee_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['position']) ?></td>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= htmlspecialchars($row['salary']) ?></td>
            <?php } elseif ($report_type === 'payroll_summary') { ?>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['payroll_date']) ?></td>
                <td><?= htmlspecialchars($row['gross_pay']) ?></td>
                <td><?= htmlspecialchars($row['tax']) ?></td>
                <td><?= htmlspecialchars($row['net_pay']) ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
