<?php
session_start();
if (!isset($_SESSION['AdminID'])) {
    header("Location: admin_login.php");
    exit();
}

include '../includes/db_connect.php';
include 'includes/admin_header.php'; 


$propertyCount = $conn->query("SELECT COUNT(*) AS total FROM property")->fetch_assoc()['total'];
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
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

        .card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            border: none;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 20px;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 16px;
            color: #555;
            margin-bottom: 15px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

      
    </style>
</head>
<body>

    <div class="container mt-5">
        <h2>Admin Dashboard</h2>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Properties</h5>
                        <p class="card-text">Total Properties: <?php echo $propertyCount; ?></p>
                        <a href="admin_properties.php" class="btn btn-primary">Manage Properties</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text">Total Users: <?php echo $userCount; ?></p>
                        <a href="admin_users.php" class="btn btn-primary">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <?php
    $conn->close();
    include 'includes/admin_footer.php';
    ?>
   
</body>
</html>
