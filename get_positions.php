<?php
include('config.php');

if (isset($_GET['department'])) {
    $department = $_GET['department'];

    $sql = "SELECT position_name FROM positions WHERE department_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $department);
    $stmt->execute();
    $result = $stmt->get_result();

    echo '<option value="">Select a Position</option>';
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . htmlspecialchars($row['position_name']) . "'>" . htmlspecialchars($row['position_name']) . "</option>";
    }

    $stmt->close();
}
?>
