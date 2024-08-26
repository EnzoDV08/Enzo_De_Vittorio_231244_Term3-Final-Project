<?php
// index.php

session_start();
include 'includes/header.php'; 
include 'includes/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Estate Website</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/navbar.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/style.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/property-card.css">
    <style>
        
        body, h1, h2, h3, h4, h5, p, a, button {
            font-family: 'Roboto', sans-serif;
            
        }

        h1, h2, h3, h4, h5 {
            font-weight: bold;
        }

        h1, .section-title-left, .section-title {
            font-size: 2.5rem; 
        }

        h2 {
            font-size: 2rem; 
        }

        h3, h4, h5 {
            font-size: 1.75rem; 
        }

        p, a, button {
            font-size: 1rem;
        }

        .border-radius, .property-card, .info-box, .action-box, .popup, .stat-box, .hero-image img, .img-fluid {
            border-radius: 12px; 
        }

        .icon-container, .stat-icon-wrapper {
            border-radius: 50%;
        }

       
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.5rem;
        }

       
        .info-title {
            font-size: 2rem;
        }

        .info-subtitle {
            font-size: 1.25rem;
        }

     
        .action-title {
            font-size: 1.5rem;
        }

        
        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .stat-description {
            font-size: 1rem;
            color: #6c757d;
        }

        
        .achievement-box h3 {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .achievement-box p {
            font-size: 1rem;
            color: #6c757d;
        }

        
        .popup {
            font-size: 1.25rem;
            border-radius: 12px;
        }

        
        .view-button, .hero-buttons .btn {
            border-radius: 12px;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['popup_message'])): ?>
<div class="popup-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: flex; justify-content: center; align-items: center; z-index: 10000;">
    <div class="popup" style="background: #fff; padding: 20px; border-radius: 12px; text-align: center; max-width: 400px; width: 90%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); animation: fadeIn 0.5s ease;">
        <h3><?php echo $_SESSION['popup_message']; ?></h3>
        <button onclick="closePopup()" style="margin-top: 20px; padding: 10px 20px; background-color: #1f4037; color: #fff; border: none; border-radius: 12px; cursor: pointer;">Continue</button>
    </div>
</div>
<?php
unset($_SESSION['popup_message']);
endif;
?>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="hero-content">
                    <h1 class="hero-title">Find Your Perfect Property</h1>
                    <p class="hero-subtitle">Discover a curated selection of homes, apartments, and commercial properties, along with expert tips and market insights.</p>
                    <div class="hero-buttons">
                        <a href="#" class="btn btn-primary">Buy Now</a>
                        <a href="#" class="btn btn-outline-secondary">Learn More</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="hero-image">
                    <img src="/real_estate_website/assets/images/livingroom.jpg" alt="Property Image" class="img-fluid border-radius">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="section-header text-center mb-5">
        <h2 class="section-title">Manage Your Property</h2>
        <div class="section-line"></div>
    </div>
    <div class="row align-items-center">
        <div class="col-lg-6 mb-4">
            <div class="info-box p-5 border-radius">
                <h2 class="info-title">Sell Your Property with Ease</h2>
                <p class="info-subtitle">Navigate to your profile to manage and sell your properties effortlessly. Start now and make the most of our platform’s powerful tools.</p>
                <a href="/real_estate_website/user/view_profile.php" class="btn btn-primary btn-lg border-radius">Go to Profile</a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="action-box p-4 border-radius">
                        <i class="fas fa-user icon"></i>
                        <h4 class="action-title">View Profile</h4>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="action-box p-4 border-radius">
                        <i class="fas fa-home icon"></i>
                        <h4 class="action-title">Manage Properties</h4>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="action-box p-4 border-radius">
                        <i class="fas fa-edit icon"></i>
                        <h4 class="action-title">Edit Profile</h4>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="action-box p-4 border-radius">
                        <i class="fas fa-key icon"></i>
                        <h4 class="action-title">Change Password</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="section-title-left">Our Achievements</h2>
        </div>
        <div class="col-md-6 d-none d-md-block">
            <div class="line-pattern"></div>
        </div>
    </div>
    <div class="row text-center stats-section">
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="achievement-box border-radius">
                <div class="icon-container">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3 class="achievement-value">Money</h3>
                <p class="achievement-text">Spend your <br>money well on Properties</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="achievement-box border-radius">
                <div class="icon-container">
                    <i class="fas fa-home"></i>
                </div>
                <h3 class="achievement-value">Perfect Home</h3>
                <p class="achievement-text">Properties for Buy & Sell <br>Successfully</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="achievement-box border-radius">
                <div class="icon-container">
                    <i class="fas fa-user-friends"></i>
                </div>
                <h3 class="achievement-value">Client</h3>
                <p class="achievement-text">Your Saftey is our consern</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
            <div class="achievement-box border-radius">
                <div class="icon-container">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="achievement-value">5+</h3>
                <p class="achievement-text">Successful Deals</p>
            </div>
        </div>
    </div>
</div>

<div class="container my-5 py-5">
    <div class="row text-center stats-section">
        <div class="col-md-4 mb-4">
            <div class="stat-box p-5 border-radius">
                <div class="stat-icon-wrapper mb-3">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <h2 class="stat-value">R150 000</h2>
                <p class="stat-description">Owned from Properties transactions</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="stat-box p-5 border-radius">
                <div class="stat-icon-wrapper mb-3">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
                <h2 class="stat-value">15+</h2>
                <p class="stat-description">Properties for Buy & Sell Successfully</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="stat-box p-5 border-radius">
                <div class="stat-icon-wrapper mb-3">
                    <div class="stat-icon">
                        <i class="fas fa-smile"></i>
                    </div>
                </div>
                <h2 class="stat-value">50+</h2>
                <p class="stat-description">Clients</p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mt-5">
    <h1 class="text-center section-title">Featured Properties</h1>

    <div class="row justify-content-center">
        <?php
     $sql = "SELECT p.PropertyID, p.Title, p.Price, p.Bedrooms, p.Bathrooms, p.SquareMeters, p.City, p.State, p.Address, p.Status, MIN(pi.ImageURL) AS ImageURL 
        FROM property p 
        LEFT JOIN propertyimages pi ON p.PropertyID = pi.PropertyID 
        WHERE p.ApprovalStatus = 'approved'
        GROUP BY p.PropertyID
        ORDER BY p.PropertyID DESC 
        LIMIT 4";

        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($property = $result->fetch_assoc()) {
                $imagePath = !empty($property['ImageURL']) ? $property['ImageURL'] : '/real_estate_website/assets/images/property-placeholder.jpg';
                ?>
              <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <div class="property-card border-radius">
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
                        <p class="property-address">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($property['Address']) . ', ' . htmlspecialchars($property['City']) . ', ' . htmlspecialchars($property['State']); ?>
                        </p>
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
                            <a href="/real_estate_website/properties/detail.php?id=<?php echo $property['PropertyID']; ?>" class="btn btn-outline-primary view-button border-radius">View Property</a>
                        </div>
                    </div>
                </div>
            </div>

                <?php
            }
        } else {
            echo "<div class='col-12'><p class='text-center'>No featured properties found.</p></div>";
        }

        $stmt->close();
        ?>
    </div>
</div>

<div class="container mt-5">
    <h2 class="section-heading text-center">Our Commitment to Quality</h2>
</div>

<div class="quality-section container mt-5 slide-in-section">
    <div class="row">
        <div class="col-md-6 slide-in-left">
            <img src="/real_estate_website/assets/images/livingroom.jpg" class="img-fluid border-radius" alt="Living Room">
        </div>
        <div class="col-md-6 d-flex flex-column justify-content-center slide-in-right">
            <h3 class="section-title">We provide Best Quality</h3>
            <p class="section-subtitle">Our Houses are sold with the finest quality materials and are checked in detail for safety.</p>
            <div class="badge-container">
                <span class="badge">Since</span>
                <span class="year">2024</span>
            </div>
        </div>
    </div>
</div>

<div class="safety-section container mt-5 slide-in-section">
    <div class="row">
        <div class="col-md-6 d-flex flex-column justify-content-center slide-in-left">
            <h3 class="section-title">By buying your property here you are guaranteed safety and comfort</h3>
            <p class="section-text">
                We strive to provide the best experience for our tenants, with a focus on safety and comfort. Each of our houses is inspected and maintained to the highest standards.
            </p>
            <div class="btn-group mt-3">
                <a href="#" class="btn btn-primary border-radius">Get in touch</a>
                <a href="#" class="btn btn-secondary border-radius ml-2">Learn more</a>
            </div>
        </div>
        <div class="col-md-6 slide-in-right">
            <img src="/real_estate_website/assets/images/safteyroom.png" class="img-fluid border-radius" alt="Safety Room">
        </div>
    </div>
</div>

<?php include './temp/footer.php';?>

<script>
 function closePopup() {
        document.querySelector('.popup').remove();
        document.querySelector('.popup-overlay').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('.slide-in-section, .slide-in-left, .slide-in-right');

        const slideInOnScroll = () => {
            sections.forEach(section => {
                const sectionTop = section.getBoundingClientRect().top;
                const triggerBottom = window.innerHeight * 0.9; 

                if (sectionTop < triggerBottom) {
                    section.classList.add('active');
                }
            });
        };

        window.addEventListener('scroll', slideInOnScroll);
        slideInOnScroll(); 
    });

    $(document).ready(function() {
        $('.save-button').click(function() {
            var button = $(this);
            var propertyID = button.data('id');
            var isSaved = button.data('saved') === 'true';
            var url = isSaved ? '../user/remove_property.php' : '../user/save_property.php';

            $.ajax({
                url: url,
                type: 'POST',
                data: { property_id: propertyID },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.status === 'saved') {
                            button.data('saved', 'true');
                            button.find('i').removeClass('far fa-heart').addClass('fas fa-heart');
                        } else if (data.status === 'removed') {
                            button.data('saved', 'false');
                            button.find('i').removeClass('fas fa-heart').addClass('far fa-heart');
                            button.closest('.property-card').remove(); 
                        } else if (data.error) {
                            alert('Error: ' + data.error);
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                        console.error("Raw response:", response);
                        alert('An error occurred. Please try again.');
                    }
                },
                error: function(xhr, status, error) {
                    alert('AJAX Error: ' + error);
                }
            });
        });
    });


</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="/real_estate_website/assets/js/navbar.js"></script>

</body>
</html>
