# Real Estate Website

## Project Overview

This project is a web-based real estate platform that allows users to browse, inquire, and manage property listings. The platform includes functionalities for both users and administrators. Users can register, log in, browse properties, and manage their profiles, while administrators can manage property listings, user accounts, and approve or reject properties.

## Folder Structure

The project is organized into the following main directories:

- **admin/**: Contains the backend admin panel for managing the website.
  - `admin_dashboard.php`: Main dashboard for administrators.
  - `admin_login.php`: Admin login page.
  - `admin_users.php`: Manage user accounts.
  - `admin_properties.php`: Manage property listings.
  - `approve_property.php`: Approve property listings.
  - `reject_property.php`: Reject property listings.
  - `process_property.php`: Handle property processing.
  - Includes:
    - `admin_dashboard.js`: JavaScript file for admin dashboard interactions.

- **user/**: Contains user-facing pages and functionality.
  - `register.php`: User registration page.
  - `login.php`: User login page.
  - `logout.php`: User logout functionality.
  - `listing.php`: Page for displaying property listings.
  - `edit_profile.php`: User profile editing page.
  - `send_inquiry.php`: Send inquiries about properties.
  - `post_comment.php`: Post comments on properties.
  - `mark_sold.php`: Mark a property as sold.

- **assets/**: Contains static assets like CSS, JavaScript, and images.
  - `css/`: Stylesheets for the website.
  - `js/`: JavaScript files for front-end functionality.
  - `images/`: Image assets used across the site.

- **includes/**: Contains reusable components such as database connections and headers.
  - `db_connect.php`: Database connection setup.
  - `header.php`: Common header file included in multiple pages.

- **properties/**: Contains property-related pages.
  - `detail.php`: Detailed view of a single property.

- **uploads/**: Directory for storing uploaded images and files.

- **temp/**: Temporary files or pages.

- **index.php**: The main entry point of the website.

## Features

- **User Management**: 
  - User registration, login, and profile management.
  - Ability to browse and search property listings.
  - Send inquiries and post comments on properties.

- **Admin Management**:
  - Admin login and dashboard.
  - Manage user accounts and property listings.
  - Approve or reject properties submitted by users.

- **Property Listings**:
  - Display properties with images, details, and pricing.
  - Mark properties as sold or available.
  - Edit and remove property listings.

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/yourusername/real-estate-website.git
