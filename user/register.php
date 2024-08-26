<?php
// user/register.php

include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('An account with this email already exists. Please log in.'); window.location.href = 'login.php';</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (Name, Email, Telephone, Password, Role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $telephone, $password, $role);

        if ($stmt->execute()) {
            session_start();
            $_SESSION['UserID'] = $conn->insert_id;
            $_SESSION['popup_message'] = "Welcome to the Real Estate Website!";
            header("Location: ../index.php");
            exit();
        } else {
            echo "<script>alert('Error creating account. Please try again.'); window.location.href = 'register.php';</script>";
        }
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/signup.css">
    <link rel="stylesheet" href="../assets/css/popup.css">
    <link rel="stylesheet" href="../assets/css/loader.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <a href="../index.php" class="back-arrow"></a>
        <h2>Create a New Account</h2>
        <form action="register.php" method="post">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="telephone" placeholder="Telephone" required>
            <select name="role" required>
                <option value="User">User</option>
                <option value="Agent">Agent</option>
            </select>
            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Password" required>
            </div>
            <div class="password-container">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="password-strength"><span></span></div>
            <div class="password-match">Passwords do not match!</div>
            <input type="submit" value="Sign Up">
            <p class="error-message">Error: Please ensure all fields are correct.</p>
            <a href="login.php">Already have an account? Login</a>
        </form>
    </div>

    <div id="loader" class="loader-overlay" style="display:none;">
        <div class="loader"></div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.querySelector('input[name="password"]');
        const confirmPasswordInput = document.querySelector('input[name="confirm_password"]');
        const passwordStrength = document.querySelector(".password-strength");
        const passwordMatch = document.querySelector(".password-match");

        passwordInput.addEventListener("input", function () {
            checkPasswordStrength(passwordInput.value);
        });

        confirmPasswordInput.addEventListener("input", function () {
            checkPasswordMatch(passwordInput.value, confirmPasswordInput.value);
        });

        function checkPasswordStrength(password) {
            let strength = "weak";
            let strengthText = "Weak";
            passwordStrength.style.display = "block";
            passwordStrength.innerHTML = "<span></span>";

            if (password.length >= 8) {
                strength = "medium";
                strengthText = "Average";
            }
            if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/)) {
                strength = "strong";
                strengthText = "Strong";
            }

            passwordStrength.innerHTML = `<span class="${strength}"></span><div class="${strength}-text">${strengthText}</div>`;
        }

        function checkPasswordMatch(password, confirmPassword) {
            if (password !== confirmPassword) {
                passwordMatch.style.display = "block";
            } else {
                passwordMatch.style.display = "none";
            }
        }
    });

    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const eyeIcon = input.nextElementSibling.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    function showLoaderAndRedirect() {
        document.getElementById('loader').style.display = 'flex';
        setTimeout(function() {
            window.location.href = "../index.php"; 
        }, 2000); 
    }
    </script>
</body>
</html>
