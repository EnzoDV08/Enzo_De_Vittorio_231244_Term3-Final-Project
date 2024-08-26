<?php


session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $propertyID = intval($_POST['propertyID']);
    $message = trim($_POST['message']);
    $userID = $_SESSION['UserID']; 

    if (!empty($message) && $propertyID > 0 && $userID > 0) {
        $stmt = $conn->prepare("INSERT INTO inquiry (PropertyID, UserID, Message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $propertyID, $userID, $message);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Inquiry sent successfully!";
        } else {
            $_SESSION['error_message'] = "Failed to send inquiry.";
        }
        $stmt->close();
    } else {
        $_SESSION['error_message'] = "Invalid input data.";
    }
}

header("Location: ../properties/detail.php?id=$propertyID");
exit();
?>
