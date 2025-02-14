<?php
include('config.php');
include('includes/header.php');

$sql = "SELECT * FROM employees";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Employee List</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .table-actions a {
            margin-right: 5px;
        }
        .btn-warning, .btn-danger {
            transition: 0.3s ease-in-out;
        }
        .btn-warning:hover {
            transform: scale(1.1);
        }
        .btn-danger:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>

<!-- Page Content -->
<div class="content">
    <div class="container mt-4">
        <h3 class="mb-4 text-center text-primary"> Employee List</h3>

        <div class="table-responsive">
            <table id="employeeTable" class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['phone']; ?></td>
                            <td><?php echo $row['position']; ?></td>
                            <td><?php echo $row['department']; ?></td>
                            <td class="table-actions text-center">
                                <a href="edit_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_employee.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
