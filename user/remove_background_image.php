<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['UserID'])) {
    echo json_encode(['success' => false, 'message' => 'You are not logged in.']);
    exit();
}

$userID = $_SESSION['UserID'];


$stmt = $conn->prepare("SELECT BackgroundImage FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($currentBackgroundImage);
$stmt->fetch();
$stmt->close();


if ($currentBackgroundImage && file_exists('../uploads/' . $currentBackgroundImage)) {
    unlink('../uploads/' . $currentBackgroundImage);
}


$stmt = $conn->prepare("UPDATE users SET BackgroundImage = NULL WHERE UserID = ?");
$stmt->bind_param("i", $userID);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Background image removed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update the database.']);
}
$stmt->close();
$conn->close();
?>
