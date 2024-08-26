<?php
session_start();
if (!isset($_SESSION['AdminID'])) {
    header("Location: admin_login.php");
    exit();
}

include '../includes/db_connect.php';
include 'includes/admin_header.php';


$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
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

        .footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
            border-top: 1px solid #eaeaea;
            position: relative;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2>Manage Users</h2>
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['UserID']; ?></td>
                            <td><?php echo htmlspecialchars($row['Name']); ?></td>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['Telephone']); ?></td>
                            <td>
                                <a href="../admin/admin_view_profile.php?user_id=<?php echo $row['UserID']; ?>" class="btn btn-info btn-sm">View Profile</a>
                                <form action="delete_user.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['UserID']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No users found.</p>
        <?php endif; ?>
    </div>

     <?php
    $conn->close();
    include 'includes/admin_footer.php';
    ?>
</body>
</html>
