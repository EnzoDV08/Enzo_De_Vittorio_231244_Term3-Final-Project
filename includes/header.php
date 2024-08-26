
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="/real_estate_website/index.php">
        <i class="fas fa-home"></i> The Real Estate Website
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            <li class="nav-item"><a class="nav-link" href="/real_estate_website/index.php"><i class="fas fa-home"></i> Home</a></li>
            <li class="nav-item"><a class="nav-link" href="/real_estate_website/user/listing.php"><i class="fas fa-list"></i> Listing</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-blog"></i> Blog</a></li>
        </ul>
        <a href="/real_estate_website/contact.php" class="cta-button">Contact Us</a>
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['UserID'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/real_estate_website/user/view_profile.php"><i class="fas fa-user"></i> Profile</a>
                </li>
                
            <?php else: ?>
                <li class="nav-item"><a class="nav-link login-btn" href="/real_estate_website/user/login.php"><i class="fas fa-sign-in-alt"></i> Log in</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="/real_estate_website/assets/js/navbar.js"></script>
</body>
</html>
