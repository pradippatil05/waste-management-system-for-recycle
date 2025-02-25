<?php
// Start the session
session_start();

// Check if user is logged in
if(!isset($_SESSION['email'])) {
    // Redirect to login page or handle accordingly if user is not logged in
    header("Location: login-user.php");
    exit();
}

// Get user email from session
$email = $_SESSION['email'];

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$errors = array();

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (empty($_POST["wastetype"])) {
        $errors[] = "Waste type is required";
    }
    if (empty($_POST["location"])) {
        $errors[] = "Location is required";
    }
    if (empty($_POST["locationdescription"])) {
        $errors[] = "Location description is required";
    }
    if (empty($_FILES["file"]["name"])) {
        $errors[] = "File is required";
    }

    // If there are no errors, proceed with inserting data into the database
    if (empty($errors)) {
        // Prepare and bind parameters
        $stmt = $conn->prepare("INSERT INTO garbageinfo (email, wastetype, location, locationdescription, file) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $email, $wastetype, $location, $locationdescription, $filename);

        // Set parameters
        $wastetype = $_POST["wastetype"];
        $location = $_POST["location"];
        $locationdescription = $_POST["locationdescription"];
        
        // Upload image file
        $target_dir = "uploads/";
        $filename = $_FILES["file"]["name"];
        $target_file = $target_dir . basename($filename);
        move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

        // Execute statement
        if ($stmt->execute()) {
            echo "<script>alert('New record inserted successfully');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        // Close statement and connection
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
