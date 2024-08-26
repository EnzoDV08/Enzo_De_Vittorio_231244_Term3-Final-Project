<?php
session_start();
if (!isset($_SESSION['AdminID'])) {
    header("Location: admin_login.php");
    exit();
}

include '../includes/db_connect.php';
include 'includes/admin_header.php';

$userID = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($userID <= 0) {
    echo "<div class='container mt-5'><h2>Invalid User ID</h2></div>";
    include 'includes/admin_footer.php';
    exit();
}


$stmt = $conn->prepare("SELECT Name, Email, Telephone, ProfileImage, BackgroundImage FROM users WHERE UserID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "<div class='container mt-5'><h2>User Not Found</h2></div>";
    include 'includes/admin_footer.php';
    exit();
}

$defaultProfileImage = '../assets/images/imageplaceholder.jpg';
$defaultBackgroundImage = '../assets/images/imageplaceholder.jpg';

$profileImage = $user['ProfileImage'] ? "../uploads/" . htmlspecialchars($user['ProfileImage']) : $defaultProfileImage;
$backgroundImage = $user['BackgroundImage'] ? "../uploads/" . htmlspecialchars($user['BackgroundImage']) : $defaultBackgroundImage;


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
    <title>Admin View Profile</title>
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
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 3;
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
        }

        .profile-info h1 {
            font-size: 58px;
            margin: 0;
            font-weight: bold;
        }

        .profile-info p {
            font-size: 20px;
            margin: 8px 0;
            line-height: 1.4;
        }

        .properties-container {
            margin-top: 50px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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

        .status-label.denied {
            background-color: #d9534f;
        }
    </style>
</head>
<body>

<div class="content-wrapper">

    <div class="profile-header"></div>

    <div class="profile-container">
        <div class="profile-image">
            <img src="<?php echo $profileImage; ?>" alt="Profile Image">
        </div>

        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['Name']); ?></h1>
            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($user['Telephone']); ?></p>
            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['Email']); ?></p>
        </div>
    </div>

    <div class="properties-container">
        <h2>User's Published Properties</h2>
        <div class="row">
            
            <?php if (count($properties) > 0): ?>
                <?php foreach ($properties as $property): ?>
                    <?php 
                        $imagePath = !empty($property['ImageURL']) ? $property['ImageURL'] : '../assets/images/property-placeholder.jpg'; 
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="property-card">
                            <div class="property-image-container">
                                <img src="<?php echo $imagePath; ?>" alt="Property Image" class="property-image">

                               
                                <?php if ($property['ApprovalStatus'] == 'pending'): ?>
                                    <span class="status-label pending">Pending Approval</span>
                                <?php elseif ($property['ApprovalStatus'] == 'approved'): ?>
                                    <span class="status-label <?php echo $property['Status'] == 'sold' ? 'sold' : 'available'; ?>">
                                        <?php echo $property['Status'] == 'sold' ? 'Sold' : 'Available'; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="status-label denied">Denied</span>
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

<?php
$conn->close();
include 'includes/admin_footer.php';
?>

</body>
</html>
