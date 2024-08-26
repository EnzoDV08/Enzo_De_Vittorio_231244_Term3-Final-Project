<?php
include '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $propertyID = $_POST['property_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $status = 'approved';
    } elseif ($action == 'deny') {
        $status = 'denied';
    }

    $sql = "UPDATE property SET ApprovalStatus = ? WHERE PropertyID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $status, $propertyID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }

    $stmt->close();
    $conn->close();
}
?>
