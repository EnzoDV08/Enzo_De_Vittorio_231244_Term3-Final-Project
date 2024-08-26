<?php
include '../includes/db_connect.php';
include '../includes/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['UserID'];

$stmt = $conn->prepare("SELECT Name, Email, Password FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($name, $email, $currentHashedPassword);
$stmt->fetch();
$stmt->close();

$updateSuccess = false;
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        
        $newName = $_POST['name'];
        $newEmail = $_POST['email'];

        $stmt = $conn->prepare("UPDATE users SET Name = ?, Email = ? WHERE UserID = ?");
        $stmt->bind_param("ssi", $newName, $newEmail, $userID);
        if ($stmt->execute()) {
            $_SESSION['updateSuccess'] = true;
        } else {
            $_SESSION['errorMessage'] = "Error updating profile information.";
        }
        $stmt->close();
    } elseif (isset($_POST['change_password'])) {
        
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];

        if (password_verify($currentPassword, $currentHashedPassword)) {
            if (!password_verify($newPassword, $currentHashedPassword)) {
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET Password = ? WHERE UserID = ?");
                $stmt->bind_param("si", $newHashedPassword, $userID);
                if ($stmt->execute()) {
                    $_SESSION['updateSuccess'] = true;
                } else {
                    $_SESSION['errorMessage'] = "Error updating password.";
                }
                $stmt->close();
            } else {
                $_SESSION['errorMessage'] = "New password cannot be the same as the old password.";
            }
        } else {
            $_SESSION['errorMessage'] = "Current password is incorrect.";
        }
    }

    header("Location: edit_profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    
    
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 120px;
            max-width: 700px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .container:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 28px;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            animation: slideIn 1s ease-out;
        }

        .form-group {
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.5s;
        }

        .form-group:nth-child(3) {
            animation-delay: 1s;
        }

        label {
            font-weight: bold;
            color: #555;
            transition: color 0.3s ease;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            animation: bounceIn 0.8s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-secondary {
            background-color: #f44336;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            animation: bounceIn 0.8s ease;
        }

        .btn-secondary:hover {
            background-color: #d32f2f;
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateX(-50%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: bounceIn 0.5s ease;
        }

        .popup h2 {
            margin-bottom: 10px;
            font-size: 20px;
        }

        .popup p {
            margin-bottom: 15px;
            font-size: 16px;
        }

        .popup .btn {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .popup .btn-primary {
            background-color: #007bff;
            color: #fff;
        }

        .popup .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .popup .btn-secondary {
            background-color: #f44336;
            color: #fff;
        }

        .popup .btn-secondary:hover {
            background-color: #d32f2f;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Profile & Change Password</h1>
    <form action="edit_profile.php" method="post">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <input type="submit" name="update_profile" value="Update Profile" class="btn-primary">
    </form>

    <form action="edit_profile.php" method="post" class="mt-4">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" class="form-control" placeholder="Current Password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" class="form-control" placeholder="New Password" required>
        </div>
        <input type="submit" name="change_password" value="Change Password" class="btn-secondary">
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.form-group').addClass('fade-in');

        <?php if (isset($_SESSION['updateSuccess']) && $_SESSION['updateSuccess']): ?>
            showPopup('Success', 'Your profile has been updated successfully!', 'btn-primary');
            <?php unset($_SESSION['updateSuccess']); ?>
        <?php elseif (isset($_SESSION['errorMessage']) && $_SESSION['errorMessage']): ?>
            showPopup('Error', '<?php echo $_SESSION['errorMessage']; ?>', 'btn-secondary');
            <?php unset($_SESSION['errorMessage']); ?>
        <?php endif; ?>
    });

    function showPopup(title, message, btnClass) {
        $('body').append(`
            <div class="popup-overlay">
                <div class="popup">
                    <h2>${title}</h2>
                    <p>${message}</p>
                    <button class="btn ${btnClass}" onclick="closePopup(true)">Close</button>
                </div>
            </div>
        `);
        $('.popup-overlay').fadeIn();
    }

    function closePopup(reload) {
        $('.popup-overlay').fadeOut(function() {
            $(this).remove();
            if (reload) {
                location.reload();
            }
        });
    }
</script>

</body>
</html>
