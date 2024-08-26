<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../includes/db_connect.php';
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imageFiles']) && isset($_SESSION['UserID'])) {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $address = $_POST['address'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $zipcode = $_POST['zipcode'] ?? '';
    $propertyType = $_POST['propertyType'] ?? '';
    $status = 'pending'; 
    $agentID = $_SESSION['UserID']; 
    $garageSpaces = $_POST['garageSpaces'] ?? 0;
    $bedrooms = $_POST['bedrooms'] ?? 0;
    $bathrooms = $_POST['bathrooms'] ?? 0;
    $squareMeters = $_POST['squareMeters'] ?? 0.0;

 
    if (!empty($title) && !empty($price) && !empty($address)) {
        
        $sql = "INSERT INTO property 
        (Title, Description, Price, Address, City, State, ZipCode, PropertyType, Status, ApprovalStatus, AgentID, GarageSpaces, Bedrooms, Bathrooms, SquareMeters) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die('Prepare failed: ' . $conn->error);
        }

        $stmt->bind_param(
            "ssissssssiisss",
            $title,
            $description,
            $price,
            $address,
            $city,
            $state,
            $zipcode,
            $propertyType,
            $status,
            $agentID,
            $garageSpaces,
            $bedrooms,
            $bathrooms,
            $squareMeters
        );

        if ($stmt->execute()) {
           
            $propertyID = $stmt->insert_id;

            
$uploadDirectory = '../uploads/'; 
$uploadedFiles = $_FILES['imageFiles'];


for ($i = 0; $i < count($uploadedFiles['name']); $i++) {
    $fileName = basename($uploadedFiles['name'][$i]);
    $targetFilePath = $uploadDirectory . $fileName;

    
    if (move_uploaded_file($uploadedFiles['tmp_name'][$i], $targetFilePath)) {
       
        $relativePath = 'uploads/' . $fileName; 
        $sqlImage = "INSERT INTO propertyimages (PropertyID, ImageURL) VALUES (?, ?)";
        $stmtImage = $conn->prepare($sqlImage);
        if ($stmtImage === false) {
            die('Image Insert Prepare failed: ' . $conn->error);
        }
        $stmtImage->bind_param("is", $propertyID, $relativePath);
        if (!$stmtImage->execute()) {
            echo "Image insertion error: " . $stmtImage->error;
        }
        $stmtImage->close();
    } else {
        echo "Failed to upload image: " . $fileName;
    }
}

            
            header("Location: ../properties/detail.php?id=" . $propertyID);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Please provide all required fields.";
    }

    $conn->close();
} else {
    echo "Form data not submitted correctly.";
}

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Your Property</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/navbar.css">
    <link rel="stylesheet" href="/real_estate_website/assets/css/style.css">

    <style>
       
        body {
            background: #f4f4f4;
            font-family: 'Roboto', sans-serif;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 100px; 
            padding: 30px;
            background-color: #ffffff;
            color: #333333;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            max-width: 900px;
        }

        .container h2 {
            font-size: 30px;
            font-weight: bold;
            color: #007bff;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group label {
            font-size: 16px;
            font-weight: 500;
            color: #333333;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 12px;
            font-size: 14px;
            background-color: #ffffff;
            transition: border-color 0.3s ease, background-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.25);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease, transform 0.3s ease;
            display: block;
            margin: 20px auto;
            width: 60%;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 8px 20px rgba(0, 91, 179, 0.6);
            transform: scale(1.05);
        }

        .upload-container {
            position: relative;
            width: 100%;
            padding: 20px;
            background: #f7f9fc;
            border-radius: 8px;
            border: 2px dashed #007bff;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .upload-container:hover {
            background: #ffffff;
            border-color: #00c6ff;
        }

        .upload-container input[type="file"] {
            display: none;
        }

        .upload-container label {
            font-size: 16px;
            color: #007bff;
            cursor: pointer;
            text-align: center;
            font-weight: bold;
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #e7f1ff;
            transition: background-color 0.3s ease;
        }

        .upload-container label:hover {
            background-color: #d0e2ff;
        }

        .image-preview-container {
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .image-preview {
            position: relative;
            width: 120px;
            height: 120px;
            border: 2px solid #ddd;
            border-radius: 8px;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            margin: 5px;
        }

        .remove-image {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            text-align: center;
            cursor: pointer;
            font-size: 12px;
            line-height: 18px;
        }

        
        @media (min-width: 768px) {
            .row .col-md-6 {
                padding-right: 15px;
                padding-left: 15px;
            }
        }

        
        .navbar .nav-link, 
        .navbar .navbar-brand {
            color: #000 !important; 
        }

        
        .navbar-scrolled .navbar-brand,
        .navbar-scrolled .nav-link {
            color: #000 !important;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Upload Your Property</h2>
    <form action="uploadProperty.php" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="title">Property Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter property title" required>
                </div>
                <div class="form-group">
                    <label for="price">Price (ZAR)</label>
                    <input type="text" name="price" class="form-control" placeholder="Enter property price" required>
                </div>
                <div class="form-group">
                    <label for="bedrooms">Bedrooms</label>
                    <input type="number" name="bedrooms" class="form-control" placeholder="Number of bedrooms" required>
                </div>
                <div class="form-group">
                    <label for="bathrooms">Bathrooms</label>
                    <input type="number" name="bathrooms" class="form-control" placeholder="Number of bathrooms" required>
                </div>
                <div class="form-group">
                    <label for="garageSpaces">Garage Spaces</label>
                    <input type="number" name="garageSpaces" class="form-control" placeholder="Number of garage spaces" required>
                </div>
                <div class="form-group">
                    <label for="squareMeters">Square Meters</label>
                    <input type="number" name="squareMeters" class="form-control" placeholder="Enter square meters" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" name="address" class="form-control" placeholder="Enter property address" required>
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" class="form-control" placeholder="Enter city" required>
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" name="state" class="form-control" placeholder="Enter state" required>
                </div>
                <div class="form-group">
                    <label for="zipcode">Zip Code</label>
                    <input type="text" name="zipcode" class="form-control" placeholder="Enter zip code" required>
                </div>
                <div class="form-group">
                    <label for="propertyType">Property Type</label>
                    <select name="propertyType" class="form-control" required>
                        <option value="">Select property type</option>
                        <option value="house">House</option>
                        <option value="apartment">Apartment</option>
                        <option value="land">Land</option>
                    </select>
                </div>
                <div class="form-group upload-container">
                    <label for="imageFiles">Upload Images</label>
                    <input type="file" name="imageFiles[]" id="imageFiles" multiple required>
                </div>
                <div class="image-preview-container">
                    
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Upload Property</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

let selectedImages = [];

function updatePreviews() {
    const previewContainer = document.querySelector('.image-preview-container');
    const files = document.getElementById('imageFiles').files;
    previewContainer.innerHTML = ''; 

    
    for (let i = 0; i < files.length; i++) {
        selectedImages.push(files[i]);
    }

    selectedImages.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgPreview = document.createElement('div');
            imgPreview.classList.add('image-preview');
            imgPreview.style.backgroundImage = `url(${e.target.result})`;

            const removeBtn = document.createElement('button');
            removeBtn.classList.add('remove-image');
            removeBtn.innerHTML = '&times;';
            removeBtn.addEventListener('click', function() {
                removeImage(index);
            });

            imgPreview.appendChild(removeBtn);
            previewContainer.appendChild(imgPreview);
        };
        reader.readAsDataURL(file);
    });
}

function removeImage(index) {
    selectedImages.splice(index, 1); 
    updateFileList(); // Update the file input and previews
}

function updateFileList() {
    const dt = new DataTransfer();
    selectedImages.forEach(file => dt.items.add(file));
    document.getElementById('imageFiles').files = dt.files;
    updatePreviews(); // Refresh the previews
}

// Add event listener to the file input
document.getElementById('imageFiles').addEventListener('change', updatePreviews);

</script>

</body>
</html>
