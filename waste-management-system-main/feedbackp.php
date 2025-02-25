<?php
// Start session
session_start();

// Include database connection
include_once "connection.php";

// Initialize variables
$contactno = "";
$feedbacks = [];
$error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve contact number from form submission
    $contactno = $_POST['contactno'];

    // Validate contact number format
    if (!preg_match("/^[0-9]{10}$/", $contactno)) {
        $error = "Invalid contact number format. Please enter a 10-digit number.";
    } else {
        // Retrieve feedbacks matching with the entered contact number
        $query = "SELECT * FROM feedback WHERE contactno = '$contactno'";
        $result = mysqli_query($con, $query);

        if ($result) {
            // Check if any matching feedbacks found
            if (mysqli_num_rows($result) > 0) {
                // Fetch all matching feedbacks
                while ($row = mysqli_fetch_assoc($result)) {
                    $feedbacks[] = $row;
                }
            } else {
                $error = "No feedback available for this number.";
            }
        } else {
            $error = "Error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Feedbacks</title>
<style>
    /* Add some basic styling */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-image: url('feedback.jpg');
        background-size: cover;
    }
    .container {
        max-width: 800px;
        margin: 50px auto;
        background: rgba(255, 255, 255, 0.8);
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
    input[type="text"] {
        width: calc(100% - 80px); /* Adjusted width */
        padding: 10px;
        border: none;
        border-radius: 4px;
        box-sizing: border-box;
        background-color: white; /* Input background color */
        color: black; /* Text color */
        margin-right: 10px;
    }
    input[type="text"]:hover {
        background-color: white; /* Hover effect */
    }
    input[type="submit"],
    .home-button {
        display: inline-block; /* Make buttons inline-block */
        width: 40%; /* Set equal width */
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        margin-left: 10px; /* Added margin */
        background-color: navy; /* Button background color */
        color: white; /* Text color */
        text-decoration: none;
    }

    input[type="submit"]:hover,
    .home-button:hover {
        background-color: green;
        color: navy;
    }

    .feedback-list {
        list-style-type: none;
        padding: 0;
    }
    .feedback-item {
        margin-bottom: 20px;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 4px;
    }
    .error-message {
        color: red;
        text-align: center;
    }
</style>
</head>
<body>

<div class="container">
    <h2>View Feedbacks</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="contactno">Enter Contact Number:</label>
            <input type="text" id="contactno" name="contactno" value="<?php echo $contactno; ?>" required><br>
            <small>Format: 10-digit number</small>
        </div>
        <input type="submit" value="Submit">
        <a href="index.html" class="home-button">Home</a>
    </form>

    <?php if ($error): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php elseif ($feedbacks): ?>
        <h3>Feedbacks for Contact Number: <?php echo $contactno; ?></h3>
        <ul class="feedback-list">
            <?php foreach ($feedbacks as $feedback): ?>
                <li class="feedback-item">
                    <strong>Name:</strong> <?php echo $feedback['name']; ?><br>
                    <strong>Contact Number:</strong> <?php echo $feedback['contactno']; ?><br>
                    <strong>Feedback:</strong> <?php echo $feedback['feedbacktext']; ?><br>
                    <strong>Date:</strong> <?php echo $feedback['feedbackdate']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

</body>
</html>
