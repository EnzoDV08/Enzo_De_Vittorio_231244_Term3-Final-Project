<?php
session_start();
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['property_id'])) {
    $propertyID = intval($_POST['property_id']);
    $userID = $_SESSION['UserID'];

    
    $stmt = $conn->prepare("SELECT PropertyID FROM property WHERE PropertyID = ? AND AgentID = ?");
    $stmt->bind_param("ii", $propertyID, $userID);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();

        
        $updateStmt = $conn->prepare("UPDATE property SET Status = 'sold' WHERE PropertyID = ?");
        $updateStmt->bind_param("i", $propertyID);
        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'debug' => 'Property marked as sold.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update property status.', 'debug' => 'Update statement failed.']);
        }
        $updateStmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Property not found or you do not have permission to mark it as sold.', 'debug' => 'Property not found or permission issue.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.', 'debug' => 'Request method or property_id missing.']);
}

$conn->close();
