<?php


session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['UserID'])) {
    echo json_encode(['success' => false, 'message' => 'You are not logged in.']);
    exit();
}

$userID = $_SESSION['UserID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $fileInfo = pathinfo($_FILES['profileImage']['name']);
        $extension = strtolower($fileInfo['extension']);

        if (!in_array($extension, $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file extension.']);
            exit();
        }

        
        $stmt = $conn->prepare("SELECT ProfileImage FROM users WHERE UserID = ?");
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $stmt->bind_result($currentProfileImage);
        $stmt->fetch();
        $stmt->close();

        
        if ($currentProfileImage && file_exists('../uploads/' . $currentProfileImage)) {
            unlink('../uploads/' . $currentProfileImage);
        }

        
        $newFileName = uniqid('profile_', true) . '.' . $extension;
        $destination = '../uploads/' . $newFileName;

        if (move_uploaded_file($_FILES['profileImage']['tmp_name'], $destination)) {
           
            $stmt = $conn->prepare("UPDATE users SET ProfileImage = ? WHERE UserID = ?");
            $stmt->bind_param("si", $newFileName, $userID);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Profile image updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database update failed.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No file uploaded or there was an upload error.']);
    }
}
$conn->close();
?>