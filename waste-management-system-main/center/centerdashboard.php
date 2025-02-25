<?php
require_once 'controllerUserData.php';
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if ($email != false && $password != false) {
    $sql = "SELECT * FROM center WHERE email = '$email'";
    $run_Sql = mysqli_query($con, $sql);
    if ($run_Sql) {
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['cstatus'];
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
    header('Location: center-login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recycle Center Dashboard</title>
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
            visibility: hidden;
        }
        .popup-table-container, .popup-table-container * {
            visibility: visible;
        }
        .popup-table-container {
            position: absolute;
            left: 0;
            top: 0;
        }
        button.no-print {
            display: none !important;
        }
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
            <li><a href="#"><h5><?php echo 'Recycle center:' . $email; ?></h5></a></li>
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
        <h1>Welcome Recycle Center!</h1>
    </div>

    <div class="dashboard-container">
        <!-- <?php
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
        -->

        <?php
        $query1 = mysqli_query($con, "SELECT workingon FROM center WHERE email='$email'");
        $workingon_row = mysqli_fetch_assoc($query1);
        $workingon = $workingon_row['workingon'];
        
        $query3 = mysqli_query($con, "SELECT * FROM garbageinfo WHERE wstatus='pending' AND wastetype='$workingon'");
        
        $Availablecounts = mysqli_num_rows($query3);
        ?> 
        <div class="dashbox" onclick="showAvailablePopup()">
            <h4> Available Garbage </h4>
            <h3><?php echo $Availablecounts; ?></h3>
        </div>


        <?php
        $query3 = mysqli_query($con, "SELECT garbageinfo.centerid, garbageinfo.email, garbageinfo.wastetype, garbageinfo.location, garbageinfo.accepteddate 
        FROM center
        JOIN garbageinfo ON garbageinfo.centerid = center.centerid 
        WHERE garbageinfo.wstatus='accepted' AND center.email='$email'");

        if (!$query3) {
        // Query execution failed
        die('Query execution failed: ' . mysqli_error($con));
        }

        $collectioncounts = mysqli_num_rows($query3);

        ?> 
        <div class="dashbox" onclick="showCollectionDetailsPopup()">
            <h4> Total Garbage Collected </h4>
            <h3><?php echo $collectioncounts; ?></h3>
        </div>
    </div>

    <!-- Popup Table for user details 
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Retrieve user data from usertable
                    $query = "SELECT id, name, email, contactno, city FROM usertable";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        // Loop through each row of data
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['contactno'] . "</td>";
                            echo "<td>" . $row['city'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Error handling if query fails
                        echo "Error: " . mysqli_error($con);
                    }
                    ?>
                </tbody>
            </table>
            <button onclick="closeUserDetailsPopup()">Close</button>
        </div>
    </div>
-->
    <!-- Popup Table for center details 
    <div class="popup-table-container" id="centerDetailsPopup">
        <div class="popup-table">
            <h1>Center Details</h1>
            <table>
                <thead>
                    <tr>
                        <th>Center ID</th>
                        <th>CenterName</th>
                        <th>Center Email</th>
                        <th>Contact No</th>
                        <th>City</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Retrieve user data from usertable
                    $query = "SELECT centerid, centername, email, contactno, city FROM center";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        // Loop through each row of data
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['centerid'] . "</td>";
                            echo "<td>" . $row['centername'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['contactno'] . "</td>";
                            echo "<td>" . $row['city'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Error handling if query fails
                        echo "Error: " . mysqli_error($con);
                    }
                    ?>
                </tbody>
            </table>
            <button onclick="closeCenterDetailsPopup()">Close</button>
        </div>
    </div>
    -->

     <!-- Popup Table for garbage details 
     <div class="popup-table-container" id="garbageDetailsPopup">
        <div class="popup-table">
            <h1>Total Garbage Details</h1>
            <table>
                <thead>
                    <tr>
                        <th>Garbage ID</th>
                        <th>User ID</th>
                        <th>User name</th>
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
            <button onclick="closeGarbageDetailsPopup()">Close</button>
        </div>
    </div> -->

<!-- Popup Table for collection details -->
<div class="popup-table-container" id="collectionDetailsPopup">
    <div class="popup-table">
        <h1>Total Garbage Collection Details</h1>
        <table>
            <thead>
                <tr>
                    <th>Center ID</th>
                    <th>User email</th>
                    <th>Waste Type</th>
                    <th>SubWaste Type</th>
                    <th>Weight</th>
                    <th>Probable Cost</th>
                    <th>Location</th>
                    <th>Request Accepted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve user data from usertable
                $query = "SELECT garbageinfo.centerid, garbageinfo.email, garbageinfo.wastetype,garbageinfo.subwastetype,garbageinfo.weight,garbageinfo.probable_cost, garbageinfo.location, garbageinfo.accepteddate 
                            FROM center
                            JOIN garbageinfo ON garbageinfo.centerid = center.centerid 
                            WHERE garbageinfo.wstatus='accepted'AND center.email='$email'";
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
        <button class="no-print no-print-print" onclick="printPopupTable()">Print</button>
        <button class="no-print no-print-close" onclick="closeCollectionDetailsPopup()">Close</button>
    </div>
</div>

    <?php
// Assuming $con is your database connection

// Retrieve center ID based on email
$query1 = "SELECT centerid FROM center WHERE email='$email'";
$centerResult = mysqli_query($con, $query1);
$centerRow = mysqli_fetch_assoc($centerResult);
$centerId = $centerRow['centerid'];
$query10 = mysqli_query($con, "SELECT workingon FROM center WHERE email='$email'");
$workingon_row = mysqli_fetch_assoc($query10);
$workingon = $workingon_row['workingon'];

// Retrieve pending garbage information
$query = "SELECT usertable.id, usertable.name, usertable.contactno,garbageinfo.file, garbageinfo.wastetype,garbageinfo.subwastetype,garbageinfo.weight,garbageinfo.probable_cost,garbageinfo.scheduledate, garbageinfo.timeslot, garbageinfo.location,garbageinfo.locationdescription, garbageinfo.garbageid as garbageid
          FROM garbageinfo
          JOIN usertable ON garbageinfo.email = usertable.email
          WHERE garbageinfo.wstatus='pending' AND garbageinfo.wastetype='$workingon'";

$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Garbage Management</title>
</head>
<body>

<div class="popup-table-container" id="AvailablePopup">
    <div class="popup-table">
        <h1>Total Garbage Available Details</h1>
        <table>
            <thead>
                <tr>
                    <!--<th>User ID</th>
                    <th>Garbage ID</th>-->
                    <th>User name</th>
                    <th>User contactno</th>
                    <th>Waste Type</th>
                    <th>SubWaste Type</th>
                    <th>Weight</th>
                    <th>Probable Cost</th>
                    <th>Schedule Date</th>
                    <th>Schedule Time</th>
                    <th>Image of Waste</th>
                    <th>Location</th>
                    <th>Location Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    // Loop through each row of data
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                       // echo "<td>" . $row['id'] . "</td>";
                       // echo "<td>" . $row['garbageid'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['contactno'] . "</td>";
                        echo "<td>" . $row['wastetype'] . "</td>";
                        echo "<td>" . $row['subwastetype'] . "</td>";
                        echo "<td>" . $row['weight'] . "</td>";
                        echo "<td>" . $row['probable_cost'] . "</td>";
                        echo "<td>" . $row['scheduledate'] . "</td>";
                        echo "<td>" . $row['timeslot'] . "</td>";
                        echo "<td><a href='../" . $row['file'] . "' target='_blank'><img src='../symbol.jpg' alt='Image'></a></td>";

                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['locationdescription'] . "</td>";

                        // Adding form and buttons for accepted and not accepted
                        echo '<td>
                                <form id="updateForm'.$row['id'].'" action="" method="post">
                                    <input type="hidden" name="centerId" value="'.$centerId.'">
                                    <input type="hidden" name="garbageid" value="'.$row['garbageid'].'">
                                    <input type="hidden" name="status" id="status'.$row['id'].'" value="">
                                    <input type="hidden" name="accepteddate" id="accepteddate'.$row['id'].'" value="'.date('Y-m-d H:i:s').'"> <!-- Add accepted date here -->
                                    <button onclick="updateStatus(\'accepted\', \''.$row['id'].'\')" style="background-color: green;">Accepted</button>
                                    <button onclick="updateStatus(\'notaccepted\', \''.$row['id'].'\')" style="background-color: red;">Not Accepted</button>
                                </form>
                            </td>';

                        echo "</tr>";
                    }
                } else {
                    // Error handling if query fails
                    echo "Error: " . mysqli_error($con);
                }
                ?>
            </tbody>
        </table>
        <button onclick="closeAvailablePopup()">Close</button>
    </div>
</div>

<script>
function updateStatus(status, id) {
    // Update the hidden input fields with necessary data
    document.getElementById("status" + id).value = status;
    
    // Submit the form
    document.getElementById("updateForm" + id).submit();
}
</script>

</body>
</html>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the data from the form submission
    $centerId = $_POST['centerId'];
    $status = $_POST['status'];
    $garbageid = $_POST['garbageid']; // Retrieving garbageid from form submission

    // Initialize update query variable
    $updateQuery = '';

    // Update database based on status
    if ($status == 'accepted') {
        // Check if accepteddate is set, if not, set it to current date and time
        $accepteddate = isset($_POST['accepteddate']) ? $_POST['accepteddate'] : date('Y-m-d H:i:s');
        $updateQuery = "UPDATE garbageinfo SET wstatus = 'accepted', accepteddate = '$accepteddate', centerid = '$centerId' WHERE garbageid= $garbageid";
    } elseif ($status == 'notaccepted') {
        // Check if accepteddate is set, if not, set it to current date and time
        $accepteddate = isset($_POST['accepteddate']) ? $_POST['accepteddate'] : date('Y-m-d H:i:s');
        $updateQuery = "UPDATE garbageinfo SET wstatus = 'not accepted', accepteddate = '$accepteddate', centerid = '$centerId' WHERE garbageid = '$garbageid'";
    }

    // Execute update query if it's not empty
    if (!empty($updateQuery)) {
        $updateResult = mysqli_query($con, $updateQuery);
        if (!$updateResult) {
            // Error handling if update fails
            echo "Error updating record: " . mysqli_error($con);
        }
    }
}
?>


<script>
    // Function to print the popup table details
    function printPopupTable() {
        // Hide the print and close buttons
        var printButton = document.querySelector('.no-print-print');
        printButton.style.display = 'none';
        
        var closeButton = document.querySelector('.no-print-close');
        closeButton.style.display = 'none';

        // Open a new window for printing
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Popup Table Print</title></head><body>');
        printWindow.document.write(document.getElementById('collectionDetailsPopup').innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Print and close the print window
        printWindow.print();
        
        // Restore the buttons after printing
        printButton.style.display = 'block';
        closeButton.style.display = 'block';
    }
</script>

    <!-- jQuery -->
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

    <script>
        // function showUserDetailsPopup() {
        //     document.getElementById("userDetailsPopup").style.display = "block";
        // }

        // function closeUserDetailsPopup() {
        //     document.getElementById("userDetailsPopup").style.display = "none";
        // }

        // function showCenterDetailsPopup() {
        //     document.getElementById("centerDetailsPopup").style.display = "block";
        // }

        // function closeCenterDetailsPopup() {
        //     document.getElementById("centerDetailsPopup").style.display = "none";
        // }

        // function showGarbageDetailsPopup() {
        //     document.getElementById("garbageDetailsPopup").style.display = "block";
        // }

        // function closeGarbageDetailsPopup() {
        //     document.getElementById("garbageDetailsPopup").style.display = "none";
        // }


        function showAvailablePopup() {
            document.getElementById("AvailablePopup").style.display = "block";
        }

        function closeAvailablePopup() {
            document.getElementById("AvailablePopup").style.display = "none";
        }


        function showCollectionDetailsPopup() {
            document.getElementById("collectionDetailsPopup").style.display = "block";
        }


        function closeCollectionDetailsPopup() {
            document.getElementById("collectionDetailsPopup").style.display = "none";
        }

        
    </script>


</body>
</html>
