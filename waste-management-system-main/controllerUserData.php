<?php 
session_start();
require "connection.php";
$email = "";
$name = "";
$district="";
$taluka="";
$city="";
$contactno="";
$wastetype="";
$location="";
$file="";
$file_name ="";
$file_tmp ="";
$wstatus="";
$timeslot="";
$scheduledate="";
$locationdescription="";
$errors = array();
$probableCost="";

// If user signup button is clicked
if(isset($_POST['signup'])){
    // Escape user inputs to prevent SQL injection
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $contactno = mysqli_real_escape_string($con, $_POST['contactno']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $district = mysqli_real_escape_string($con, $_POST['district']);
    $taluka = mysqli_real_escape_string($con, $_POST['taluka']);
    $city = mysqli_real_escape_string($con, $_POST['city']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);

    // Check if passwords match
    if($password !== $cpassword){
        $errors['password'] = "Confirm password does not match!";
    }

    // Check if email already exists
    $email_check = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email already exists!";
    }

    // If no errors, proceed with registration
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO usertable (name, contactno, email, district, taluka, city, password, code, status)
                        VALUES ('$name', '$contactno', '$email', '$district', '$taluka', '$city', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            $subject = "Email Verification Code";
            $message = "Your verification code is $code";
            $sender = "From:prathmeshshinde1900@gmail.com";
            if(mail($email, $subject, $message, $sender)){
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            }else{
                $errors['otp-error'] = "Failed while sending code!";
            }
        }else{
            $errors['db-error'] = "Failed while inserting data into the database!";
        }
    }
}

// Add garbage by user 
if(isset($_POST['Addgarbage'])) {
    include('connection.php'); // Include your database connection file

        // Escape user inputs to prevent SQL injection
        $location = mysqli_real_escape_string($con, $_POST['location']);
        $locationdescription = mysqli_real_escape_string($con, $_POST['locationdescription']);
        $wastetype = mysqli_real_escape_string($con, $_POST['wastetype']);  // Assuming only one wastetype is selected
        $subwastetype = mysqli_real_escape_string($con, $_POST['subwastetype']);// Added subwastetype
        $scheduledate = mysqli_real_escape_string($con, $_POST['scheduledate']);
        $timeslot = mysqli_real_escape_string($con, $_POST['timeslot']);
        $weight = mysqli_real_escape_string($con, $_POST['weight']);
        $probable_cost = mysqli_real_escape_string($con, $_POST['probable_cost']);// Changed from probableCostValue to probable_cost

// Rest of your code remains the same

    // File upload handling
    if(isset($_FILES['file'])) {
        $pic = $_FILES["file"]["name"];
        $extension = strtolower(pathinfo($pic, PATHINFO_EXTENSION)); // Get the file extension in lowercase

        // Allowed extensions
        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        // Validation for allowed extensions
        if(!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
            exit; // Stop further execution
        } else {
            // Generate a unique filename
            $file_destination = "center/uploads/" . md5($pic . time()) . ".$extension";
            // Set the destination directory to "uploads" folder
            move_uploaded_file($_FILES["file"]["tmp_name"], $file_destination);
        }
    } else {
        // Handle file upload error here
        // You might want to present an error message to the user or log it
        $file_destination = ''; // Set default value or handle as per your requirement
    }

    // Check if the user is logged in
    if(isset($_SESSION['email'])) {
        // Retrieve user information from session
        $email = $_SESSION['email'];
        
        // Insert data into the database using prepared statements
        $insert_data = "INSERT INTO garbageinfo (email, wastetype, subwastetype, scheduledate, timeslot, location, locationdescription, weight, probable_cost, file, date, wstatus)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(),'pending')";
        $stmt = mysqli_prepare($con, $insert_data);
        mysqli_stmt_bind_param($stmt, "ssssssssss", $email, $wastetype, $subwastetype, $scheduledate, $timeslot, $location, $locationdescription, $weight, $probable_cost, $file_destination);
        
        if(mysqli_stmt_execute($stmt)) {
            // Data inserted successfully
            header('location: userdashboard.php');
            exit; // Make sure to exit after a header redirect
        } else {
            // Error inserting data
            echo 'Error: ' . mysqli_error($con);
        }

        mysqli_stmt_close($stmt); // Close the prepared statement
    } else {
        // User is not logged in
        echo 'User is not logged in.';
    }
}



// If user click verification code submit button
if(isset($_POST['check'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE usertable SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($con, $update_otp);
        if($update_res){
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header('location: login-user.php');
            exit();
        }else{
            $errors['otp-error'] = "Failed while updating code!";
        }
    }else{
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

// If user click login button
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM usertable WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            if($status == 'verified'){
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: userdashboard.php');
                exit();
            }else{
                $info = "It's look like you haven't still verify your email - $email";
                $_SESSION['info'] = $info;
                header('location: user-otp.php');
            }
        }else{
            $errors['email'] = "Incorrect email or password!";
        }
    }else{
        $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
    }
}

// If login now button is clicked
if(isset($_POST['login-now'])){
    header('Location: login-user.php');
}

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM usertable WHERE email='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE usertable SET code = $code WHERE email = '$email'";
            $run_query =  mysqli_query($con, $insert_code);
            if($run_query){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
                $sender = "From:prathmeshshinde1900@gmail.com";
                if(mail($email, $subject, $message, $sender)){
                    $info = "We've sent a passwrod reset otp to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    header('location: reset-code.php');
                    exit();
                }else{
                    $errors['otp-error'] = "Failed while sending code!";
                }
            }else{
                $errors['db-error'] = "Something went wrong!";
            }
        }else{
            $errors['email'] = "This email address does not exist!";
        }
    }

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        $_SESSION['info'] = "";
        $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
        $check_code = "SELECT * FROM usertable WHERE code = $otp_code";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $email = $fetch_data['email'];
            $_SESSION['email'] = $email;
            $info = "Please create a new password that you don't use on any other site.";
            $_SESSION['info'] = $info;
            header('location: new-password.php');
            exit();
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        $_SESSION['info'] = "";
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE usertable SET code = $code, password = '$encpass' WHERE email = '$email'";
            $run_query = mysqli_query($con, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: password-changed.php');
            }else{
                $errors['db-error'] = "Failed to change your password!";
            }
        }
    }
    
   //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: login-user.php');
    }
?>