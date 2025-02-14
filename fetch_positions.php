<?php
include('config.php');

header('Content-Type: application/json');

if (isset($_GET['department_id'])) {
    $dept_id = $_GET['department_id'];
    
    $stmt = $conn->prepare("SELECT id, position_name FROM positions WHERE department_id = ?");
    $stmt->bind_param("i", $dept_id);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $positions = array();
    
    while ($row = $result->fetch_assoc()) {
        $positions[] = array(
            'id' => $row['id'],
            'position_name' => $row['position_name']
        );
    }
    
    echo json_encode($positions);
    $stmt->close();
} else {
    echo json_encode(array('error' => 'No department ID provided'));
}
?>