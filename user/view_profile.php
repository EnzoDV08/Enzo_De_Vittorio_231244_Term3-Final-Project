<?php


include '../includes/db_connect.php';
include '../includes/header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['UserID'];


$stmt = $conn->prepare("SELECT Name, Email, Telephone, ProfileImage, BackgroundImage, Role FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$stmt->bind_result($name, $email, $telephone, $profileImage, $backgroundImage, $role);
$stmt->fetch();
$stmt->close();

$defaultProfileImage = '../assets/images/imageplaceholder.jpg';
$defaultBackgroundImage = '../assets/images/imageplaceholder.jpg';

$profileImage = $profileImage ? "../uploads/" . htmlspecialchars($profileImage) : $defaultProfileImage;
$backgroundImage = $backgroundImage ? "../uploads/" . htmlspecialchars($backgroundImage) : $defaultBackgroundImage;


$properties = [];
$sql = "SELECT p.*, pi.ImageURL 
        FROM property p 
        LEFT JOIN propertyimages pi ON p.PropertyID = pi.PropertyID 
        WHERE p.AgentID = ?
        GROUP BY p.PropertyID";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($property = $result->fetch_assoc()) {
        $properties[] = $property;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/published_properties.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .profile-header {
            background-image: url('<?php echo $backgroundImage; ?>');
            background-size: cover;
            background-position: center;
            height: 400px;
            position: relative;
            border-bottom: 1px solid #eaeaea;
        }

        .profile-container {
            display: flex;
            align-items: flex-start;
            padding: 20px;
            margin-top: -80px;
            position: relative;
            z-index: 2;
        }

        .profile-image {
            width: 350px;
            height: 350px;
            border-radius: 15px;
            overflow: hidden;
            border: 5px solid #fff;
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 400px;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 3;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-info {
            color: #333; 
            margin-left: 20px;
            margin-top: 80px;
            transition: opacity 0.3s ease;
            cursor: pointer;
        }

        .profile-info:hover {
            opacity: 0.9;
        }

        .profile-info h1 {
            font-size: 58px;
            margin: 0;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .profile-info p {
            font-size: 20px;
            margin: 8px 0;
            line-height: 1.4;
            transition: transform 0.3s ease;
        }

        .profile-info h1:hover,
        .profile-info p:hover {
            transform: translateX(10px);
        }

        .overlay {
            position: absolute;
            top: 10px;
            left: 20px;
            display: flex;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-image:hover .overlay,
        .profile-header:hover .overlay {
            opacity: 1;
        }

        .overlay button {
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .overlay button:hover {
            background-color: rgba(0, 0, 0, 0.8);
            transform: scale(1.1);
        }

        .profile-actions {
            margin-top: 50px;
            position: relative;
        }

        .profile-actions button {
            background-color: #007bff;
            color: #fff;
            padding: 8px 60px;
            font-size: 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .profile-actions button:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        #profile-dropdown {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 10px;
            border-radius: 5px;
            z-index: 10;
            min-width: 150px;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        #profile-dropdown a {
            display: block;
            padding: 8px 12px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s ease;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        #profile-dropdown a:hover {
            background-color: #f4f4f4;
        }

        #profile-dropdown a.logout {
            color: #fff;
            background-color: #ff9800;
        }

        #profile-dropdown a.logout:hover {
            background-color: #e68a00;
        }

        #profile-dropdown a.delete-account {
            color: #fff;
            background-color: #f44336;
        }

        #profile-dropdown a.delete-account:hover {
            background-color: #d32f2f;
        }

        #profile-dropdown.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        #profile-dropdown.hide {
            opacity: 0;
            transform: translateY(-10px);
            pointer-events: none;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.3s ease-in-out;
        }

        .modal-header{
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header {
            border-bottom: 1px solid #eaeaea;
            padding-bottom: 10px;
        }

        .modal-footer {
            border-top: 1px solid #eaeaea;
            padding-top: 10px;
        }

        .modal-body {
            margin: 20px 0;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-delete,
        .btn-logout {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-logout {
            background-color: #ff9800;
        }

        .btn:hover {
            opacity: 0.8;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: translateY(-20%);
            }
            to {
                transform: translateY(0);
            }
        }

        
        .properties-container {
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .add-property-card {
            position: relative;
            margin: 10px;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: #007bff;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%; 
            cursor: pointer;
        }

        .add-property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
            background-color: #0056b3;
        }

        .add-property-card .plus-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .add-property-card h5 {
            margin: 0;
            font-size: 1.25rem;
        }

         .toggle-status-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #28a745;
        border: none;
        color: white;
        padding: 8px 16px;
        font-size: 12px;
        border-radius: 5px;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        z-index: 2;
    }

    .toggle-status-button:hover {
        background-color: #218838;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }

    
    .status-label {
        position: absolute;
        top: 10px;
        left: 10px; 
        padding: 5px 10px;
        border-radius: 5px;
        color: #fff;
        font-size: 12px;
        text-transform: uppercase;
        font-weight: bold;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .status-label.sold {
        background-color: #d9534f;
    }

    .status-label.pending {
        background-color: #f0ad4e;
    }

    .status-label.available {
        background-color: #5cb85c;
    }

    
    .property-card {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .property-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
    }

    .property-image-container {
        position: relative;
        overflow: hidden;
    }

    .property-image {
        width: 100%;
        height: auto;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        object-fit: cover;
    }

    .property-content {
        padding: 15px;
        background-color: #fff;
        border-bottom-left-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .property-title {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .property-address {
        font-size: 14px;
        color: #777;
        margin-bottom: 15px;
    }

    .property-details {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #555;
        margin-bottom: 15px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .property-price-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .property-price {
        font-size: 18px;
        font-weight: bold;
    }

    .view-button {
        font-size: 14px;
        padding: 5px 10px;
    }

        
        
.sell-button {
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: #28a745;
    border: none;
    color: white;
    padding: 8px 16px;
    font-size: 12px;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    z-index: 2;
}

.sell-button:hover {
    background-color: #218838;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
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

        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: white; 
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
    </style>
</head>
<body>

<div class="content-wrapper">
   
    <div class="profile-header" style="background-image: url('<?php echo $backgroundImage; ?>');">
        <div class="overlay">
            <button onclick="updateBackgroundImage()"><i class="fas fa-camera"></i></button>
            <button onclick="removeBackgroundImage()"><i class="fas fa-trash-alt"></i></button>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-image">
            <img src="<?php echo $profileImage; ?>" alt="Profile Image">
            <div class="overlay">
                <button onclick="updateProfileImage()"><i class="fas fa-camera"></i></button>
                <button onclick="removeProfileImage()"><i class="fas fa-trash-alt"></i></button>
            </div>
        </div>

        <div class="profile-info">
            <h1><?php echo htmlspecialchars($name); ?></h1>
            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($telephone); ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($email); ?></p>
            <p><i class="fas fa-star"></i> 4.5 review</p>
            <div class="profile-actions">
                <button onclick="toggleProfileDropdown()">Profile Settings</button>
                <div id="profile-dropdown" class="hide">
                    <a href="edit_profile.php">Edit Profile</a>
                    <a href="#" onclick="confirmLogout()" class="logout">Logout</a>
                    <a href="#" onclick="confirmDeleteAccount()" class="delete-account">Delete Account</a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="properties-container">
        <h2>Your Published Properties</h2>
        <div class="row">
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="add-property-card" onclick="window.location.href='uploadProperty.php'">
                    <i class="fas fa-plus plus-icon"></i>
                    <h5>Add New Property</h5>
                </div>
            </div>

            
            <?php if (count($properties) > 0): ?>
                
                <?php foreach ($properties as $property): ?>
                    <?php 
                        $imagePath = !empty($property['ImageURL']) ? "../" . $property['ImageURL'] : '../assets/images/property-placeholder.jpg'; 
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 property-card-container">
                        <div class="property-card">
                            <div class="property-image-container">
                                <img src="<?php echo $imagePath; ?>" alt="Property Image" class="property-image">
                                <button class="delete-button" data-id="<?php echo $property['PropertyID']; ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                               
                                <?php if ($property['ApprovalStatus'] == 'pending'): ?>
                                    <span class="status-label pending">Pending Approval</span>
                                <?php elseif ($property['ApprovalStatus'] == 'approved'): ?>
                                    <span class="status-label <?php echo $property['Status'] == 'sold' ? 'sold' : 'available'; ?>">
                                        <?php echo $property['Status'] == 'sold' ? 'Sold' : 'Available'; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-label denied">Denied</span>
                                <?php endif; ?>

                                
                                <?php if ($property['ApprovalStatus'] == 'approved' && $property['Status'] != 'sold'): ?>
                                    <button class="btn btn-success sell-button" data-id="<?php echo $property['PropertyID']; ?>">
                                        Mark as Sold
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="property-content">
                                <h5 class="property-title"><?php echo htmlspecialchars($property['Title']); ?></h5>
                                <p class="property-address"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['Address']); ?></p>
                                <div class="property-details">
                                    <div class="detail-item">
                                        <i class="fas fa-ruler-combined"></i>
                                        <span><?php echo $property['SquareMeters'] ?? 'N/A'; ?> sqft</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-bed"></i>
                                        <span><?php echo $property['Bedrooms'] ?? 'N/A'; ?> Beds</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-bath"></i>
                                        <span><?php echo $property['Bathrooms'] ?? 'N/A'; ?> Baths</span>
                                    </div>
                                    <div class="detail-item">
                                        <i class="fas fa-car"></i>
                                        <span><?php echo $property['GarageSpaces'] ?? 'N/A'; ?> Garage</span>
                                    </div>
                                </div>
                                <div class="property-price-container">
                                   <span class="property-price">R <?php echo number_format($property['Price'], 2); ?></span>
                                   <a href="../properties/detail.php?id=<?php echo $property['PropertyID']; ?>" class="btn btn-outline-primary view-button">View Property</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12"><p class="text-center">No properties found.</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div id="popupAlert" class="alert-popup">
    <div class="alert-message" id="alertMessage">This is a popup message!</div>
    <button class="close-alert" onclick="closePopupAlert()">OK</button>
</div>

<?php include '../temp/footer.php';?>
<script>
    function updateProfileImage() {
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'profileImage';
        input.style.display = 'none';
        input.onchange = function () {
            const formData = new FormData();
            formData.append('profileImage', input.files[0]);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload_profile_image.php', true);
            xhr.onload = function () {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.success) {
                    showAlert(response.message);
                    location.reload();
                } else {
                    showAlert(response.message || 'An error occurred during the upload.');
                }
            };
            xhr.send(formData);
        };
        document.body.appendChild(input);
        input.click();
    }

    function removeProfileImage() {
        if (confirm('Are you sure you want to remove your profile image?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'remove_profile_image.php', true);
            xhr.onload = function () {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.success) {
                    showAlert(response.message);
                    location.reload();
                } else {
                    showAlert(response.message || 'An error occurred while removing the profile image.');
                }
            };
            xhr.send();
        }
    }

    function updateBackgroundImage() {
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'backgroundImage';
        input.style.display = 'none';
        input.onchange = function () {
            const formData = new FormData();
            formData.append('backgroundImage', input.files[0]);

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'upload_background_image.php', true);
            xhr.onload = function () {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.success) {
                    showAlert(response.message);
                    location.reload();
                } else {
                    showAlert(response.message || 'An error occurred during the upload.');
                }
            };
            xhr.send(formData);
        };
        document.body.appendChild(input);
        input.click();
    }

    function removeBackgroundImage() {
        if (confirm('Are you sure you want to remove your background image?')) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'remove_background_image.php', true);
            xhr.onload = function () {
                const response = JSON.parse(xhr.responseText);
                if (xhr.status === 200 && response.success) {
                    showAlert(response.message);
                    location.reload();
                } else {
                    showAlert(response.message || 'An error occurred while removing the background image.');
                }
            };
            xhr.send();
        }
    }

    function toggleProfileDropdown() {
        const dropdown = document.getElementById("profile-dropdown");
        dropdown.classList.toggle("show");
        dropdown.classList.toggle("hide");
    }

    function showAlert(message) {
        alert(message);
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = "none";
    }

    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "block";
        } else {
            console.error(`Modal with ID ${modalId} not found`);
        }
    }

    function confirmDeleteAccount() {
        showModal('deleteAccountModal');
    }

    function deleteAccount() {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_account.php", true);
        xhr.onload = function () {
            const response = JSON.parse(xhr.responseText);
            if (xhr.status === 200 && response.success) {
                showAlert("Account deleted successfully.");
                window.location.href = "../index.php";
            } else {
                showAlert(response.message);
            }
        };
        xhr.send();
    }

    function confirmLogout() {
        showModal('logoutModal');
    }

    function logout() {
        window.location.href = "logout.php";
    }

    
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-button');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const propertyID = this.getAttribute('data-id');
                
                if (confirm('Are you sure you want to delete this property?')) {
                    fetch('delete_property.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `property_id=${propertyID}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'removed') {
                            const propertyCard = this.closest('.property-card-container');
                            propertyCard.remove(); 
                            showAlert('Property removed successfully.');
                        } else {
                            showAlert(data.error || 'Failed to remove property.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });

        const sellButtons = document.querySelectorAll('.sell-button');

        sellButtons.forEach(button => {
            button.addEventListener('click', function () {
                const propertyID = this.getAttribute('data-id');

                if (confirm('Are you sure you want to mark this property as sold?')) {
                    fetch('mark_sold.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `property_id=${propertyID}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const statusLabel = this.previousElementSibling;
                            statusLabel.textContent = 'Sold';
                            statusLabel.classList.remove('available');
                            statusLabel.classList.add('sold');
                            this.remove(); 
                            showAlert('Property marked as sold successfully.');
                        } else {
                            showAlert('Failed to mark property as sold.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        });
    });

  

</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
 
    const sellButtons = document.querySelectorAll('.sell-button');

    sellButtons.forEach(button => {
        button.addEventListener('click', function () {
            const propertyID = this.getAttribute('data-id');

            if (confirm('Are you sure you want to mark this property as sold?')) {
                fetch('mark_sold.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `property_id=${propertyID}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const statusLabel = this.closest('.property-image-container').querySelector('.status-label');

                        if (statusLabel) {
                            statusLabel.textContent = 'Sold';
                            statusLabel.classList.remove('available');
                            statusLabel.classList.add('sold');
                        }

                        this.remove();
                        showPopupAlert('Property marked as sold successfully.');
                    } else {
                        showPopupAlert('Failed to mark property as sold.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    });
});


function showPopupAlert(message) {
    const alertMessageElement = document.getElementById('alertMessage');
    const popupAlertElement = document.getElementById('popupAlert');
    
    
    alertMessageElement.textContent = message;
    
    
    popupAlertElement.style.display = 'block';
}

function closePopupAlert() {
    const popupAlertElement = document.getElementById('popupAlert');
    
    
    popupAlertElement.style.display = 'none';
}

</script>

<div id="logoutModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Logout</h4>
            <span class="close" onclick="closeModal('logoutModal')">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to logout?</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('logoutModal')">Cancel</button>
            <button class="btn-logout" onclick="logout()">Logout</button>
        </div>
    </div>
</div>


<div id="deleteAccountModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>Delete Account</h4>
            <span class="close" onclick="closeModal('deleteAccountModal')">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal('deleteAccountModal')">Cancel</button>
            <button class="btn-delete" onclick="deleteAccount()">Delete Account</button>
        </div>
    </div>
</div>

</body>
</html>
