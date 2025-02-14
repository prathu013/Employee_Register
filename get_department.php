<?php
include('config.php');

if (isset($_POST['position_name'])) {
    $position = $_POST['position_name'];

    $sql = "SELECT d.department_name FROM positions p 
            JOIN departments d ON p.department_id = d.id 
            WHERE p.position_name = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $position);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo $row['department_name']; // Send department name as response
    } else {
        echo "Not Found"; // If no department is found
    }
}
?>
