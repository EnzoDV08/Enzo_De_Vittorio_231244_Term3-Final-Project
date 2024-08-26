<?php
session_start();
include '../includes/db_connect.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['adminEmail'];
    $password = $_POST['adminPassword'];

   
    $stmt = $conn->prepare("SELECT AdminID, Password FROM admin WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($adminID, $hashed_password);
        $stmt->fetch();

        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['AdminID'] = $adminID;
            header("Location: admin_dashboard.php");
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
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #1f4037, #99f2c8);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            position: relative;
            animation: slideIn 0.6s ease-out;
        }

        h2 {
            margin-bottom: 20px;
            color: #1f4037;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="email"],
        input[type="password"] {
            padding: 14px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
            transition: border 0.3s ease;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border: 1px solid #1f4037;
            outline: none;
        }

        input[type="submit"] {
            background-color: #1f4037;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #145e5b;
        }

        .error-message {
            color: red;
            margin-bottom: 15px;
            font-size: 14px;
            display: none;
        }

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
        }

        .back-arrow i {
            margin-right: 8px;
            font-size: 28px;
        }

        .back-arrow:hover {
            transform: scale(1.1);
            color: #2b2b2b;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
       
        <a href="../index.php" class="back-arrow"><i class="fas fa-arrow-left"></i></a>

        <h2>Admin Login</h2>
        <form action="admin_login.php" method="post">
            <input type="email" name="adminEmail" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            <input type="password" name="adminPassword" placeholder="Password" required>
            <input type="submit" value="Login">
            <p class="error-message"></p>
        </form>
    </div>
</body>
</html>