<?php
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM employees WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: list_employee.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
