<?php
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch department details
    $sql = "SELECT * FROM departments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $department = $stmt->get_result()->fetch_assoc();

    // Fetch associated positions
    $positionQuery = "SELECT position_name FROM positions WHERE department_id = ?";
    $stmt = $conn->prepare($positionQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $selected_positions = [];
    while ($row = $result->fetch_assoc()) {
        $selected_positions[] = $row['position_name'];
    }
}

// Handle form submission
if (isset($_POST['update_department'])) {
    $department_name = $_POST['department_name'];
    $positions = $_POST['position_name']; // Array of positions

    // Update department name
    $updateDepartmentSQL = "UPDATE departments SET department_name = ? WHERE id = ?";
    $stmt = $conn->prepare($updateDepartmentSQL);
    $stmt->bind_param("si", $department_name, $id);
    $stmt->execute();

    // Remove existing positions for this department
    $deletePositionsSQL = "DELETE FROM positions WHERE department_id = ?";
    $stmt = $conn->prepare($deletePositionsSQL);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Insert updated positions
    foreach ($positions as $position) {
        $insertPositionSQL = "INSERT INTO positions (position_name, department_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertPositionSQL);
        $stmt->bind_param("si", $position, $id);
        $stmt->execute();
    }

    $message = "Department and Positions updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Department & Position</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body {
            display: flex;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #007bff;
            color: white;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar h2 {
            font-size: 1.5em;
            font-weight: bold;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            flex-grow: 1;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-5">
            <h3 class="mb-4">Edit Department & Position</h3>
            <?php if (isset($message)) echo "<p class='text-success'>$message</p>"; ?>
            <form method="POST">
                <div class="form-group mb-3">
                    <label for="department_name">Department Name</label>
                    <input type="text" class="form-control" name="department_name" value="<?= htmlspecialchars($department['department_name']); ?>" required>
                </div>
                <div class="form-group mb-3">
                    <label for="position_name">Position</label>
                    <select class="form-control" id="position_name" name="position_name[]" multiple required>
                        <?php
                        // Fetch all positions from the database
                        $allPositionsSQL = "SELECT DISTINCT position_name FROM positions";
                        $allPositionsResult = $conn->query($allPositionsSQL);
                        while ($row = $allPositionsResult->fetch_assoc()) {
                            $selected = in_array($row['position_name'], $selected_positions) ? "selected" : "";
                            echo "<option value='" . $row['position_name'] . "' $selected>" . $row['position_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" name="update_department" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#position_name').select2({
                theme: 'bootstrap-5',
                placeholder: "Select or add positions",
                tags: true,
                allowClear: true
            });
        });
    </script>
</body>
</html>
