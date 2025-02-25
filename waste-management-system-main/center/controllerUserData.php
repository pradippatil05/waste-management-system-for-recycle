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
$workingon="";
$locationdescription="";
$errors = array();

// If user signup button is clicked
if(isset($_POST['signup'])){
    // Escape user inputs to prevent SQL injection
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $contactno = mysqli_real_escape_string($con, $_POST['contactno']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $workingon = mysqli_real_escape_string($con, $_POST['workingon'][0]); // Assuming only one workingon is selected
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
    $email_check = "SELECT * FROM center WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email already exists!";
    }

    // If no errors, proceed with registration
    if(count($errors) === 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(999999, 111111);
        $status = "notverified";
        $insert_data = "INSERT INTO center (centername, contactno, email, workingon, district, taluka, city, password, code, cstatus)
                        VALUES ('$name', '$contactno', '$email', '$workingon','$district', '$taluka', '$city', '$encpass', '$code', '$status')";
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
            $errors['db-error'] = "Failed while inserting data into the database: " . mysqli_error($con);
        }
    }
}

/*if(isset($_POST['Addgarbage'])) {
    include('connection.php'); // Include your database connection file

    // Escape user inputs to prevent SQL injection
    $location = mysqli_real_escape_string($con, $_POST['location']);
    $locationdescription = mysqli_real_escape_string($con, $_POST['locationdescription']);
    $wastetype = mysqli_real_escape_string($con, $_POST['wastetype'][0]); // Assuming only one wastetype is selected

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
            $file_destination = "uploads/" . md5($pic . time()) . ".$extension";
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
        $insert_data = "INSERT INTO garbageinfo (email, wastetype, location, locationdescription, file, date, wstatus)
                        VALUES (?, ?, ?, ?, ?, NOW(),'pending')";
        $stmt = mysqli_prepare($con, $insert_data);
        mysqli_stmt_bind_param($stmt, "sssss", $email, $wastetype, $location, $locationdescription, $file_destination);
        
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

*/

// If user click verification code submit button
if(isset($_POST['check'])){
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM center WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if(mysqli_num_rows($code_res) > 0){
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE center SET code = $code, cstatus = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($con, $update_otp);
        if($update_res){
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header('location: center-login.php');
            exit();
        }else{
            $errors['otp-error'] = "Failed while updating code!";
        }
    }else{
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

// If user click login button
if(isset($_POST['Login'])){
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM center WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if(mysqli_num_rows($res) > 0){
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password'];
        if(password_verify($password, $fetch_pass)){
            $_SESSION['email'] = $email;
            $status = $fetch['cstatus'];
            if($status == 'verified'){
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: centerdashboard.php');
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
    header('Location: center-login.php');
}

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT * FROM center WHERE email='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE center SET code = $code WHERE email = '$email'";
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
        $check_code = "SELECT * FROM center WHERE code = $otp_code";
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
            $update_pass = "UPDATE center SET code = $code, password = '$encpass' WHERE email = '$email'";
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
        header('Location: center-login.php');
    }
?>