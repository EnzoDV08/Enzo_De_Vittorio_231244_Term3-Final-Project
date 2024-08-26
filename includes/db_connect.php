

<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "new_real_estate_db"; 

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8mb4");

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage(), 3, 'errors.log');
    die("Connection failed. Please try again later.");
}
?>

