<?php
include('config.php');

$error_message = '';
$success_message = '';

// Delete Department
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM departments WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        header("Location: list_department.php");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
