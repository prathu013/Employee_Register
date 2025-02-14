<?php
include('config.php');

$message = "";

if (isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $positions = implode(", ", $_POST['position']);

    $stmt = $conn->prepare("INSERT INTO employees (name, email, phone, position, department) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $positions, $department);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'> Employee added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'> Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'includes/sidebar.php'; ?>
    <?php include 'includes/header.php'; ?>
    
    <!-- Bootstrap & Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    
<body>

    <div class="card">
        <h2> Add New Employee</h2>
        <?= $message; ?>

        <form name="employeeForm" method="POST" onsubmit="return validateForm()">
            <div class="form-group mb-3">
                <label for="name"> Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="email"> Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            
            <div class="form-group mb-3">
                <label for="phone"> Phone</label>
                <input type="text" class="form-control" name="phone" required>
            </div>

            <div class="form-group mb-3">
                <label for="department"> Department</label>
                <select class="form-control" id="department" name="department" required>
                    <option value="">Select a Department</option>
                    <?php
                    $sql = "SELECT id, department_name FROM departments";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['department_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="position"> Position</label>
                <select class="form-control select2" id="position" name="position[]" multiple="multiple" required>
                    <option value="">Select Position(s)</option>
                </select>
            </div>

            <button type="submit" name="add_employee" class="btn btn-primary w-100">Add Employee</button>
        </form>
    </div>

    <!-- jQuery, Bootstrap, and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#position').select2({
                placeholder: " Select Position(s)",
                allowClear: true
            });

            $('#department').on("change", function() {
                let departmentId = $(this).val();
                let positionDropdown = $("#position");

                positionDropdown.empty().prop("disabled", true);

                if (departmentId) {
                    $.ajax({
                        url: 'fetch_positions.php',
                        type: 'GET',
                        data: { department_id: departmentId },
                        dataType: 'json',
                        success: function(data) {
                            positionDropdown.append(new Option('Select Position(s)', ''));
                            data.forEach(function(position) {
                                positionDropdown.append(new Option(position.position_name, position.id));
                            });
                            positionDropdown.prop("disabled", false);
                        },
                        error: function() {
                            alert(' Error fetching positions');
                            positionDropdown.prop("disabled", false);
                        }
                    });
                } else {
                    positionDropdown.append(new Option('Select Position(s)', ''));
                }
            });
        });

        function validateForm() {
            let department = document.forms["employeeForm"]["department"].value;
            let positions = $('#position').val();
            
            if (department == "") {
                alert(" Please select a department");
                return false;
            }
            
            if (!positions || positions.length === 0) {
                alert(" Please select at least one position");
                return false;
            }
            
            return true;
        }
    </script>

</body>
</html>
