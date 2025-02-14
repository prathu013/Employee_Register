<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$message = "";

// First, let's modify the admin_master table if needed
$alter_table = "ALTER TABLE admin_master ADD IF NOT EXISTS 
    name VARCHAR(100) DEFAULT NULL,
    ADD IF NOT EXISTS email VARCHAR(100) DEFAULT NULL,
    ADD IF NOT EXISTS mobile VARCHAR(15) DEFAULT NULL";
$conn->query($alter_table);

// Fetch Admin Data
$sql = "SELECT * FROM admin_master WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['admin']);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
$stmt->close();

// Update Admin Profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);

    // Basic Validation
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Invalid email format.</div>";
    } elseif (!empty($mobile) && !preg_match('/^[0-9]{10}$/', $mobile)) {
        $message = "<div class='alert alert-danger'>Mobile number must be 10 digits.</div>";
    } else {
        if (!empty($password)) {
            // Update with new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE admin_master SET name=?, email=?, mobile=?, password=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $mobile, $hashed_password, $_SESSION['admin']);
        } else {
            // Update without changing password
            $sql = "UPDATE admin_master SET name=?, email=?, mobile=? WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $mobile, $_SESSION['admin']);
        }

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Profile updated successfully!</div>";
            // Refresh admin data after update
            $stmt->close();
            $sql = "SELECT * FROM admin_master WHERE username=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $_SESSION['admin']);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Admin Profile</title>

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: rgb(11, 142, 230);
            --hover-color: rgb(9, 110, 178);
            --text-color: white;
            --background-color: #f8f9fa;
        }
        body {
            background-color: var(--background-color);
            display: flex;
        }
        .sidebar {
            position: fixed;
            width: 250px;
            height: 100vh;
            background: var(--primary-color);
            color: var(--text-color);
            padding: 20px;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
        }
        h3 {
            font-weight: 600;
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            transition: 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: var(--hover-color);
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--primary-color);
        }
        .form-group label {
            font-weight: 500;
            margin-bottom: 8px;
        }
        .form-control {
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <?php include('includes/sidebar.php'); ?>
    
    <div class="main-content">
        <div class="container">
            <h3>Edit Admin Profile</h3>
            
            <?php echo $message; ?>

            <form method="POST">
                <div class="form-group mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($admin['name'] ?? ''); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" name="mobile" value="<?php echo htmlspecialchars($admin['mobile'] ?? ''); ?>">
                </div>
                <div class="form-group mb-3">
                    <label for="password">New Password (leave empty to keep current)</label>
                    <div class="password-container">
                        <input type="password" class="form-control" name="password" id="password">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                    </div>
                </div>
                <button type="submit" name="update_admin" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>