<?php
// Include the connection file
include 'connection.php';

// Check if weight and subwastetype are set in the POST request
if (isset($_POST['weight']) && isset($_POST['subwastetype'])) {
    // Sanitize the input to prevent SQL injection
    $weight = mysqli_real_escape_string($con, $_POST['weight']);
    $subwastetype = mysqli_real_escape_string($con, $_POST['subwastetype']);

    // Query to fetch the price from the database
    $query = "SELECT price FROM cost WHERE waste_type = '$subwastetype'";
    $result = mysqli_query($con, $query);

    // Check if the query was successful
    if ($result) {
        // Check if a row was returned
        if (mysqli_num_rows($result) > 0) {
            // Fetch the price from the result
            $row = mysqli_fetch_assoc($result);
            $price = $row['price'];

            // Return the price
            echo $price;
        } else {
            // If no row was returned, indicate that the price is not available
            echo "Price not available";
        }
    } else {
        // If the query failed, indicate that the price is not available
        echo "Price not available";
    }
} else {
    // If weight or subwastetype are not set in the request, return an error message
    echo "Weight or sub waste type not specified";
}
?>
