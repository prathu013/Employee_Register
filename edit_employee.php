<?php
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM employees WHERE id = '$id'";
    $result = $conn->query($sql);
    $employee = $result->fetch_assoc();
}

if (isset($_POST['update_employee'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    $sql = "UPDATE employees SET name='$name', email='$email', phone='$phone', position='$position', department='$department' WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        $message = "Employee updated successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary-color: #007bff;
            --background-color: #f8f9fa;
            --text-color: #ffffff;
        }
        body {
            background-color: var(--background-color);
        }
        .container {
            background-color: var(--text-color);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 200px;
            background-color: var(--primary-color);
            color: var(--text-color);
            padding: 20px;
        }
        .sidebar a {
            color: var(--text-color);
            text-decoration: none;
            display: block;
            padding: 10px 0;
        }
        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2 class="header">Admin Panel</h2>
        <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Add Employee</a></li>
        <li><a href="list_employee.php"><i class="fas fa-users"></i> Employee List</a></li>
    </div>
    <div class="main-content">
        <div class="container mt-5">
            <h3>Edit Employee</h3>
            <?php if (isset($message)) echo "<p class='text-success'>$message</p>"; ?>
            <form method="POST">
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo $employee['name']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo $employee['email']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?php echo $employee['phone']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="position">Position</label>
                    <input type="text" class="form-control" name="position" value="<?php echo $employee['position']; ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="department">Department</label>
                    <select name="department" class="form-select" required>
                        <option value="">Select Department</option>
                        <?php
                        $sql = "SELECT * FROM departments";
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($employee['department'] == $row['department_name']) ? 'selected' : '';
                            echo "<option value='{$row['department_name']}' $selected>{$row['department_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="update_employee" class="btn btn-primary">Update Employee</button>
            </form>
        </div>
    </div>
</body>
</html>
