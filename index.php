<?php
include('config.php');
include('includes/header.php');
include('includes/sidebar.php');
?>

<div class="container">
    <h2>Add New Employee</h2>
    <form action="add_employee.php" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label for="position">Position:</label>
            <input type="text" class="form-control" id="position" name="position" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<?php
include('includes/footer.php');
?>