<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
include 'db_connection.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $age = intval($_POST['age']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $salary = floatval($_POST['salary']);
    $working_hours = intval($_POST['working_hours']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);

    if (isset($_POST['employee_id'])) {
        $employee_id = intval($_POST['employee_id']);
        $sql = "UPDATE employees SET name='$name', address='$address', gender='$gender', age='$age', phone_number='$phone_number', email='$email', position='$position', salary='$salary', working_hours='$working_hours', department='$department' WHERE employee_id=$employee_id";
    } else {
        $sql = "INSERT INTO employees (name, address, gender, age, phone_number, email, position, salary, working_hours, department) VALUES ('$name', '$address', '$gender', '$age', '$phone_number', '$email', '$position', '$salary', '$working_hours', '$department')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?page=employees");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

if (isset($_GET['delete'])) {
    $employee_id = intval($_GET['delete']);
    $conn->query("DELETE FROM employees WHERE employee_id=$employee_id");
    header("Location: dashboard.php?page=employees");
    exit();
}

// Fetch All Employees
$result = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
</head>
<body>
    <h2>Manage Employees</h2>
    <a href="dashboard.php?page=employees&add=1" class="button add">Add New Employee</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Position</th>
            <th>Department</th>
            <th>Salary</th>
            <th>Actions</th>
        </tr>
        <?php while ($employee = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($employee['employee_id']) ?></td>
            <td><?= htmlspecialchars($employee['name']) ?></td>
            <td><?= htmlspecialchars($employee['position']) ?></td>
            <td><?= htmlspecialchars($employee['department']) ?></td>
            <td>₱<?= htmlspecialchars($employee['salary']) ?></td>
            <td>
                <a href="dashboard.php?page=employees&edit=<?= $employee['employee_id'] ?>" class="button edit">Edit</a>
                <a href="dashboard.php?page=employees&delete=<?= $employee['employee_id'] ?>" class="button delete" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <?php if (isset($_GET['add']) || isset($_GET['edit'])) {
        $employee = [];
        if (isset($_GET['edit'])) {
            $employee_id = intval($_GET['edit']);
            $result = $conn->query("SELECT * FROM employees WHERE employee_id=$employee_id");
            $employee = $result->fetch_assoc();
        }
    ?>
    <h3><?= isset($_GET['edit']) ? 'Edit Employee' : 'Add New Employee' ?></h3>
    <form method="POST" action="">
        <?php if (isset($_GET['edit'])) { ?>
        <input type="hidden" name="employee_id" value="<?= htmlspecialchars($employee['employee_id']) ?>">
        <?php } ?>
        <!-- Employee form fields -->
        <label>Name:</label><input type="text" name="name" value="<?= htmlspecialchars($employee['name'] ?? '') ?>" required><br>
        <label>Address:</label><input type="text" name="address" value="<?= htmlspecialchars($employee['address'] ?? '') ?>"><br>
        <label>Gender:</label>
        <select name="gender">
            <option value="Male" <?= isset($employee['gender']) && $employee['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
            <option value="Female" <?= isset($employee['gender']) && $employee['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            <option value="Other" <?= isset($employee['gender']) && $employee['gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select><br>
        <label>Age:</label><input type="number" name="age" value="<?= htmlspecialchars($employee['age'] ?? '') ?>"><br>
        <label>Phone Number:</label><input type="text" name="phone_number" value="<?= htmlspecialchars($employee['phone_number'] ?? '') ?>"><br>
        <label>Email:</label><input type="email" name="email" value="<?= htmlspecialchars($employee['email'] ?? '') ?>" required><br>
        <label>Position:</label><input type="text" name="position" value="<?= htmlspecialchars($employee['position'] ?? '') ?>" required><br>
        <label>Salary:</label><input type="number" step="0.01" name="salary" value="<?= htmlspecialchars($employee['salary'] ?? '') ?>" required><br>
        <label>Working Hours:</label><input type="number" name="working_hours" value="<?= htmlspecialchars($employee['working_hours'] ?? '') ?>" required><br>
        <label>Department:</label><input type="text" name="department" value="<?= htmlspecialchars($employee['department'] ?? '') ?>"><br>
        <button type="submit"><?= isset($_GET['edit']) ? 'Update' : 'Add' ?></button>
    </form>
    <?php } ?>
</body>
</html>

