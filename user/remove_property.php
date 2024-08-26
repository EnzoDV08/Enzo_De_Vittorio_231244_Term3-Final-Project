<?php
session_start();
header('Content-Type: application/json');

include '../includes/db_connect.php';

if (!isset($_SESSION['UserID'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_id'])) {
    $propertyID = intval($_POST['property_id']);
    $userID = $_SESSION['UserID'];

   
    $stmt = $conn->prepare("SELECT PropertyID FROM property WHERE PropertyID = ? AND AgentID = ?");
    $stmt->bind_param("ii", $propertyID, $userID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        
        $deleteImagesStmt = $conn->prepare("DELETE FROM propertyimages WHERE PropertyID = ?");
        $deleteImagesStmt->bind_param("i", $propertyID);
        $deleteImagesStmt->execute();
        $deleteImagesStmt->close();

        
        $deletePropertyStmt = $conn->prepare("DELETE FROM property WHERE PropertyID = ?");
        $deletePropertyStmt->bind_param("i", $propertyID);
        if ($deletePropertyStmt->execute()) {
            echo json_encode(['status' => 'removed']);
        } else {
            echo json_encode(['error' => 'Failed to remove property.']);
        }
        $deletePropertyStmt->close();
    } else {
        echo json_encode(['error' => 'Property not found or you do not have permission to delete it.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method or missing property ID.']);
}

$conn->close();
?>
