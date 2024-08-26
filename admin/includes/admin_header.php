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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/navbar.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="/real_estate_website/index.php">
        <i class="fas fa-home"></i> Relasto Admin
    </a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto">
            
            <li class="nav-item"><a class="nav-link" href="/real_estate_website/admin/admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            
        </ul>
        <ul class="navbar-nav">
            <?php if (isset($_SESSION['AdminID'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/real_estate_website/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
