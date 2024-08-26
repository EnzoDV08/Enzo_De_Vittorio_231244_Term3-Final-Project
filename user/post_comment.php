<?php
session_start();
include '../includes/db_connect.php';

$response = ['success' => false]; 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $propertyID = intval($_POST['propertyID']);
    $comment = trim($_POST['comment']);
    $userID = $_SESSION['UserID'] ?? 0;
    $userName = $_SESSION['UserName'] ?? 'Anonymous'; 
    $parentID = intval($_POST['parentID']);

    if (!empty($comment) && $propertyID > 0 && $userID > 0) {
        $stmt = $conn->prepare("INSERT INTO reviews (PropertyID, UserID, Comment, ParentID) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $propertyID, $userID, $comment, $parentID);
        if ($stmt->execute()) {
            $reviewID = $stmt->insert_id; 
            $response['success'] = true;
           
            $response['commentHTML'] = '<div class="comment" data-comment-id="'.$reviewID.'" style="margin-left: '.($parentID == 0 ? '0' : '20').'px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px;">';
            $response['commentHTML'] .= '<p><strong>'.htmlspecialchars($userName).':</strong></p>';
            $response['commentHTML'] .= '<p>'.htmlspecialchars($comment).'</p>';
            $response['commentHTML'] .= '<p class="comment-reply" style="color: #007bff; cursor: pointer;">Reply</p>';
            $response['commentHTML'] .= '<div class="reply-form" style="display:none; margin-left: 20px;"></div>'; 
            $response['commentHTML'] .= '</div>';
        } else {
            $response['error'] = "Failed to post comment.";
        }
        $stmt->close();
    } else {
        $response['error'] = "Invalid input data.";
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
