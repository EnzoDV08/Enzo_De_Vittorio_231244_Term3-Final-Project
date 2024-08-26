<?php


header('Content-Type: application/json'); 
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propertyID = $_POST['property_id'];

    
    if (empty($propertyID) || !is_numeric($propertyID)) {
        echo json_encode(['success' => false, 'error' => 'Invalid property ID']);
        exit();
    }

  
    $sql = "UPDATE property SET Status = 'rejected' WHERE PropertyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $propertyID);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to reject property.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>