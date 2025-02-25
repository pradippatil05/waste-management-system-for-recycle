<?php
require_once 'controllerUserData.php';
$email = $_SESSION['email'];
$password = $_SESSION['password'];
if ($email != false && $password != false) {
    $sql = "SELECT * FROM usertable WHERE email = '$email'";
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
    header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
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


/* Style for container */
.container {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 9999;
    background: linear-gradient(to bottom right, #ffffff, #f0f0f0); /* Background color gradient */
    padding: 30px;
    border-radius: 20px;
    box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    max-width: 60%;
}



/* Style for card */
.card {
    border: none;
}

/* Style for form control */
.form-control {
    border-radius: 0;
}

/* Style for card header */
.card-header {
    border-bottom: none;
}

/* Style for file input */
.form-control-file {
    cursor: pointer;
}

/* Style for submit button */
.btn-primary {
    margin-right: 10px;
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
            <li><a href="#"><h5><?php echo 'User:' . $email; ?></h5></a></li>
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
        <h1>Welcome User !</h1>
    </div>

    <div class="dashboard-container">
        <?php
        $query3 = mysqli_query($con, "SELECT * FROM usertable JOIN garbageinfo ON garbageinfo.email = usertable.email  where garbageinfo.email='$email'");
        $usercounts = mysqli_num_rows($query3);
        ?>
        <div class="dashbox" onclick="showgarbageaddedPopup()">
            <h4>Total Garbage Added </h4>
            <h3><?php echo $usercounts; ?></h3>
        </div>


        <?php
        $query3 = mysqli_query($con, "SELECT * FROM garbageinfo
                                        JOIN usertable ON usertable.email = garbageinfo.email WHERE garbageinfo.wstatus='pending' and garbageinfo.email='$email'");
        $garbagependingcounts = mysqli_num_rows($query3);
        ?>
        <div class="dashbox" onclick="showgarbagependingPopup()">
            <h4>garbage order pending</h4>
            <h3><?php echo $garbagependingcounts; ?></h3>
        </div>


        <?php
        $query3 = mysqli_query($con, "SELECT * FROM garbageinfo
        JOIN usertable ON usertable.email = garbageinfo.email WHERE garbageinfo.wstatus='not accepted' And garbageinfo.email='$email'");
        $garbagerejectedcounts = mysqli_num_rows($query3);
        ?>
        <div class="dashbox" onclick="showgarbagerejectedPopup()">
            <h4>garbage order Not Accepted</h4>
            <h3><?php echo $garbagerejectedcounts; ?></h3>
        </div>

        <?php
            $query3 = mysqli_query($con, "SELECT * FROM garbageinfo 
                JOIN usertable ON usertable.email = garbageinfo.email WHERE garbageinfo.wstatus='accepted' AND garbageinfo.email='$email'");
            $Garbagecounts = mysqli_num_rows($query3);
            ?>
        <div class="dashbox" onclick="showgarbagedeliveredPopup()">
            <h4>total garbage Accepted </h4>
            <h3><?php echo $Garbagecounts; ?></h3>
        </div>

        <div class="dashbox" onclick="showgarbageaddPopup()">
        <h4><i class="fa fa-fw fa-user"></i>Add garbage details</h4>
        <div class="form-group">
            <!--<input class="form-control button" type="button" value="Add Garbage">  -->
        </div>
        </div>
    </div>


<!-- Popup Table for added details -->
<div class="popup-table-container" id="addedPopup">
        <div class="popup-table">
            <h1>Total Garbage added Details</h1>
            <table>
                <thead>
                    <tr>
                        <th>User Email</th>
                        <th>Garbage ID</th>
                        <th>User name</th>
                        <th>Waste Type</th>
                        <th>Waste location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  
                    $query = "SELECT usertable.email, garbageinfo.garbageid , usertable.name, garbageinfo.wastetype, garbageinfo.location FROM garbageinfo
                    JOIN usertable ON usertable.email = garbageinfo.email where garbageinfo.email='$email'";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        // Loop through each row of data
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['garbageid'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
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
            <button onclick="closegarbageaddedPopup()">Close</button>
        </div>
    </div>


    

<!-- Popup Table for pending details -->
<div class="popup-table-container" id="pendingPopup">
        <div class="popup-table">
            <h1>Total Garbage Pending Details</h1>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Garbage ID</th>
                        <th>User name</th>
                        <th>Waste Type</th>
                        <th>Waste location</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  
                    $query = "SELECT usertable.id, garbageinfo.garbageid , usertable.name, garbageinfo.wastetype, garbageinfo.location, garbageinfo.wstatus FROM garbageinfo
                    JOIN usertable ON usertable.email = garbageinfo.email WHERE garbageinfo.wstatus='pending' and garbageinfo.email='$email'";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        // Loop through each row of data
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['garbageid'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            
                            echo "<td>" . $row['wastetype'] . "</td>";
                            echo "<td>" . $row['location'] . "</td>";
                            echo "<td>" . $row['wstatus'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Error handling if query fails
                        echo "Error: " . mysqli_error($con);
                    }
                    ?>
                </tbody>
            </table>
            <button onclick="closegarbagependingPopup()">Close</button>
        </div>
    </div>




<!-- Popup Table for Rejected details -->
<div class="popup-table-container" id="rejectedPopup">
        <div class="popup-table">
            <h1>Total Garbage Not Accepted Details</h1>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Garbage ID</th>
                        <th>User name</th>
                        <th>Waste Type</th>
                        <th>Waste location</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  
                    $query = "SELECT usertable.id, garbageinfo.garbageid , usertable.name, garbageinfo.wastetype, garbageinfo.location, garbageinfo.wstatus FROM garbageinfo
                    JOIN usertable ON usertable.email = garbageinfo.email WHERE garbageinfo.wstatus='not accepted' and garbageinfo.email='$email'";
                    $result = mysqli_query($con, $query);
                    if ($result) {
                        // Loop through each row of data
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['garbageid'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>" . $row['wastetype'] . "</td>";
                            echo "<td>" . $row['location'] . "</td>";
                            echo "<td>" . $row['wstatus'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        // Error handling if query fails
                        echo "Error: " . mysqli_error($con);
                    }
                    ?>
                </tbody>
            </table>
            <button onclick="closegarbagerejectedPopup()">Close</button>
        </div>
    </div>

<!-- Popup Table for collected details -->
<div class="popup-table-container" id="deliveredPopup">
    <div class="popup-table">
        <h1>Total Garbage Accepted Details</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Garbage ID</th>
                    <th>Center Name</th>
                    <th>Center Contact No</th>
                    <th>User name</th>
                    <th>Waste Type</th>
                    <th>Sub waste Type</th>
                    <th>Weight</th>
                    <th>Probable Cost</th>
                    <th>Waste location</th>
                    <th>Status</th>
                    <th>Request Accepted Date</th> <!-- Corrected column name -->
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT usertable.id, garbageinfo.garbageid ,center.centername,center.contactno, usertable.name, garbageinfo.wastetype,garbageinfo.subwastetype,garbageinfo.weight,garbageinfo.probable_cost, garbageinfo.location, garbageinfo.wstatus, garbageinfo.accepteddate FROM 
                usertable join garbageinfo ON usertable.email = garbageinfo.email 
                join center on center.centerid= garbageinfo.centerid WHERE garbageinfo.wstatus='accepted' and garbageinfo.email='$email'";
                $result = mysqli_query($con, $query);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['garbageid'] . "</td>";
                        echo "<td>" . $row['centername'] . "</td>";
                        echo "<td>" . $row['contactno'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['wastetype'] . "</td>";
                        echo "<td>" . $row['subwastetype'] . "</td>";
                        echo "<td>" . $row['weight'] . "</td>";
                        echo "<td>" . $row['probable_cost'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['wstatus'] . "</td>";
                        echo "<td>" . $row['accepteddate'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "Error: " . mysqli_error($con);
                }
                ?>
            </tbody>
        </table>
        <button class="no-print no-print-print" onclick="printPopupTable()">Print</button>
        <button class="no-print no-print-close" onclick="closegarbagedeliveredPopup()">Close</button>

    </div> <!-- Close div class="popup-table" -->
</div> <!-- Close div class="popup-table-container" -->




<!-- Add garbage popup -->
<div class="container" id="garbageaddPopup" style="display: none;">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Add Garbage Details</h2>
                </div>
                <div class="card-body">
                    <form action="userdashboard.php" method="POST" enctype="multipart/form-data" autocomplete="off">

                        <div class="form-group">
                            <label for="wastetype">Waste Type</label>
                            <select class="form-control" id="wastetype" name="wastetype" required onchange="updateOptions()">
                                <option value="">Select Waste Type</option>
                                <option value="paper">Paper Waste</option>
                                <option value="plastic">Plastic Waste</option>
                                <option value="metal">Metal Waste</option>
                                <option value="electronics">Electronics Waste</option>
                                <option value="hazardous">Hazardous Waste</option>
                                <option value="other">Other Waste</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="subwastetype">Sub Waste Type</label>
                            <select class="form-control" id="subwastetype" name="subwastetype" required>
                                <!-- Options will be dynamically populated here -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="weight">Weight in Kg</label>
                            <input class="form-control" type="text" id="weight" name="weight" placeholder="Enter weight of material" required onkeyup="calculateProbableCost()">
                        </div>

                        <div class="form-group">
                            <label for="scheduledate">Schedule Date</label>
                            <input type="date" name="scheduledate" id="scheduledate" class="form-control" placeholder="Select waste scheduling date" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" />
                        </div>
                        
                        <div class="form-group">
                            <label for="timeslot">Schedule Slot</label>
                            <select class="form-control" id="timeslot" name="timeslot" required>
                                <option value="">Select Schedule Slot</option>
                                <option value="9am-12pm">9 am - 12 pm</option>
                                <option value="12pm-3pm">12 pm - 3 pm</option>
                                <option value="3pm-6pm">3 pm - 6 pm</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="location">Location</label>
                            <input class="form-control" type="text" id="location" name="location" placeholder="Enter location" required>
                        </div>

                        <div class="form-group">
                            <label for="locationdescription">Location Description</label>
                            <input class="form-control" type="text" id="locationdescription" name="locationdescription" placeholder="Enter location description" required>
                        </div>

                        <div class="form-group">
                            <label for="file">Image of Garbage</label>
                            <input class="form-control" type="file" id="file" name="file" required>
                        </div>

                        <!-- Display probable cost -->
                        <div id="probableCost"></div>

                        <!-- Hidden input field for probable_cost -->
                        <input type="hidden" id="probableCostValue" name="probable_cost">

                        <button type="submit" value="Addgarbage" name="Addgarbage" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" onclick="closegarbageaddPopup()">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateOptions() {
        var selectElement = document.getElementById("wastetype");
        var selectedValue = selectElement.value;
        var secondSelectElement = document.getElementById("subwastetype");
        var options = "";

        switch (selectedValue) {
            case "paper":
                options = "<option value='newspaper'>Newspaper</option>" +
                          "<option value='books'>Books</option>" +
                          "<option value='carton'>Carton</option>" +
                          "<option value='white_papers'>White Papers</option>" +
                          "<option value='magazines'>Magazines</option>";
                break;
            case "plastic":
                options = "<option value='polythene'>Polythene</option>" +
                          "<option value='hard_plastic'>Hard Plastic</option>" +
                          "<option value='soft_plastic'>Soft Plastic</option>" +
                          "<option value='fibre'>Fibre</option>";
                break;
            case "metal":
                options = "<option value='copper'>Copper</option>" +
                          "<option value='brass'>Brass</option>" +
                          "<option value='aluminum'>Aluminum</option>" +
                          "<option value='steel'>Steel</option>" +
                          "<option value='iron'>Iron</option>" +
                          "<option value='tin'>Tin</option>" +
                          "<option value='wires'>Wires</option>";
                break;
            case "electronics":
                options = "<option value='E-waste'>E-waste</option>";
                break;
            case "hazardous":
                options = "<option value='battery'>Battery</option>" +
                          "<option value='glass'>Glass</option>" +
                          "<option value='solvents_chemicals'>Solvents & Chemicals</option>" +
                          "<option value='oil_paints'>Oil Paints</option>" +
                          "<option value='asbestos_material'>Asbestos Material</option>" +
                          "<option value='PCB_materials'>PCB Materials</option>";
                break;
            case "other":
                options = "<option value='tyre'>Tyre</option>" +
                          "<option value='mix_waste'>Mix Waste</option>";
                break;
            default:
                options = "";
                break;
        }

        secondSelectElement.innerHTML = options;
    }

    function calculateProbableCost() {
        var weight = parseFloat(document.getElementById("weight").value);
        var selectedSubWasteType = document.getElementById("subwastetype").value;

        // Submit weight and selected sub waste type to the server using a form
        var form = new FormData();
        form.append('weight', weight);
        form.append('subwastetype', selectedSubWasteType);

        // Make a server request using Fetch API
        fetch('get_price.php', {
            method: 'POST',
            body: form
        })
        .then(response => response.text())
        .then(price => {
            // Check if price is a valid number
            if (!isNaN(parseFloat(price))) {
                var probableCost = parseFloat(price) * weight;
                document.getElementById("probableCost").innerHTML = "Probable Cost: ₹" + probableCost.toFixed(2);

                // Set probable_cost directly from the calculated value
                var probableCostValue = document.getElementById("probableCostValue");
                probableCostValue.value = probableCost.toFixed(2);
            } else {
                document.getElementById("probableCost").innerHTML = "Price not available";
            }
        })
        .catch(error => {
            console.error('Error fetching price:', error);
            document.getElementById("probableCost").innerHTML = "Error fetching price";
        });
    }
</script>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set min date to tomorrow
    $(document).ready(function() {
        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        var minDate = tomorrow.toISOString().split('T')[0];
        $('#scheduledate').attr('min', minDate);
    });
</script>

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
        printWindow.document.write(document.getElementById('deliveredPopup').innerHTML);
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
        
        function showgarbageaddedPopup() {
            document.getElementById("addedPopup").style.display = "block";
        }

        function closegarbageaddedPopup() {
            document.getElementById("addedPopup").style.display = "none";
        }

        function showgarbagerejectedPopup() {
            document.getElementById("rejectedPopup").style.display = "block";
        }

        function closegarbagerejectedPopup() {
            document.getElementById("rejectedPopup").style.display = "none";
        }

        function showgarbagedeliveredPopup() {
            document.getElementById("deliveredPopup").style.display = "block";
        }

        function closegarbagedeliveredPopup() {
            document.getElementById("deliveredPopup").style.display = "none";
        }

        function showgarbageaddPopup() {
        document.getElementById("garbageaddPopup").style.display = "block";
        }

        function closegarbageaddPopup() {
            document.getElementById("garbageaddPopup").style.display = "none";
        }
        function showgarbagependingPopup() {
            document.getElementById("pendingPopup").style.display = "block";
        }

        function closegarbagependingPopup() {
            document.getElementById("pendingPopup").style.display = "none";
        }

        function addWasteType() {
        // Clone the first waste type select element
        var firstWasteType = document.getElementById("wastetype");
        var newWasteType = firstWasteType.cloneNode(true);
        
        // Clear the selected option in the cloned element
        newWasteType.selectedIndex = 0;
        
        // Append the cloned select element to the container
        document.getElementById("wasteTypesContainer").appendChild(newWasteType);
        }
        

    </script>
</body>
</html>
<!-- <?php
// Perform the query to fetch center names
$query = "SELECT centername FROM center";
$result = mysqli_query($con, $query);

// Check if query was successful
if ($result) {
    // Start the select element
    echo '<div class="form-group">';
    echo '<select name="centername" id="centername" class="form-control">';
    echo '<option value="">Select Center</option>'; // Placeholder option

    // Loop through query results and create an option for each center
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row['centername'] . '">' . $row['centername'] . '</option>';
    }

    // Close the select element and form group
    echo '</select>';
    echo '</div>';
} else {
    // Error handling if query fails
    echo 'Error fetching center names: ' . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);


    ?> -->
