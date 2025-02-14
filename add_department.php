<?php
include('config.php');

$error_message = '';
$success_message = '';

// Add Department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_department'])) {
    $department_name = trim($_POST['department_name']);

    if (empty($department_name)) {
        $error_message = "Department name cannot be empty.";
    } else {
        // Check if department already exists
        $stmt = $conn->prepare("SELECT id FROM departments WHERE department_name = ?");
        $stmt->bind_param("s", $department_name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = " Department already exists.";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO departments (department_name) VALUES (?)");
            $stmt->bind_param("s", $department_name);
            if ($stmt->execute()) {
                $success_message = " Department added successfully!";
            } else {
                $error_message = " Error adding department.";
            }
        }
        $stmt->close();
    }
}

// Add Position
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_position'])) {
    $position_name = trim($_POST['position']);
    $department_id = $_POST['department_id'];

    if (empty($position_name) || empty($department_id)) {
        $error_message = " Please fill in all fields.";
    } else {
        // Check if position already exists in the department
        $stmt = $conn->prepare("SELECT id FROM positions WHERE position_name = ? AND department_id = ?");
        $stmt->bind_param("si", $position_name, $department_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = " Position already exists in this department.";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO positions (position_name, department_id) VALUES (?, ?)");
            $stmt->bind_param("si", $position_name, $department_id);
            if ($stmt->execute()) {
                $success_message = " Position added successfully!";
            } else {
                $error_message = " Error adding position.";
            }
        }
        $stmt->close();
    }
}

// Fetch Departments
$departments = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/sidebar.php'); ?>
    <?php include('includes/header.php'); ?>
    <title>Manage Departments & Positions</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="content">
    <div class="container my-4">
        <h1 class="text-center text-primary"> Manage Departments & Positions</h1>

        <!-- Display Messages -->
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success text-center"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Add Department Form -->
        <div class="card my-4">
            <div class="card-body">
                <h5 class="card-title text-center"> Add Department</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="department_name" class="form-label">Department Name</label>
                        <input type="text" class="form-control" name="department_name" required>
                    </div>
                    <button type="submit" name="add_department" class="btn btn-primary w-100"> Add Department</button>
                </form>
            </div>
        </div>

        <!-- Add Position Form -->
        <div class="card my-4">
            <div class="card-body">
                <h5 class="card-title text-center"> Add Position</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="position" class="form-label">Position Name</label>
                        <input type="text" class="form-control" name="position" required>
                    </div>
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Select Department</label>
                        <select class="form-control" name="department_id" required>
                            <option value="" disabled selected> Choose a department</option>
                            <?php while ($row = $departments->fetch_assoc()) { ?>
                                <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <?php echo htmlspecialchars($row['department_name']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" name="add_position" class="btn btn-primary w-100">Add Position</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
