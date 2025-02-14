<?php
require_once 'config.php';

// Fetch customer data from the database
$sql = "SELECT id, name, email, phone, address, gender, dob FROM customers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    
    <!-- Bootstrap & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background:rgb(0, 128, 255);
            padding: 20px;
            height: 100vh;
            color: white;
        }
        .sidebar ul {
            padding: 0;
            list-style: none;
        }
        .sidebar ul li {
            margin: 15px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sidebar ul li a:hover {
            color: #ffeb3b;
            transform: scale(1.05);
        }
        .container {
            margin: 20px auto;
            max-width: 95%;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-action a {
            font-size: 14px;
            padding: 5px 10px;
            margin-right: 5px;
        }
        .btn-action a:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="container">
    <h2 class="text-center text-primary"> Customer List</h2>

    <div class="table-container">
        <!-- Search Input -->
        <div class="mb-3">
            <input type="text" id="searchBox" class="form-control" placeholder=" Search customer...">
        </div>

        <table id="customerTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th> Name</th>
                    <th> Email</th>
                    <th> Phone</th>
                    <th> Address</th>
                    <th> Gender</th>
                    <th> Date of Birth</th>
                    <th class="text-center"> Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['phone']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td><?= htmlspecialchars($row['gender']); ?></td>
                            <td><?= htmlspecialchars($row['dob']); ?></td>
                            <td class="text-center btn-action">
                                <a href="edit_customer.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_customer.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Are you sure you want to delete this customer?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- jQuery, Bootstrap, and DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        let table = $('#customerTable').DataTable({
            paging: true,
            searching: true,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: "", // Hide the default search label
                searchPlaceholder: "Search customers..."
            }
        });

        // Search Box Functionality
        $('#searchBox').on('keyup', function() {
            table.search(this.value).draw();
        });
    });
</script>

</body>
</html>

<?php
$conn->close();
?>
