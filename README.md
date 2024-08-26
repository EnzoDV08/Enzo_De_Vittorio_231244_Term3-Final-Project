# 🏡 Real Estate Website

Welcome to the Real Estate Website project, a comprehensive platform designed for managing and browsing property listings. This platform offers functionality for both users and administrators, ensuring a seamless experience whether you're looking to buy, sell, or manage properties.

## 🌟 Project Overview

This project is a web-based real estate platform that allows users to **browse**, **inquire**, and **manage** property listings. The platform includes functionalities for both users and administrators:

- **Users** can register, log in, browse properties, and manage their profiles.
- **Administrators** can manage property listings, user accounts, and approve or reject properties.

Mockup of Website
![mockup real estate](https://github.com/user-attachments/assets/f4ed7687-4060-451f-ba5a-68f139ecaabb)

## 📁 Folder Structure

The project is organized into the following main directories:

### **admin/**
Contains the backend admin panel for managing the website.
- `admin_dashboard.php`: Main dashboard for administrators.
- `admin_login.php`: Admin login page.
- `admin_users.php`: Manage user accounts.
- `admin_properties.php`: Manage property listings.
- `approve_property.php`: Approve property listings.
- `reject_property.php`: Reject property listings.
- `process_property.php`: Handle property processing.

### **user/**
Contains user-facing pages and functionality.
- `register.php`: User registration page.
- `login.php`: User login page.
- `logout.php`: User logout functionality.
- `listing.php`: Page for displaying property listings.
- `edit_profile.php`: User profile editing page.
- `send_inquiry.php`: Send inquiries about properties.
- `post_comment.php`: Post comments on properties.
- `mark_sold.php`: Mark a property as sold.

### **assets/**
Contains static assets like CSS, JavaScript, and images.
- **css/**: Stylesheets for the website.
- **js/**: JavaScript files for front-end functionality.
- **images/**: Image assets used across the site.

### **includes/**
Contains reusable components such as database connections and headers.
- `db_connect.php`: Database connection setup.
- `header.php`: Common header file included in multiple pages.

### **properties/**
Contains property-related pages.
- `detail.php`: Detailed view of a single property.

### **uploads/**
Directory for storing uploaded images and files.

### **temp/**
Temporary files or pages.

### **index.php**
The main entry point of the website.

## 🔥 Features

### User Management
- **Registration & Login**: Users can easily register and log in to manage their profiles.
- **Profile Management**: Edit profile information, upload profile images, and update contact details.
- **Property Browsing**: Explore a variety of properties, search for specific listings, and view detailed property information.
- **Inquiries & Comments**: Send inquiries directly to property owners and post comments on property listings.

### Admin Management
- **Admin Dashboard**: Access a comprehensive dashboard to manage users and properties.
- **User & Property Management**: Add, edit, approve, or reject user accounts and property listings.
- **Property Approval**: Approve or reject properties submitted by users to ensure quality control.

### Property Listings
- **Detailed Listings**: Display properties with multiple images, detailed descriptions, and pricing.
- **Property Management**: Admins and users can edit and manage their property listings, marking them as sold or available.
- **Interactive Features**: Users can post comments, view reviews, and engage with other users on property listings.

## 🛠️ Technologies Used

This project was built using the following technologies:

- **Languages**:
  - PHP
  - HTML
  - CSS
  - JavaScript
  - SQL

- **Development Environment**:
  - XAMPP (Apache, MySQL)
  - phpMyAdmin (Database management)

- **Frontend**:
  - Bootstrap (CSS Framework)
  - Custom CSS
  - JavaScript

- **Backend**:
  - PHP
  - MySQL (Database)

---

## 🗃️ Database Structure

#### **Admin Table**

| AdminID (PK) | Name | Email | Password |
#### **Users Table**

| UserID (PK) | Name | Email | Telephone | ProfileImage | BackgroundImage | Password | Role |
#### **Property Table**

| PropertyID (PK) | Title | Description | Price | Address | City | State | ZipCode | PropertyType | Status | AgentID (FK) | UserID (FK) | GarageSpaces | Bedrooms | Bathrooms | SquareMeters | Image1 | Image2 | Image3 | Image4 | ApprovalStatus |
#### **PropertyImages Table**

| ImageID (PK) | PropertyID (FK) | ImageURL |
#### **Reviews Table**

| ReviewID (PK) | UserID (FK) | ReviewerID (FK) | Comment | PropertyID (FK) | ParentID (FK) |
### 📊 Relationships

- **Users own Properties** → `(Users.UserID → Property.UserID)` [1 to Many]
- **Users write Reviews** → `(Users.UserID → Reviews.UserID)` [1 to Many]
- **Properties are managed by Users** → `(Property.AgentID → Users.UserID)` [Many to 1]
- **Properties have many Images** → `(Property.PropertyID → PropertyImages.PropertyID)` [1 to Many]
- **Properties are reviewed by Users** → `(Property.PropertyID → Reviews.PropertyID)` [1 to Many]
- **Reviews can be nested (Replies)** → `(Reviews.ReviewID → Reviews.ParentID)` [1 to Many]

---
## 🛠️ Technologies Used

This project is built using the following technologies:

![HTML](https://img.shields.io/badge/Code-HTML5-informational?style=flat&logo=html5&logoColor=white&color=E34F26)
![CSS](https://img.shields.io/badge/Code-CSS3-informational?style=flat&logo=css3&logoColor=white&color=1572B6)
![JavaScript](https://img.shields.io/badge/Code-JavaScript-informational?style=flat&logo=javascript&logoColor=white&color=F7DF1E)
![XAMPP](https://img.shields.io/badge/Server-XAMPP-informational?style=flat&logo=xampp&logoColor=white&color=FB7A24)
![SQL](https://img.shields.io/badge/Database-SQL-informational?style=flat&logo=sqlite&logoColor=white&color=003B57)
![MySQL](https://img.shields.io/badge/Database-MySQL-informational?style=flat&logo=mysql&logoColor=white&color=4479A1)
![phpMyAdmin](https://img.shields.io/badge/Tool-phpMyAdmin-informational?style=flat&logo=phpmyadmin&logoColor=white&color=6C78AF)

