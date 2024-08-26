<?php


include '../includes/db_connect.php';
session_start();

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    $userID = $_SESSION['UserID'];

    $stmt = $conn->prepare("SELECT Password FROM user WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($currentPassword, $hashed_password)) {
        $stmt = $conn->prepare("UPDATE user SET Password = ? WHERE UserID = ?");
        $stmt->bind_param("si", $newPassword, $userID);

        if ($stmt->execute()) {
            header("Location: profile.php");
            exit();
        } else {
            echo "Error updating password.";
        }

        $stmt->close();
    } else {
        echo "Current password is incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/navbar.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/profile.css">

    <style>
       
        .navbar .nav-link, 
        .navbar .navbar-brand {
            color: #000 !important; 
        }

     
        .navbar-scrolled .navbar-brand,
        .navbar-scrolled .nav-link {
            color: #000 !important;
        }

        
        .container {
            margin-top: 150px; 
        }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h1 class="text-center mb-4">Change Password</h1>
    <form action="change_password.php" method="post">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
        </div>
        <input type="submit" value="Change Password" class="btn btn-primary">
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.amazonaws.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

