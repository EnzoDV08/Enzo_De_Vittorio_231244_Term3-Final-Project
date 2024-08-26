<?php


session_start();
include '../includes/db_connect.php';

header('Content-Type: application/json');

$response = ["success" => false];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $propertyID = $_POST['property_id'] ?? null;

    if ($propertyID) {
        $stmt = $conn->prepare("UPDATE property SET Status = 'approved' WHERE PropertyID = ?");
        $stmt->bind_param("i", $propertyID);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = $stmt->error;
        }

        $stmt->close();
    } else {
        $response['error'] = "Invalid property ID.";
    }
}

$conn->close();
echo json_encode($response);
?>
