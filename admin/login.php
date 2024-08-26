<?php


session_start();
include '../includes/db_connect.php';

header('Content-Type: application/json');

$response = ["success" => false, "message" => ""];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

   
    error_log("Email: " . $email);
    error_log("Password: " . $password);

    if (empty($email) || empty($password)) {
        $response['message'] = 'Email or password cannot be empty.';
    } else {
        
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
                $response['message'] = 'Password incorrect.';
            }
        } else {
            $response['message'] = 'No account found with that email.';
        }

        $stmt->close();
    }
}

$conn->close();
echo json_encode($response);
?>
