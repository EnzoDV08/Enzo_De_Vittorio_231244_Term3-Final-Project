🏡 Real Estate Website
Welcome to the Real Estate Website project, a comprehensive platform designed for managing and browsing property listings. This platform offers functionality for both users and administrators, ensuring a seamless experience whether you're looking to buy, sell, or manage properties.

🌟 Project Overview
This project is a web-based real estate platform that allows users to browse, inquire, and manage property listings. The platform includes functionalities for both users and administrators:

Users can register, log in, browse properties, and manage their profiles.
Administrators can manage property listings, user accounts, and approve or reject properties.
📁 Folder Structure
The project is organized into the following main directories:

admin/
Contains the backend admin panel for managing the website.

admin_dashboard.php: Main dashboard for administrators.
admin_login.php: Admin login page.
admin_users.php: Manage user accounts.
admin_properties.php: Manage property listings.
approve_property.php: Approve property listings.
reject_property.php: Reject property listings.
process_property.php: Handle property processing.
Includes:
admin_dashboard.js: JavaScript file for admin dashboard interactions.
user/
Contains user-facing pages and functionality.

register.php: User registration page.
login.php: User login page.
logout.php: User logout functionality.
listing.php: Page for displaying property listings.
edit_profile.php: User profile editing page.
send_inquiry.php: Send inquiries about properties.
post_comment.php: Post comments on properties.
mark_sold.php: Mark a property as sold.
assets/
Contains static assets like CSS, JavaScript, and images.

css/: Stylesheets for the website.
js/: JavaScript files for front-end functionality.
images/: Image assets used across the site.
includes/
Contains reusable components such as database connections and headers.

db_connect.php: Database connection setup.
header.php: Common header file included in multiple pages.
properties/
Contains property-related pages.

detail.php: Detailed view of a single property.
uploads/
Directory for storing uploaded images and files.

temp/
Temporary files or pages.

index.php
The main entry point of the website.

🔥 Features
User Management
Registration & Login: Users can easily register and log in to manage their profiles.
Profile Management: Edit profile information, upload profile images, and update contact details.
Property Browsing: Explore a variety of properties, search for specific listings, and view detailed property information.
Inquiries & Comments: Send inquiries directly to property owners and post comments on property listings.
Admin Management
Admin Dashboard: Access a comprehensive dashboard to manage users and properties.
User & Property Management: Add, edit, approve, or reject user accounts and property listings.
Property Approval: Approve or reject properties submitted by users to ensure quality control.
Property Listings
Detailed Listings: Display properties with multiple images, detailed descriptions, and pricing.
Property Management: Admins and users can edit and manage their property listings, marking them as sold or available.
Interactive Features: Users can post comments, view reviews, and engage with other users on property listings.
🛠️ Technologies Used
PHP
PHP was used for server-side scripting, handling form submissions, database interactions, and user authentication.

Example: register.php

php
Copy code
<?php
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
    if (mysqli_query($conn, $sql)) {
        header('Location: login.php');
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
HTML
HTML was used to structure the web pages, including forms, property listings, and the user interface.

Example: register.php

html
Copy code
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <form action="register.php" method="POST">
        <h2>Register</h2>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
JavaScript
JavaScript was used for front-end interactivity, such as form validation and dynamic content loading.

Example: admin_dashboard.js

javascript
Copy code
document.addEventListener('DOMContentLoaded', function() {
    const approveButtons = document.querySelectorAll('.approve-btn');
    
    approveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const propertyId = this.dataset.id;
            fetch('approve_property.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${propertyId}`
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    alert('Property approved successfully!');
                } else {
                    alert('Approval failed.');
                }
            });
        });
    });
});
CSS
CSS was used to style the web pages, ensuring a consistent look and feel across the platform.

Example: style.css

css
Copy code
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

form {
    background: #fff;
    padding: 20px;
    max-width: 400px;
    margin: 50px auto;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

form h2 {
    margin-bottom: 20px;
    color: #333;
}

form label {
    display: block;
    margin-bottom: 5px;
}

form input[type="text"],
form input[type="email"],
form input[type="password"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

form button {
    padding: 10px 15px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
SQL
SQL was used to create and manage the database structure, including tables for users, properties, and reviews.

Example: SQL Table Creation

sql
Copy code
CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Telephone VARCHAR(15),
    ProfileImage VARCHAR(255),
    BackgroundImage VARCHAR(255),
    Password VARCHAR(255) NOT NULL,
    Role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE properties (
    PropertyID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2),
    Address VARCHAR(255),
    City VARCHAR(100),
    State VARCHAR(100),
    ZipCode VARCHAR(10),
    PropertyType VARCHAR(50),
    Status ENUM('available', 'sold') DEFAULT 'available',
    AgentID INT,
    UserID INT,
    GarageSpaces INT,
    Bedrooms INT,
    Bathrooms INT,
    SquareMeters FLOAT,
    Image1 VARCHAR(255),
    Image2 VARCHAR(255),
    Image3 VARCHAR(255),
    Image4 VARCHAR(255),
    ApprovalStatus ENUM('pending', 'approved', 'denied') DEFAULT 'pending',
    FOREIGN KEY (UserID) REFERENCES users(UserID),
    FOREIGN KEY (AgentID) REFERENCES users(UserID)
);
XAMPP & MySQL
XAMPP was used as the local development environment, providing the Apache server and MySQL database. phpMyAdmin was used to manage the MySQL database.

Steps to Setup:

Start Apache and MySQL: Open the XAMPP Control Panel and start Apache and MySQL.
Access phpMyAdmin: Navigate to http://localhost/phpmyadmin/ to manage the database.
Import SQL File: Use phpMyAdmin to import the SQL file located in the /sql/ directory of the project.

🗃️ Database Structure
Admin Table
scss
Copy code
| AdminID (PK) | Name  | Email         | Password                                                  |
Users Table
scss
Copy code
| UserID (PK) | Name  | Email         | Telephone | ProfileImage | BackgroundImage | Password | Role   |
Property Table
scss
Copy code
| PropertyID (PK) | Title  | Description | Price | Address | City  | State  | ZipCode | PropertyType | Status  | AgentID (FK) | UserID (FK) | GarageSpaces | Bedrooms | Bathrooms | SquareMeters | Image1 | Image2 | Image3 | Image4 | ApprovalStatus |
PropertyImages Table
scss
Copy code
| ImageID (PK) | PropertyID (FK) | ImageURL                                                      |
Reviews Table
scss
Copy code
| ReviewID (PK) | UserID (FK) | ReviewerID (FK) | Comment | PropertyID (FK) | ParentID (FK)       |
📊 Relationships
Users own Properties → (Users.UserID → Property.UserID) [1 to Many]
Users write Reviews → (Users.UserID → Reviews.UserID) [1 to Many]
Properties are managed by Users → (Property.AgentID → Users.UserID) [Many to 1]
Properties have many Images → (Property.PropertyID → PropertyImages.PropertyID) [1 to Many]
Properties are reviewed by Users → (Property.PropertyID → Reviews.PropertyID) [1 to Many]
Reviews can be nested (Replies) → (Reviews.ReviewID → Reviews.ParentID) [1 to Many]

🚀 Installation
1. Clone the repository:
bash
Copy code
git clone https://github.com/yourusername/real-estate-website.git
2. Navigate to the project directory:
bash
Copy code
cd real-estate-website
3. Set up the database:
Import the SQL file provided in the /sql/ directory to your MySQL server.
4. Configure the database connection:
Open includes/db_connect.php and update the connection parameters (host, username, password, database name).
5. Start the server:
If you're using XAMPP or WAMP, place the project in the htdocs or www directory.
Open the project in your web browser by navigating to http://localhost/real-estate-website.
6. Access the Admin Panel:
Navigate to http://localhost/real-estate-website/admin/admin_login.php to log in as an admin.
7. Enjoy the Real Estate Platform!

   
📌 Additional Information
Responsive Design: The platform is fully responsive, providing an optimal experience across devices.
Security: Basic security features like input validation, password hashing, and session management are implemented.
Scalability: The project structure allows for easy expansion with new features or pages.
