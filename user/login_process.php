<?php
include '../includes/db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['user_name'] = $user['Name'];
            header("Location: login.php?success=1");
        } else {
            header("Location: login.php?error=Incorrect Password");
        }
    } else {
        header("Location: login.php?error=No account found with that email");
    }

    $stmt->close();
    $conn->close();
}
?>
