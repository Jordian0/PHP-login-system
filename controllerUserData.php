<?php 
session_start();
require "connection.php";
require "mail-config.php";
$email = "";
$name = "";
$errors = array();

ini_set('display_errors', 1);
error_reporting(E_ALL);


//if user signup button
if(isset($_POST['signup'])){
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    $rvalue = $_POST['radiovalue'];

    if($password !== $cpassword){
        $errors['password'] = "Confirm password not matched!";
    }

    $email_check = "SELECT name, email FROM doctor_table WHERE email = '$email' UNION SELECT name, email FROM patient_table WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if(mysqli_num_rows($res) > 0){
        $errors['email'] = "Email that you have entered is already exist!";
    }
    if(count($errors) == 0){
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = 0;
        $status = "notverified";
        if($rvalue == "doctor") {
            $insert_data = "INSERT INTO doctor_table (name, email, password, code, status) values('$name', '$email', '$encpass', '$code', '$status')";
        } else if($rvalue == "patient") {
            $insert_data = "INSERT INTO patient_table (name, email, password, code, status) values('$name', '$email', '$encpass', '$code', '$status')";
        }

        $data_check = mysqli_query($con, $insert_data);
        if($data_check){
            header('location: login-user.php');
        } else {
            $errors['db-error'] = "Failed while inserting data into database!";
        }
    }

}
    //if user click verification code submit button
    if(isset($_POST['check'])){
        unset($_SESSION['info']);
        $email = mysqli_real_escape_string($con, $_SESSION['email']);
        $check_code = "SELECT name, email, code FROM doctor_table WHERE email = '$email'
                        UNION
                        SELECT name, email, code FROM patient_table WHERE email = '$email'";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $code = $_POST['otp'];

            if($code == $fetch_code) {
                $status = 'verified';
                $update_otp = "UPDATE doctor_table SET status = '$status' WHERE email = '$email';
                                UPDATE patient_table SET status = '$status' WHERE email='$email'";
                $update_res = mysqli_multi_query($con, $update_otp);

                $_SESSION['email'] = $email;
                $_SESSION['password'] = true;
                header('location: home.php');
                exit();
            } else {
                $errors['otp-error'] = "You've entered incorrect code!";
            }
        }else{
            $errors['otp-error'] = "You've entered incorrect email!";
        }
    }

    //if user click login button
    if(isset($_POST['login'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $check_email = "SELECT name, email, password, code, status FROM doctor_table WHERE email = '$email'
                        UNION
                        SELECT name, email, password, code, status FROM patient_table WHERE email='$email'";

        $res = mysqli_query($con, $check_email);
        if(mysqli_num_rows($res) > 0){
            $fetch = mysqli_fetch_assoc($res);
            $fetch_pass = $fetch['password'];
            if(password_verify($password, $fetch_pass)){
                $_SESSION['email'] = $email;

                // opt authentication
                $code = mt_rand(100000, 999999);
                $update_otp = "UPDATE doctor_table SET code = $code WHERE email = '$email'; UPDATE patient_table SET code = $code WHERE email = '$email';";
                $update_res = mysqli_multi_query($con, $update_otp);

                $subject = "Email Verification Code";
                $message = "Your verification code is $code";
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->Body = $message;
                if($mail->send()){
                    $info = "We've sent a verification code to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    header('location: user-otp.php');
                    exit();
                }else{
                    $errors['otp-error'] = "Failed while sending code!";
                }
            }else{
                $errors['email'] = "Incorrect email or password!";
            }
        }else{
            $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
        }
    }

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $check_email = "SELECT name, email FROM doctor_table WHERE email='$email' UNION SELECT name, email FROM patient_table WHERE email='$email'";
        $run_sql = mysqli_query($con, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = mt_rand(100000, 999999);
            $insert_code = "UPDATE doctor_table SET code = $code WHERE email = '$email'; UPDATE patient_table SET code = $code WHERE email = '$email';";
            $run_query =  mysqli_multi_query($con, $insert_code);
            if($run_query){
                $subject = "Password Reset Code";
                $message = "Your password reset code is $code";
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->Body = $message;
                if($mail->send()){
                    $info = "We've sent a password reset otp to your email - $email";
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
        unset($_SESSION['info']);
        $email = $_SESSION['email'];
        $check_code = "SELECT email, code FROM doctor_table WHERE email = '$email' UNION SELECT email, code FROM patient_table WHERE email='$email'";
        $code_res = mysqli_query($con, $check_code);
        if(mysqli_num_rows($code_res) > 0){
            $fetch_data = mysqli_fetch_assoc($code_res);
            $fetch_code = $fetch_data['code'];
            $code = $_POST['otp'];

            if($code == $fetch_code) {
                $_SESSION['email'] = $email;
                $_SESSION['password'] = true;
                $info = "Please create a new password that you don't use on any other site.";
                $_SESSION['info'] = $info;
                header('location: new-password.php');
                exit();
            } else {
                $errors['otp-error'] = "You've entered incorrect code!";
            }
        }else{
            $errors['otp-error'] = "You've entered incorrect code!";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        unset($_SESSION['info']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors['password'] = "Confirm password not matched!";
        }else{
            $email = $_SESSION['email']; //getting this email using session
            $encpass = password_hash($password, PASSWORD_BCRYPT);
            $update_pass = "UPDATE doctor_table SET password = '$encpass' WHERE email = '$email'; UPDATE patient_table SET password = '$encpass' WHERE email = '$email';";
            $run_query = mysqli_multi_query($con, $update_pass);
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

    // if patient form
    if(isset($_POST['patient-data-submit'])) {
        $selectedDoctor = mysqli_real_escape_string($con, $_POST['select-doctor']);
        $patientEmail = mysqli_real_escape_string($con, $_POST['user-email']);


        if(isset($_FILES['upload-report'])){
            $patientsql = "SELECT patient_id FROM patient_table WHERE email = '$patientEmail'";
            $result = mysqli_query($con, $patientsql);
            $fetch_data = mysqli_fetch_assoc($result);
            $patientID = $fetch_data['patient_id'];

            $targetdir = "uploads/";
            $uploadfile = $targetdir . basename($_FILES['upload-report']['name']);

            if($_FILES['upload-report']['size'] > 16000000){
                echo "File too large!";
            } else {
                if (move_uploaded_file($_FILES['upload-report']['tmp_name'], $uploadfile)) {
                    $filename = $_FILES['upload-report']['name'];
                    $folder_path = $targetdir;
                    $time_stamp = date('Y-m-d H:i:s');
                    $sql = "INSERT INTO file_upload (filename, folder_path, time_stamp, patient_id) VALUES ('$filename', '$folder_path', '$time_stamp', $patientID)";
                    $result = mysqli_query($con, $sql);
                }
            }
        }

        $uploaddocid = "UPDATE patient_table SET doc_id = $selectedDoctor WHERE email='$patientEmail'";
        $result = mysqli_query($con, $uploaddocid);
        if(!$result) {
            echo "Error Occurred while inserting data!";
        }

    }
?>
