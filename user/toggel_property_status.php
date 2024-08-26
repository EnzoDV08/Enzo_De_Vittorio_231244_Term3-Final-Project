<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $propertyID = $_POST['property_id'];
    $status = $_POST['status'];

    
    $stmt = $conn->prepare("UPDATE property SET Status = ? WHERE PropertyID = ?");
    $stmt->bind_param("si", $status, $propertyID);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
}
