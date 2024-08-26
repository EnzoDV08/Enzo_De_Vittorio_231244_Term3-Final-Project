<?php

session_start();

include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT UserID, Password FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userID, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['UserID'] = $userID;
            $_SESSION['popup_message'] = "Welcome back!";
            error_log("Login successful for user ID: " . $userID); 
            header("Location: ../index.php");
            exit();
        } else {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    document.querySelector('.error-message').textContent = 'Incorrect password. Please try again.';
                });
            </script>";
        }
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelector('.error-message').textContent = 'No account found with that email. Please try again.';
            });
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/popup.css">
    <style>
        .back-arrow {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 24px;
            color: #000;
            text-decoration: none;
            display: flex;
            align-items: center;
            font-weight: bold;
            transition: transform 0.3s ease-in-out;
            z-index: 10;
        }

        .back-arrow::before {
            content: "\\2190"; 
            font-size: 28px;
            margin-right: 8px;
            color: #000;
        }

        .back-arrow:hover {
            transform: scale(1.1);
        }

        .back-arrow:hover::before {
            color: #000;
        }

        .container {
            position: relative;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../index.php" class="back-arrow"></a>
        <h2>Login to Your Account</h2>
        <form action="login.php" method="post">
            <input type="email" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
            <p class="error-message"></p>
            <a href="forgot_password.php">Forgot your password?</a>
            <a href="register.php">Don't have an account? Sign up</a>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        function showPopup(message) {
            var popupOverlay = document.createElement('div');
            popupOverlay.className = 'popup-overlay';
            document.body.appendChild(popupOverlay);

            var popup = document.createElement('div');
            popup.className = 'popup';
            popup.innerHTML = '<h3>' + message + '</h3><button onclick="closePopup()">Continue</button>';
            document.body.appendChild(popup);
        }

        function closePopup() {
            document.querySelector('.popup').remove();
            document.querySelector('.popup-overlay').remove();
            window.location.href = "../index.php"; 
        }
    });
    </script>
</body>
</html>
<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "new_real_estate_db";  

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage(), 3, 'errors.log');
    die("Connection failed. Please try again later.");
}

exit();
?>
