<?php


include '../includes/db_connect.php';
session_start();

$userID = $_SESSION['UserID'] ?? null;
$password = $_POST['password'] ?? '';

if ($userID && $password) {
    
    $stmt = $conn->prepare("SELECT Password FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    
    if (password_verify($password, $hashedPassword)) {
        
        $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        if ($stmt->execute()) {
           
            session_destroy();
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting account.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'User ID or password is missing.']);
}

$conn->close();
?>

