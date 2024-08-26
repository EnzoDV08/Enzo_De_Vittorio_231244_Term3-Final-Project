<?php


session_start();
include '../includes/header.php';
include '../includes/db_connect.php';

$propertyID = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($propertyID <= 0) {
    echo "Invalid property ID.";
    exit();
}


$sql = "SELECT p.*, u.Name as AgentName, u.Email as AgentEmail, u.Telephone as AgentPhone, u.ProfileImage as AgentProfileImage 
        FROM property p 
        INNER JOIN users u ON p.AgentID = u.UserID 
        WHERE p.PropertyID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $propertyID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
} else {
    echo "Property not found.";
    exit();
}


$sqlImages = "SELECT ImageURL FROM propertyimages WHERE PropertyID = ?";
$stmtImages = $conn->prepare($sqlImages);
$stmtImages->bind_param("i", $propertyID);
$stmtImages->execute();
$resultImages = $stmtImages->get_result();
$images = [];
while ($row = $resultImages->fetch_assoc()) {
    $images[] = '../' . $row['ImageURL'];
}


$sqlComments = "SELECT r.*, u.Name as ReviewerName, u.ProfileImage as ReviewerProfileImage 
                FROM reviews r 
                INNER JOIN users u ON r.UserID = u.UserID 
                WHERE r.PropertyID = ?";
$stmtComments = $conn->prepare($sqlComments);
$stmtComments->bind_param("i", $propertyID);
$stmtComments->execute();
$resultComments = $stmtComments->get_result();
$comments = [];
while ($row = $resultComments->fetch_assoc()) {
    $comments[] = $row;
}

$stmt->close();
$stmtImages->close();
$stmtComments->close();
$conn->close();

function displayComments($comments, $parentID = 0, $level = 0) {
    foreach ($comments as $comment) {
        if ($comment['ParentID'] == $parentID) {
            echo '<div class="comment" style="margin-left: ' . ($level * 20) . 'px; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 10px;">';
            echo '<img src="../uploads/' . htmlspecialchars($comment['ReviewerProfileImage']) . '" alt="Reviewer Profile Image" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 10px;">';
            echo '<div class="comment-content" style="display: inline-block; vertical-align: top;">';
            echo '<p><strong>' . htmlspecialchars($comment['ReviewerName']) . ':</strong></p>';
            echo '<p>' . htmlspecialchars($comment['Comment']) . '</p>';
            echo '<p class="comment-reply" style="color: #007bff; cursor: pointer;">Reply</p>';
            echo '</div>';
            echo '<div class="reply-form" style="display:none; margin-left: 20px;">';
            echo '<form action="../user/post_comment.php" method="post">';
            echo '<div class="form-group" style="margin-bottom: 10px;">';
            echo '<label for="comment">Write your reply:</label>';
            echo '<textarea name="comment" placeholder="Write your reply..." rows="2" style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc;" required></textarea>';
            echo '</div>';
            echo '<input type="hidden" name="propertyID" value="' . $comment['PropertyID'] . '">';
            echo '<input type="hidden" name="parentID" value="' . $comment['ReviewID'] . '">';
            echo '<button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Submit</button>';
            echo '</form>';
            echo '</div>';

            echo '</div>';

            
            displayComments($comments, $comment['ReviewID'], $level + 1);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($property['Title']); ?> - Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            color: #333;
            margin-top: 100px;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .property-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }

        .property-info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .property-info {
            flex: 2;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }

        .property-info p {
            font-size: 18px;
            margin-bottom: 15px;
            color: #34495e;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .property-description {
            margin-top: 20px;
            line-height: 1.6;
            font-size: 16px;
            color: #555;
        }

        .carousel-inner img {
            height: 500px;
            object-fit: cover;
            border-radius: 8px;
        }

        .carousel-thumbnails {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .carousel-thumbnails img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            margin: 0 5px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s ease;
        }

        .carousel-thumbnails img.active {
            opacity: 1;
            border: 2px solid #007bff;
        }

       
        .agent-info {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .agent-info img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .agent-info h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .agent-info p {
            margin-bottom: 8px;
            color: #34495e;
        }

        .agent-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .agent-buttons a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .agent-buttons a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        
        .inquiry-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .inquiry-form-popup {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            width: 80%;
            max-width: 400px;
            text-align: center;
        }

        .inquiry-form-popup h4 {
            margin-bottom: 20px;
        }

        .inquiry-form-popup textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            resize: none;
        }

        .inquiry-form-popup button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .inquiry-form-popup button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .inquiry-form-popup .close-inquiry {
            margin-top: 20px;
            cursor: pointer;
            color: #007bff;
        }

        
        .comments-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        .comments-section h4 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #2c3e50;
        }

        .comment {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .comment img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
        }

        .comment-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
        }

        .comment-content p {
            margin: 0 0 10px;
        }

        .comment-reply {
            font-size: 14px;
            color: #007bff;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
        }

        .reply-form {
            margin-top: 20px;
            display: none;
        }

        .reply-form.active {
            display: block;
        }

        .reply-form textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            resize: none;
        }

        .reply-form button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .reply-form button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        
        .alert-popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            z-index: 9999;
            display: none;
            animation: fadeIn 0.5s ease-in-out;
            text-align: center;
            width: 80%;
            max-width: 400px;
        }

        .alert-popup .alert-message {
            font-size: 16px;
            margin-bottom: 20px;
        }

        .alert-popup .close-alert {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .alert-popup .close-alert:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translate(-50%, -55%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, -50%);
            }
        }

        
        .status-label {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            border-radius: 5px;
            color: #fff;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
            z-index: 1000; 
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .status-label.sold {
            background-color: #d9534f;
        }

        .status-label.available {
            background-color: #5cb85c;
        }

        .status-label.pending {
            background-color: #f0ad4e;
        }


        .comment-input-container {
    background-color: #f0f2f5;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
}

.comment-input-container textarea {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 20px;
    resize: none;
    outline: none;
    background-color: #fff;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    font-size: 14px;
}

.comment-input-container button {
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.comment-input-container button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>

<div class="container">
    <div class="carousel-inner">
        <?php if ($property['Status'] == 'sold'): ?>
            <div class="status-label sold">Sold</div>
        <?php elseif ($property['ApprovalStatus'] == 'pending'): ?>
            <div class="status-label pending">Pending</div>
        <?php else: ?>
            <div class="status-label available">Available</div>
        <?php endif; ?>

        <div id="propertyImagesCarousel" class="carousel mb-4">
            <div class="carousel-inner">
                <?php
                $first = true;
                foreach ($images as $image) {
                    echo '<div class="carousel-item '.($first ? 'active' : '').'">';
                    echo '<img src="'.$image.'" class="d-block w-100" alt="Property Image">';
                    echo '</div>';
                    $first = false;
                }
                ?>
            </div>

           
            <div class="carousel-thumbnails">
                <?php
                $thumbIndex = 0;
                foreach ($images as $image) {
                    echo '<img src="'.$image.'" class="'.($thumbIndex === 0 ? 'active' : '').'" data-target="#propertyImagesCarousel" data-slide-to="'.$thumbIndex.'">';
                    $thumbIndex++;
                }
                ?>
            </div>
        </div>
    </div>

    <div class="property-info-container">
        <div class="property-info">
            <h1 class="property-title"><?php echo htmlspecialchars($property['Title']); ?></h1>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($property['Address']); ?></p>
            <p><strong>Price:</strong> R<?php echo number_format($property['Price'], 2); ?></p>
            <p><strong>Bedrooms:</strong> <?php echo $property['Bedrooms']; ?></p>
            <p><strong>Bathrooms:</strong> <?php echo $property['Bathrooms']; ?></p>
            <p><strong>Size:</strong> <?php echo $property['SquareMeters']; ?> m²</p>
            <div class="property-description">
                <p><?php echo nl2br(htmlspecialchars($property['Description'])); ?></p>
            </div>
        </div>

        <div class="agent-info">
            <img src="../uploads/<?php echo htmlspecialchars($property['AgentProfileImage']); ?>" alt="Agent Profile Image">
            <div class="agent-details">
                <h3><?php echo htmlspecialchars($property['AgentName']); ?></h3>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($property['AgentPhone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($property['AgentEmail']); ?></p>
            </div>
            <div class="agent-buttons">
                <a href="#" id="contact-agent-button">Contact Agent</a>
                <a href="/real_estate_website/user/view_agent_profile.php?UserID=<?php echo $property['AgentID']; ?>">View Agent Profile</a>

            </div>
        </div>
    </div>

   <div class="comments-section">
    <h4>Comments</h4>
    <?php if (!empty($comments)): ?>
        <?php displayComments($comments); ?>
    <?php else: ?>
        <p>No comments yet. Be the first to comment!</p>
    <?php endif; ?>

    <div class="comment-input-container">
        <form action="../user/post_comment.php" method="post" id="commentForm">
            <div class="form-group">
                <textarea name="comment" id="commentInput" placeholder="Write a comment..." rows="2" required></textarea>
            </div>
            <input type="hidden" name="propertyID" value="<?php echo $propertyID; ?>">
            <input type="hidden" name="parentID" value="0">
            <button type="submit">Post</button>
        </form>
    </div>
</div>



<div class="inquiry-overlay">
    <div class="inquiry-form-popup">
        <h4>Contact Agent</h4>
        <form action="#" method="post" id="dummyInquiryForm">
            <div class="form-group">
                <label for="inquiryMessage">Your Message:</label>
                <textarea name="inquiryMessage" id="inquiryMessage" rows="4" required></textarea>
            </div>
            <button type="submit">Send Inquiry</button>
        </form>
        <p class="close-inquiry">Close</p>
    </div>
</div>

<?php include '../temp/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    document.getElementById('commentForm').addEventListener('submit', function (e) {
        e.preventDefault(); 

        const formData = new FormData(this);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        
                        const commentsContainer = document.querySelector('.comments-section');
                        const parentID = formData.get('parentID');

                        if (parentID === "0") {
                           
                            commentsContainer.insertAdjacentHTML('beforeend', response.commentHTML);
                        } else {
                           
                            const parentComment = document.querySelector(`.comment[data-id="${parentID}"]`);
                            const replyContainer = parentComment.querySelector('.reply-container');
                            if (replyContainer) {
                                replyContainer.insertAdjacentHTML('beforeend', response.commentHTML);
                            } else {
                                parentComment.insertAdjacentHTML('beforeend', response.commentHTML);
                            }
                        }

                       
                        document.getElementById('commentInput').value = '';
                    } else {
                        alert('Failed to post comment.');
                    }
                } catch (e) {
                    console.error('Failed to parse response:', xhr.responseText);
                    alert('An error occurred while processing your request.');
                }
            } else {
                alert('Failed to send AJAX request.');
            }
        };
        xhr.onerror = function () {
            alert('An error occurred during the request.');
        };
        xhr.send(formData);
    });

    
    document.querySelectorAll('.comment-reply').forEach(function (replyButton) {
        replyButton.addEventListener('click', function () {
            const replyForm = this.closest('.comment').querySelector('.reply-form');
            replyForm.classList.toggle('active');
            replyForm.querySelector('textarea').focus(); 
        });
    });

    
    document.querySelectorAll('.carousel-thumbnails img').forEach(function (thumbnail) {
        thumbnail.addEventListener('click', function () {
            document.querySelectorAll('.carousel-thumbnails img').forEach(function (thumb) {
                thumb.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    
    document.getElementById('contact-agent-button').addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector('.inquiry-overlay').style.display = 'flex';
    });

    
    document.querySelector('.close-inquiry').addEventListener('click', function () {
        document.querySelector('.inquiry-overlay').style.display = 'none';
    });

    
    document.getElementById('dummyInquiryForm').addEventListener('submit', function (e) {
        e.preventDefault();
        alert('Inquiry sent successfully!');
        document.querySelector('.inquiry-overlay').style.display = 'none';
    });
});



</script>

</body>
</html>
