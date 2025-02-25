<?php
// Start session
session_start();

// Include database connection
include_once "connection.php";

// Initialize variables
$name = $contactno = $feedback = "";
$successMessage = "";
$errorMessage = ""; // Initialize errorMessage variable

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $_POST['name'];
    $contactno = $_POST['contactno'];
    $feedback = $_POST['feedback'];

    // Validate form data
    if (empty($name) || empty($feedback)) {
        // Set error message if any required field is empty
        $_SESSION['errorMessage'] = "All fields are required.";
    } elseif (!preg_match("/^[0-9]{10}$/", $contactno)) {
        // Set error message if contact number format is invalid
        $_SESSION['errorMessage'] = "Invalid contact number format. Please enter a 10-digit number.";
    } else {
        // Save feedback to database
        $query = "INSERT INTO feedback (name, contactno, feedbacktext) VALUES ('$name', '$contactno', '$feedback')";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Set success message if insertion is successful
            $_SESSION['successMessage'] = "Your feedback is successfully submitted.";
            // Clear form fields
            $name = $contactno = $feedback = "";
        } else {
            // Set error message if insertion fails
            $_SESSION['errorMessage'] = "Error: " . mysqli_error($con);
        }
    }

    // Redirect to prevent form resubmission on page refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Display success message if it exists
if (isset($_SESSION['successMessage'])) {
    $successMessage = $_SESSION['successMessage'];
    unset($_SESSION['successMessage']); // Clear session variable
}

// Display error message if it exists
if (isset($_SESSION['errorMessage'])) {
    $errorMessage = $_SESSION['errorMessage'];
    unset($_SESSION['errorMessage']); // Clear session variable
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Feedback Form</title>
<style>
    /* Add some basic styling */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-image: url('feedback.jpg'); /* Specify the path to your background image */
        background-size: cover;
        background-position: center;
        background-color: #f4f4f4;
    }
    .container {
        max-width: 500px;
        margin: 50px auto;
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        font-weight: bold;
    }
    input[type="text"],
    textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    textarea {
        height: 100px;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    .success-message {
        color: green;
        text-align: center;
    }
    .error-message {
        color: red;
        text-align: center;
    }
    /* Style for the Home button */
    .home-button {
        background-color: navy;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        text-decoration: none; /* Remove underlines */
        display: inline-block; /* Adjust display */
        margin-right: 10px; /* Add margin for spacing */
    }
    .home-button:hover {
        background-color: #001f3f; /* Darken color on hover */
    }
</style>
</head>
<body>

<div class="container">
    <h2>Feedback Form</h2>
    <?php if ($successMessage): ?>
        <p class="success-message"><?php echo $successMessage; ?></p>
    <?php endif; ?>
    <?php if ($errorMessage): ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
        </div>
        <div class="form-group">
            <label for="contactno">Contact Number:</label>
            <input type="text" id="contactno" name="contactno" value="<?php echo $contactno; ?>" required>
            <small>Format: 10-digit number</small>
        </div>
        <div class="form-group">
            <label for="feedback">Feedback:</label>
            <textarea id="feedback" name="feedback" required><?php echo $feedback; ?></textarea>
        </div>
        <input type="submit" value="Submit">
        <a href="index.html" class="home-button">Home</a>
    </form>
</div>

</body>
</html>
