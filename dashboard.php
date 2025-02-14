<?php
session_start();
include('config.php');

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Fetch admin details securely
$username = $_SESSION['admin'];
$sql = "SELECT * FROM admin_master WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// If admin not found, redirect to login
if (!$admin) {
    session_destroy();
    header("Location: login.php");
    exit;
}

//Fecth Counts
$employee_count = $conn->query("SELECT COUNT(*) AS total FROM employees")->fetch_assoc()['total'];
$department_count = $conn->query("SELECT COUNT(*) AS total FROM departments")->fetch_assoc()['total'];
$customer_count = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/header.php'); ?>

<!-- Main Content -->
<div class="container mt-5">
    <div class="row justify-content-center text-center"> <!-- Centers the cards -->

        <!-- Employees Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3 d-flex justify-content-center">
            <div class="card text-white bg-info shadow-lg rounded-3 w-100">
                <div class="card-body">
                    <h2 class="fw-bold"><?php echo $employee_count; ?></h2>
                    <p class="fs-5">Total Employees</p>
                </div>
                <div class="card-footer">
                    <a href="list_employee.php" class="text-white fw-bold">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Departments Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3 d-flex justify-content-center">
            <div class="card text-white bg-success shadow-lg rounded-3 w-100">
                <div class="card-body">
                    <h2 class="fw-bold"><?php echo $department_count; ?></h2>
                    <p class="fs-5">Total Departments</p>
                </div>
                <div class="card-footer">
                    <a href="list_department.php" class="text-white fw-bold">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Customers Card -->
        <div class="col-lg-3 col-md-4 col-sm-6 mb-3 d-flex justify-content-center">
            <div class="card text-white bg-warning shadow-lg rounded-3 w-100">
                <div class="card-body">
                    <h2 class="fw-bold"><?php echo $customer_count; ?></h2>
                    <p class="fs-5">Registered Customers</p>
                </div>
                <div class="card-footer">
                    <a href="list_customer.php" class="text-white fw-bold">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
