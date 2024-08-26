<?php
session_start();
include '../includes/header.php'; 
include '../includes/db_connect.php';

$sql = "SELECT p.PropertyID, p.Title, p.Price, p.Bedrooms, p.Bathrooms, p.SquareMeters, p.City, p.State, p.Address, p.Status, MIN(pi.ImageURL) AS ImageURL 
        FROM property p 
        LEFT JOIN propertyimages pi ON p.PropertyID = pi.PropertyID 
        WHERE p.ApprovalStatus = 'approved'";


$filters = [];
$params = [];


if (!empty($_GET['search'])) {
    $filters[] = "(p.Title LIKE ? OR p.City LIKE ? OR p.Address LIKE ?)";
    $searchTerm = '%' . $_GET['search'] . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}


if (!empty($_GET['location'])) {
    $filters[] = "p.City = ?";
    $params[] = $_GET['location'];
}

if (!empty($_GET['property_type'])) {
    $filters[] = "p.PropertyType = ?";
    $params[] = $_GET['property_type'];
}

if (!empty($_GET['price_range'])) {
    $filters[] = "p.Price <= ?";
    $params[] = $_GET['price_range'];
}

if (!empty($_GET['bedrooms'])) {
    $filters[] = "p.Bedrooms >= ?";
    $params[] = $_GET['bedrooms'];
}

if (!empty($_GET['bathrooms'])) {
    $filters[] = "p.Bathrooms >= ?";
    $params[] = $_GET['bathrooms'];
}

if (count($filters) > 0) {
    $sql .= " AND " . implode(" AND ", $filters);
}

$sql .= " GROUP BY p.PropertyID ORDER BY p.PropertyID DESC";
$stmt = $conn->prepare($sql);


if (count($params) > 0) {
    $types = str_repeat('s', count($params)); 
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Property Listings</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/navbar.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/style.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/property-card.css">
    <style>
        .property-image-container {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            overflow: hidden;
        }

        .property-image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .property-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
            margin-bottom: 30px;
            opacity: 0;
            transform: translateY(50px);
            animation: fadeInUp 0.6s ease-in-out forwards;
        }

        .property-card:hover {
            transform: translateY(-5px);
        }

        .status-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: green;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .property-content {
            padding: 15px;
            background-color: #fff;
            border-top: 1px solid #eee;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }

        .container-fluid.mt-5 {
            margin-top: 150px; 
        }

        .page-title {
            margin-top: 100px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: bold;
        }

        
        .filters {
            position: -webkit-sticky;
            position: sticky;
            top: 20px;
            z-index: 1000;
            animation: fadeIn 1s ease-in-out;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

      
        .btn-primary, .btn-secondary {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-primary:hover, .btn-secondary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<div class="container-fluid mt-5">
    <div class="page-title">
        Property Listings
    </div>
    <div class="row">
       
        <div class="col-lg-3">
            <div class="filters bg-light p-4 rounded">
                <h4>Filters</h4>
                <form action="listing.php" method="GET">
                   
                    <div class="form-group">
                        <label for="search">Search</label>
                        <input type="text" name="search" id="search" class="form-control" placeholder="Enter search term..."
                            value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                            onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter search term...'" />
                    </div>

                    
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" class="form-control" placeholder="Enter South African city..." value="<?php echo isset($_GET['location']) ? htmlspecialchars($_GET['location']) : ''; ?>">
                    </div>

                    
                    <div class="form-group">
                        <label for="property_type">Property Type</label>
                        <select id="property_type" name="property_type" class="form-control">
                            <option value="">Select Type</option>
                            <option value="house" <?php echo isset($_GET['property_type']) && $_GET['property_type'] == 'house' ? 'selected' : ''; ?>>House</option>
                            <option value="apartment" <?php echo isset($_GET['property_type']) && $_GET['property_type'] == 'apartment' ? 'selected' : ''; ?>>Apartment</option>
                            <option value="land" <?php echo isset($_GET['property_type']) && $_GET['property_type'] == 'land' ? 'selected' : ''; ?>>Land</option>
                        </select>
                    </div>

                    
                    <div class="form-group">
                        <label for="price_range">Price Range (Max): <span id="price_value">R <?php echo isset($_GET['price_range']) ? number_format($_GET['price_range'], 2) : '1,000,000'; ?></span></label>
                        <input type="range" id="price_range" name="price_range" class="form-control-range" min="100000" max="1000000" step="50000"
                            value="<?php echo isset($_GET['price_range']) ? $_GET['price_range'] : '1000000'; ?>"
                            oninput="document.getElementById('price_value').innerText = 'R ' + parseFloat(this.value).toLocaleString()">
                    </div>

                    
                    <div class="form-group">
                        <label for="bedrooms">Bedrooms</label>
                        <select id="bedrooms" name="bedrooms" class="form-control">
                            <option value="">Any</option>
                            <option value="1" <?php echo isset($_GET['bedrooms']) && $_GET['bedrooms'] == '1' ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?php echo isset($_GET['bedrooms']) && $_GET['bedrooms'] == '2' ? 'selected' : ''; ?>>2</option>
                            <option value="3" <?php echo isset($_GET['bedrooms']) && $_GET['bedrooms'] == '3' ? 'selected' : ''; ?>>3</option>
                            <option value="4" <?php echo isset($_GET['bedrooms']) && $_GET['bedrooms'] == '4' ? 'selected' : ''; ?>>4</option>
                            <option value="5" <?php echo isset($_GET['bedrooms']) && $_GET['bedrooms'] == '5' ? 'selected' : ''; ?>>5+</option>
                        </select>
                    </div>

                   
                    <div class="form-group">
                        <label for="bathrooms">Bathrooms</label>
                        <select id="bathrooms" name="bathrooms" class="form-control">
                            <option value="">Any</option>
                            <option value="1" <?php echo isset($_GET['bathrooms']) && $_GET['bathrooms'] == '1' ? 'selected' : ''; ?>>1</option>
                            <option value="2" <?php echo isset($_GET['bathrooms']) && $_GET['bathrooms'] == '2' ? 'selected' : ''; ?>>2</option>
                            <option value="3" <?php echo isset($_GET['bathrooms']) && $_GET['bathrooms'] == '3' ? 'selected' : ''; ?>>3</option>
                            <option value="4" <?php echo isset($_GET['bathrooms']) && $_GET['bathrooms'] == '4' ? 'selected' : ''; ?>>4</option>
                            <option value="5" <?php echo isset($_GET['bathrooms']) && $_GET['bathrooms'] == '5' ? 'selected' : ''; ?>>5+</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                    <button type="reset" class="btn btn-secondary btn-block" onclick="window.location.href='listing.php';">Clear Filters</button>
                </form>
            </div>
        </div>

        
        <div class="col-lg-9">
            <div class="row g-3">
                <?php
                if ($result->num_rows > 0) {
                    while ($property = $result->fetch_assoc()) {
                        $imagePath = !empty($property['ImageURL']) ? '../' . $property['ImageURL'] : '/real_estate_website/assets/images/property-placeholder.jpg';
                        ?>
                        <div class="col-md-4">
                            <div class="property-card">
                                <div class="property-image-container">
                                    <img src="<?php echo $imagePath; ?>" alt="Property Image" class="property-image">
                                    <?php if ($property['Status'] == 'sold'): ?>
                                        <span class="status-label sold">Sold</span>
                                    <?php else: ?>
                                        <span class="status-label available">Available</span>
                                    <?php endif; ?>
                                </div>

                                <div class="property-content">
                                    <h5 class="property-title"><?php echo htmlspecialchars($property['Title']); ?></h5>
                                    <p class="property-address"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['Address']); ?></p>
                                   <div class="property-details">
    <div class="detail-item">
        <i class="fas fa-ruler-combined"></i>
        <span><?php echo isset($property['SquareMeters']) ? htmlspecialchars($property['SquareMeters']) : 'N/A'; ?> sqft</span>
    </div>
    <div class="detail-item">
        <i class="fas fa-bed"></i>
        <span><?php echo isset($property['Bedrooms']) ? htmlspecialchars($property['Bedrooms']) : 'N/A'; ?> Beds</span>
    </div>
    <div class="detail-item">
        <i class="fas fa-bath"></i>
        <span><?php echo isset($property['Bathrooms']) ? htmlspecialchars($property['Bathrooms']) : 'N/A'; ?> Baths</span>
    </div>
</div>

                                    <div class="property-price-container">
                                        <span class="property-price">R <?php echo number_format($property['Price'], 2); ?></span>
                                        <a href="../properties/detail.php?id=<?php echo $property['PropertyID']; ?>" class="btn btn-outline-primary view-button">View Property</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12'><p class='text-center'>No properties found.</p></div>";
                }

                $stmt->close();
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../temp/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="/real_estate_website/assets/js/navbar.js"></script>

</body>
</html>
