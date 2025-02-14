<?php
require_once 'config.php';

// Initialize messages
$success_message = "";
$error_messages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $dob = htmlspecialchars(trim($_POST['dob']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($name)) {
        $error_messages[] = " Name is required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_messages[] = " Invalid email format.";
    }
    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error_messages[] = " Phone number must be 10 digits.";
    }
    if (empty($address)) {
        $error_messages[] = " Address is required.";
    }
    if (empty($gender)) {
        $error_messages[] = " Please select a gender.";
    }
    if (empty($dob)) {
        $error_messages[] = " Date of birth is required.";
    }
    if (strlen($password) < 6) {
        $error_messages[] = " Password must be at least 6 characters long.";
    }
    if ($password !== $confirm_password) {
        $error_messages[] = " Passwords do not match.";
    }

    // If no errors, store in database
    if (empty($error_messages)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO customers (name, email, phone, address, gender, dob, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $email, $phone, $address, $gender, $dob, $hashed_password);

        if ($stmt->execute()) {
            $success_message = " Registration successful!";
        } else {
            $error_messages[] = " Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/header.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #0c63bb;
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
            transition: 0.3s ease-in-out;
        }
        .sidebar ul li a:hover {
            color: #ffeb3b;
            transform: scale(1.05);
        }
        .container {
            max-width: 500px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        h2 {
            font-weight: 600;
            text-align: center;
            color: #007bff;
        }
        .form-label {
            font-weight: 500;
        }
        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 10px;
            top: 38px;
            color: #007bff;
        }
        .password-toggle:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>

<!-- Main Content -->
<div class="container">
    <h2> Customer Registration</h2>

    <!-- Display Messages -->
    <?php if (!empty($error_messages)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($error_messages as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= $success_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label for="name" class="form-label"> Full Name:</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label"> Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label"> Phone:</label>
            <input type="text" id="phone" name="phone" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label"> Address:</label>
            <input type="text" id="address" name="address" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="gender" class="form-label"> Gender:</label>
            <select id="gender" name="gender" class="form-select" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="dob" class="form-label"> Date of Birth:</label>
            <input type="date" id="dob" name="dob" class="form-control" required>
        </div>

        <div class="mb-3 position-relative">
            <label for="password" class="form-label"> Password:</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <i class="fas fa-eye password-toggle" onclick="togglePassword('password')"></i>
        </div>

        <div class="mb-3 position-relative">
            <label for="confirm_password" class="form-label"> Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success"> Register</button>
            <button type="reset" class="btn btn-danger"> Reset</button>
        </div>
    </form>
</div>

<script>
    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        field.type = (field.type === "password") ? "text" : "password";
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
