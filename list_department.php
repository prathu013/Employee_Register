<?php
include('config.php');

// Fetch departments with positions using GROUP_CONCAT()
$sql = "SELECT d.id, d.department_name, 
               COALESCE(GROUP_CONCAT(p.position_name SEPARATOR ', '), '') AS positions 
        FROM departments d 
        LEFT JOIN positions p ON d.id = p.department_id 
        GROUP BY d.id, d.department_name";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'includes/header.php'; ?>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="container">
        <h2 class="mb-4">Departments & Positions</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Department Name</th>
                        <th>Positions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']); ?></td>
                            <td><?= htmlspecialchars($row['department_name']); ?></td>
                            <td>
                                <?php if (!empty($row['positions'])) { ?>
                                    <ul class="position-list">
                                        <?php 
                                        $positions = explode(', ', $row['positions']);
                                        foreach ($positions as $position) {
                                            echo "<li>" . htmlspecialchars($position) . "</li>";
                                        }
                                        ?>
                                    </ul>
                                <?php } else { ?>
                                    <span class="no-positions">No Positions Assigned</span>
                                <?php } ?>
                            </td>
                            <td class="btn-action">
                                <a href="edit_department.php?id=<?= $row['id']; ?>" class="fa-solid fa-pen-to-square"></a>
                                <a href="delete_department.php?id=<?= $row['id']; ?>" class="fa-solid fa-trash"></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
