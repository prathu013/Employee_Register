<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$dbname = 'Company_Emp';  // Change this to your database name
$username = 'root';         // Change this to your database username
$password = '';             // Change this to your database password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create admin_master table if it doesn't exist
$create_table = "CREATE TABLE IF NOT EXISTS admin_master (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";

$conn->query($create_table);

// Create default admin user if it doesn't exist
$check_admin = $conn->query("SELECT * FROM admin_master WHERE username = 'admin'");
if ($check_admin->num_rows == 0) {
    $default_username = 'admin';
    $default_password = 'admin123';
    $hashed_password = password_hash($default_password, PASSWORD_DEFAULT);
    
    $insert_admin = $conn->prepare("INSERT INTO admin_master (username, password) VALUES (?, ?)");
    $insert_admin->bind_param("ss", $default_username, $hashed_password);
    $insert_admin->execute();
    $insert_admin->close();
}

// Check if already logged in
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        if (empty($username) || empty($password)) {
            $error = "Please fill in all fields";
        } else {
            $sql = "SELECT * FROM admin_master WHERE username = ?";
            try {
                $stmt = $conn->prepare($sql);
                if ($stmt === false) {
                    throw new Exception("Failed to prepare statement");
                }
                
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                $admin = $result->fetch_assoc();
                $stmt->close();

                if ($admin && password_verify($password, $admin['password'])) {
                    $_SESSION['admin'] = $admin['username'];
                    $_SESSION['admin_id'] = $admin['id'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid username or password";
                }
            } catch (Exception $e) {
                $error = "An error occurred. Please try again later.";
                error_log("Login error: " . $e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            width: 380px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .input-group-text {
            background-color: rgb(136, 116, 116);
            color: white;
        }
        .input-group-text i {
            font-size: 1.2rem;
        }
        .toggle-password {
            cursor: pointer;
            color: rgb(255, 255, 255);
        }
        .toggle-password:hover {
            color: rgb(170, 182, 196);
        }
    </style>
</head>
<body>

<div class="login-card">
    <h3 class="mb-4">Admin Login</h3>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="form-group mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <span class="input-group-text toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password i');
        
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

</body>
</html>