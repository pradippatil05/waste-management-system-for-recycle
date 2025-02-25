<?php
require_once 'controllerUserData.php';
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if ($email != false && $password != false) {
    $sql = "SELECT * FROM adminlogin_tbl WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        $code = $fetch_info['code'];
        if ($status == "verified") {
            if ($code != 0) {
                header('Location: reset-code.php');
            }
        } else {
            header('Location: user-otp.php');
        }
    }
} else {
    header('Location: adminlogin.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
         body {
            font-family: Arial, sans-serif;
            margin-top: 50px;
            background-color: #f5f5f5;
            background-image: url('recycle.avif');  /*Replace 'your-image-url.jpg' with the URL of your desired background image */
            background-size: cover; /* Ensure the background image covers the entire body */
            background-position: contain;
            background-repeat: no-repeat; /* Prevent the background image from repeating */
        }

        #wrapper {
            padding-left: 0;
        }

        #page-wrapper {
            width: 100%;
            padding: 15px;
            background-color: #fff;
        }

        .top-nav,
        .navbar-inverse {
            background-color: #37517e !important;
        }

        .navbar-brand {
            padding: 5px 15px;
        }

        .logo1 {
            border-radius: 50%;
        }

        .dashboard-container {
            padding: 20px;
            display: flex;
            justify-content: flex-start;
            flex-wrap: wrap;
            margin-bottom: -20px; /* Negative margin to counteract the margin-bottom of dashboxes */
        }

        .dashbox {
            flex: 0 0 calc(25% - 20px); /* Adjust width for 4 boxes per line */
            background-color: #37517e;
            color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            margin-right: 20px; /* Adjust margin to create spacing between boxes */
            cursor: pointer; /* Add cursor pointer to indicate clickable */
        }

        .dashbox:last-child {
            margin-right: 0; /* Remove margin from the last box */
        }

        .dashbox h4 {
            margin-top: 0;
            margin-bottom: 10px;
        }

        .dashbox h3 {
            margin-top: 0;
            font-size: 24px;
        }

        @media (max-width: 768px) {
            .dashbox {
                flex: 0 0 calc(50% - 20px);
            }
        }

        /* Popup Table Styles */
        .popup-table-container {
            display: none; /* Initially hidden */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            z-index: 999; /* Ensure it's on top of other elements */
            overflow: auto; /* Enable scrolling if needed */
        }

        .popup-table {
            background-color: #fff;
            margin: 10% auto; /* Center the table vertically and horizontally */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            max-width: 80%; /* Limit the width of the table */
        }

        .popup-table h1 {
            margin-top: 0;
            text-align: center;
            background-color: #37517e;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
        }

        .popup-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .popup-table th,
        .popup-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .popup-table th {
            background-color: #37517e;
            color: white;
        }

        .popup-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

   
    /* Style for print button */
    @media print {
        body * {
            visibility: visible;
        }
        .no-print {
            display: none !important;
        }
    }

     /* Style for change price of waste */
     
    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .update-price-btn {
        background-color: #37517e; /* Change button color to match popup box */
        color: #000;
        margin-right: 10px; /* Add some spacing between buttons */
    }

    .close-btn {
        background-color: #ccc;
        color: #000;
    }

    button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .btn-default {
        background-color: #ccc;
        color: #000;
    }

    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">
                <img src="Capture.PNG" alt="LOGO" height="50" width="50" class="logo1">
            </a>
        </div>
        <ul class="nav navbar-right top-nav">
            <li><a href="#"><h5><?php echo 'Admin:' . $email; ?></h5></a></li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li><a href="#Dashboard" data-toggle="collapse" data-target="#submenu-1"><i class="fa fa-fw fa-list"></i> Dashboard </a></li>
                <li><a href="forgot-password.php"><i class="fa fa-fw fa-paper-plane-o"></i> Change password</a></li>
                <li><a href="logout-user.php"><i class="fa fa-fw fa fa-question-circle"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div id="page-wrapper">
        <!-- Page Heading -->
        <h1>Welcome Admin!</h1>
    </div>

    <div class="dashboard-container">
        <?php
        $query3 = mysqli_query($con, "Select * from usertable");
        $usercounts = mysqli_num_rows($query3);
        ?>
        <div class="dashbox" onclick="showUserDetailsPopup()">
            <h4>Total Users</h4>
            <h3><?php echo $usercounts; ?></h3>
        </div>

        <?php
        $query3 = mysqli_query($con, "Select * from center");
        $centercounts = mysqli_num_rows($query3);
        ?>
        <div class="dashbox" onclick="showCenterDetailsPopup()">
            <h4>Total Centers</h4>
            <h3><?php echo $centercounts; ?></h3>
        </div>

        <?php
        $query3 = mysqli_query($con, "Select * from garbageinfo");
        $Garbagecounts = mysqli_num_rows($query3);
        ?>
        <div class="dashbox" onclick="showGarbageDetailsPopup()">
            <h4>Total Garbage </h4>
            <h3><?php echo $Garbagecounts; ?></h3>
        </div>

        <?php
        $query3 = mysqli_query($con, "Select * from garbageinfo where wstatus='accepted'");
        $collectioncounts = mysqli_num_rows($query3);
        ?>
        <div class="dashbox" onclick="showCollectionDetailsPopup()">
            <h4> Total Garbage Collected </h4>
            <h3><?php echo $collectioncounts; ?></h3>
        </div>


        <div class="dashbox" onclick="showChangePricePopup()">
            <h4> Change Waste Type Price </h4>
        </div>

    </div>

    

    <!-- Popup Table for user details  -->
    <?php
// Database connection

// Check if the connection is successful
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Check if the delete button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Get the user ID to be deleted
        $delete_id = $_POST['delete_id'];

        // Perform the deletion from the database
        $delete_query = "DELETE FROM usertable WHERE id = '$delete_id'";
        $delete_result = mysqli_query($con, $delete_query);

        if ($delete_result) {
            // Display message only if deletion was successful
            //echo "User deleted successfully.";
        } else {
            // Error handling if deletion fails
            echo "Error: " . mysqli_error($con);
        }
    }
}

// Retrieve user data from usertable
$query = "SELECT id, name, email, contactno, city FROM usertable";
$result = mysqli_query($con, $query);
?>

<div class="popup-table-container" id="userDetailsPopup">
    <div class="popup-table">
        <h1>User Details</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Contact No</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['contactno'] . "</td>";
                        echo "<td>" . $row['city'] . "</td>";
                        echo "<td><form method='post'><input type='hidden' name='delete_id' value='" . $row['id'] . "'><input type='submit' name='delete' value='Delete' style='background-color: red;'></form></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "Error: " . mysqli_error($con);
                }
                ?>
            </tbody>
        </table>
        <button class="no-print no-print-print" onclick="printPopupTable(this)">Print</button>
        <button class="no-print" onclick="closePopup(this)">Close</button>    </div>
</div>



<!-- Popup Table for center details -->
    <?php
// Check if the delete button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Get the center ID to be deleted
        $delete_id = $_POST['delete_id'];

        // Perform the deletion from the database
        $delete_query = "DELETE FROM center WHERE centerid = '$delete_id'";
        $delete_result = mysqli_query($con, $delete_query);

        if ($delete_result) {
            // Display message only if deletion was successful
            //echo "Center deleted successfully.";
        } else {
            // Error handling if deletion fails
            echo "Error: " . mysqli_error($con);
        }
    }
}

// Retrieve center data from center table
$query = "SELECT centerid, centername, email, contactno, city FROM center";
$result = mysqli_query($con, $query);
?>

<div class="popup-table-container" id="centerDetailsPopup">
    <div class="popup-table">
        <h1>Center Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Center ID</th>
                    <th>Center Name</th>
                    <th>Center Email</th>
                    <th>Contact No</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['centerid'] . "</td>";
                        echo "<td>" . $row['centername'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['contactno'] . "</td>";
                        echo "<td>" . $row['city'] . "</td>";
                        echo "<td><form method='post'><input type='hidden' name='delete_id' value='" . $row['centerid'] . "'><input type='submit' name='delete' value='Delete' style='background-color: red;'></form></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "Error: " . mysqli_error($con);
                }
                ?>
            </tbody>
        </table>
        <button class="no-print no-print-print" onclick="printPopupTable(this)">Print</button>
        <button class="no-print" onclick="closePopup(this)">Close</button>
    </div>
</div>


<!-- Popup Table for collection details -->
<div class="popup-table-container" id="collectionDetailsPopup">
    <div class="popup-table">
        <h1>Total Garbage Collection Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Center ID</th>
                    <th>User Email</th>
                    <th>Waste Type</th>
                    <th>SubWaste Type</th>
                    <th>Weight</th>
                    <th>Probable Cost</th>
                    <th>Center Name</th>
                    <th>Location</th>
                    <th>Request Accepted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve user data from usertable
                $query = "SELECT garbageinfo.centerid, garbageinfo.email, garbageinfo.wastetype,garbageinfo.subwastetype,garbageinfo.weight,garbageinfo.probable_cost, center.centername, garbageinfo.location, garbageinfo.accepteddate FROM center
                        join garbageinfo on center.centerid = garbageinfo.centerid where garbageinfo.wstatus='accepted'";
                $result = mysqli_query($con, $query);
                if ($result) {
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['centerid'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['wastetype'] . "</td>";
                        echo "<td>" . $row['subwastetype'] . "</td>";
                        echo "<td>" . $row['weight'] . "</td>";
                        echo "<td>" . $row['probable_cost'] . "</td>";
                        echo "<td>" . $row['centername'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['accepteddate'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Error handling if query fails
                    echo "Error: " . mysqli_error($con);
                }
                ?>
            </tbody>
        </table>
        <button class="no-print no-print-print" onclick="printPopupTable(this)">Print</button>
        <button class="no-print" onclick="closePopup(this)">Close</button>
    </div>
</div>


<!-- Popup Table for garbage details -->
<div class="popup-table-container" id="garbageDetailsPopup">
    <div class="popup-table">
        <h1>Total Garbage Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Garbage ID</th>
                    <th>User Email</th>
                    <th>Waste Type</th>
                    <th>Location</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve user data from usertable
                $query = "SELECT Garbageid, email, wastetype, location FROM garbageinfo";
                $result = mysqli_query($con, $query);
                if ($result) {
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['Garbageid'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['wastetype'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    // Error handling if query fails
                    echo "Error: " . mysqli_error($con);
                }
                ?>
            </tbody>
        </table>
        <button class="no-print no-print-print" onclick="printPopupTable(this)">Print</button>
        <button class="no-print" onclick="closePopup(this)">Close</button>
    </div>
</div>

<script>
    // Function to print the popup table details
    function printPopupTable(button) {
        // Hide the print and close buttons
        button.style.display = 'none';
        var closeButton = button.nextElementSibling;
        closeButton.style.display = 'none';

        // Open a new window for printing
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Popup Table Print</title></head><body>');
        printWindow.document.write(document.getElementById(button.parentElement.parentElement.id).innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Print and close the print window
        printWindow.print();

        // Restore the buttons after printing
        button.style.display = 'block';
        closeButton.style.display = 'block';
    }

    // Function to close the popup
    function closePopup(button) {
        var popup = button.closest('.popup-table-container');
        popup.style.display = 'none';
    }
</script>

<!-- Popup Table for change price of waste type -->
<div class="popup-table-container" id="changePricePopup">
    <div class="popup-table">
        <h1>Change Price of Waste Types</h1>
        <form id="changePriceForm" method="post">
            <div class="form-group">
                <label for="wasteTypeSelect">Select Waste Type:</label>
                <select id="wasteTypeSelect" name="wasteType" class="form-control">
                    <?php
                    // Fetch waste types from the cost table
                    $query = "SELECT waste_type FROM cost";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['waste_type'] . "'>" . $row['waste_type'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="newPriceInput">Enter New Price:</label>
                <input type="text" id="newPriceInput" name="newPrice" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary update-price-btn">Update Price</button>
                <button type="button" onclick="closeChangePricePopup()" class="btn btn-default close-btn">Close</button>
            </div>
        </form>
    </div>
</div>



<?php
// Assuming $con is your database connection
// for updating cost in cost table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from form submission
    $wasteType = $_POST['wasteType'];
    $newPrice = $_POST['newPrice'];

    // Update price in the database
    $query = "UPDATE cost SET price = $newPrice WHERE waste_type = '$wasteType'";
    $result = mysqli_query($con, $query);

    if ($result) {
       // echo "Price for $wasteType updated successfully.";
    } else {
        echo "Error updating price: " . mysqli_error($con);
    }
}
?>



    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

    <script>
        function showUserDetailsPopup() {
            document.getElementById("userDetailsPopup").style.display = "block";
        }

        function closeUserDetailsPopup() {
            document.getElementById("userDetailsPopup").style.display = "none";
        }

        function showCenterDetailsPopup() {
            document.getElementById("centerDetailsPopup").style.display = "block";
        }

        function closeCenterDetailsPopup() {
            document.getElementById("centerDetailsPopup").style.display = "none";
        }

        function showGarbageDetailsPopup() {
            document.getElementById("garbageDetailsPopup").style.display = "block";
        }

        function closeGarbageDetailsPopup() {
            document.getElementById("garbageDetailsPopup").style.display = "none";
        }

        function showCollectionDetailsPopup() {
            document.getElementById("collectionDetailsPopup").style.display = "block";
        }

        function closeCollectionDetailsPopup() {
            document.getElementById("collectionDetailsPopup").style.display = "none";
        }

        function showChangePricePopup() {
            document.getElementById("changePricePopup").style.display = "block";
        }

        function closeChangePricePopup() {
            document.getElementById("changePricePopup").style.display = "none";
        }
    </script>
</body>
</html>
