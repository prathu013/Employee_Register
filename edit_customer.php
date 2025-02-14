<?php
require_once 'config.php';

// Check if ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Customer ID.");
}

$customer_id = intval($_GET['id']);

// Fetch customer details from the database
$sql = "SELECT * FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Customer not found.");
}

$customer = $result->fetch_assoc();

// Update customer details if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);
    $gender = htmlspecialchars($_POST['gender']);
    $dob = htmlspecialchars($_POST['dob']);

    // Update query
    $update_sql = "UPDATE customers SET name=?, email=?, phone=?, address=?, gender=?, dob=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssssi", $name, $email, $phone, $address, $gender, $dob, $customer_id);

    if ($stmt->execute()) {
        echo "<script>alert('Customer details updated successfully!'); window.location.href='list_customer.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body class="bg-light">
<?php include 'includes/sidebar.php'; ?>

<div class="container mt-5">
    <h2 class="text-center text-primary">Edit Customer</h2>
    
    <div class="card p-4 shadow-sm">
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Name:</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($customer['name']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email:</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($customer['email']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone:</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($customer['phone']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Address:</label>
                <textarea name="address" class="form-control" required><?= htmlspecialchars($customer['address']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender:</label>
                <select name="gender" class="form-control" required>
                    <option value="Male" <?= $customer['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?= $customer['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?= $customer['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Birth:</label>
                <input type="date" name="dob" class="form-control" value="<?= htmlspecialchars($customer['dob']); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Customer</button>
            <a href="list_customer.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
