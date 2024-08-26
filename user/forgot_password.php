<?php


require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $query = "SELECT * FROM User WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $query = "UPDATE User SET reset_token = ?, reset_expiration = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $token, $email);
        $stmt->execute();

        $resetLink = "http://localhost/real_estate_website/user/reset_password.php?token=$token";
        $message = "Click the following link to reset your password: $resetLink";
        mail($email, "Password Reset", $message, "From: no-reply@example.com");

        $success = "A password reset link has been sent to your email.";
    } else {
        $error = "No account found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../assets/css/forgot-password.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="forgot_password_process.php" method="post">
            <div class="error-message" id="error-message"></div>
            <input type="email" name="email" placeholder="Enter your email" required>
            <input type="submit" value="Reset Password">
            <p><a href="login.php">Remembered your password? Login</a></p>
        </form>
    </div>

    <script src="../assets/js/forgot-password.js"></script>
</body>
</html>

