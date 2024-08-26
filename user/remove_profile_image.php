<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['UserID'])) {
    echo json_encode(['success' => false, 'message' => 'You are not logged in.']);
    exit();
}

$userID = $_SESSION['UserID'];


$stmt = $conn->prepare("SELECT ProfileImage FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($currentProfileImage);
$stmt->fetch();
$stmt->close();


if ($currentProfileImage && file_exists('../uploads/' . $currentProfileImage)) {
    unlink('../uploads/' . $currentProfileImage);
}


$stmt = $conn->prepare("UPDATE users SET ProfileImage = NULL WHERE UserID = ?");
$stmt->bind_param("i", $userID);
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile image removed successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update the database.']);
}
$stmt->close();
$conn->close();
?>
