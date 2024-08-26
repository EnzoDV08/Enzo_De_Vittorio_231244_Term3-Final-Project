
<?php
session_start();
if (!isset($_SESSION['AdminID'])) {
    header("Location: admin_login.php");
    exit();
}

include '../includes/db_connect.php';
include 'includes/admin_header.php';


$sql = "SELECT * FROM property";
$result = $conn->query($sql);


$pendingPropertyCount = $conn->query("SELECT COUNT(*) AS total FROM property WHERE ApprovalStatus = 'pending'")->fetch_assoc()['total'];


$_SESSION['pendingPropertyCount'] = $pendingPropertyCount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Properties</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            padding: 20px;
            margin-top: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .table-striped {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .table-striped th, .table-striped td {
            vertical-align: middle;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2>Manage Properties</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Property ID</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Address</th>
                        <th>Approval Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['PropertyID']; ?></td>
                            <td><?php echo htmlspecialchars($row['Title']); ?></td>
                            <td>R <?php echo number_format($row['Price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['Address']); ?></td>
                            <td><?php echo htmlspecialchars($row['ApprovalStatus']); ?></td>
                            <td>
                                <?php if ($row['ApprovalStatus'] == 'pending'): ?>
                                    <form action="approve_property.php" method="POST" style="display:inline;">
    <input type="hidden" name="property_id" value="<?php echo $row['PropertyID']; ?>">
    <input type="hidden" name="action" value="approve">
    <button type="submit" class="btn btn-success btn-sm">Approve</button>
</form>
<form action="approve_property.php" method="POST" style="display:inline;">
    <input type="hidden" name="property_id" value="<?php echo $row['PropertyID']; ?>">
    <input type="hidden" name="action" value="deny">
    <button type="submit" class="btn btn-danger btn-sm">Deny</button>
</form>

                                <?php else: ?>
                                    <span class="badge badge-secondary">No actions available</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No properties found.</p>
        <?php endif; ?>
    </div>

   
    <?php
    $conn->close();
    include 'includes/admin_footer.php';
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(document).on('submit', 'form', function(e) {
        e.preventDefault();

        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: form.serialize(), 
            success: function(response) {
                if (response.status === 'success') {
                    location.reload(); 
                } else {
                    console.error('Failed to update property status.');
                    location.reload(); 
                }
            },
            error: function() {
                console.error('An error occurred. Please try again.');
                location.reload(); 
            }
        });
    });
</script>


</body>
</html>
