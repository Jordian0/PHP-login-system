<?php require_once "controllerUserData.php"; ?>
<?php 
$email = mysqli_real_escape_string($con, $_SESSION['email']);
$password = $_SESSION['password'];
if($email != false && $password != false){
    $doctor_sql = mysqli_query($con, "SELECT doc_id FROM doctor_table WHERE email='$email'");

    $sql = "SELECT name, status FROM doctor_table WHERE email = '$email' UNION SELECT name, status FROM patient_table WHERE email='$email'";
    $run_Sql = mysqli_query($con, $sql);
    if($run_Sql){
        $fetch_info = mysqli_fetch_assoc($run_Sql);
        $status = $fetch_info['status'];
        if($status != "verified"){
            header('Location: user-otp.php');
        }
    }
}else{
    header('Location: login-user.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $fetch_info['name'] ?> | Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
    nav{
        padding-left: 100px!important;
        padding-right: 100px!important;
        background: #6665ee;
        font-family: 'Poppins', sans-serif;
    } 
    nav a.navbar-brand{
        color: #fff;
        font-size: 30px!important;
        font-weight: 500;
    }
    button a{
        color: #6665ee;
        font-weight: 500;
    }
    button a:hover{
        text-decoration: none;
    }
    h1{
        margin-top: 50px;
        text-align: center;
        font-size: 50px;
        font-weight: 600;
    }
    .table {
        margin-top: 50px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
    }
    </style>
</head>
<body>
    <nav class="navbar">
    <a class="navbar-brand" href="#">Logo</a>
    <button type="button" class="btn btn-light"><a href="logout-user.php">Logout</a></button>
    </nav>
    <h1>Welcome <?php echo $fetch_info['name'] ?></h1>
    <?php

    $fetch_doc = mysqli_fetch_assoc($doctor_sql);
    $docid = $fetch_doc['doc_id'];
    if($docid) {
        // Query to select all rows from the table
        $query = "SELECT patient_table.patient_id, patient_table.name, patient_table.email FROM patient_table INNER JOIN doctor_table ON doctor_table.doc_id = patient_table.doc_id WHERE doctor_table.doc_id = '$docid';";
        $result = mysqli_query($con, $query);

        // Check if there are any rows returned
        if (mysqli_num_rows($result) > 0) {
        // Output table header
        echo "<table class=\"table table-bordered table-striped\">";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th></tr>";

            // Output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>" . $row['patient_id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                // Add more columns as needed
                echo "</tr>";
            }

            // Close table
            echo "</table>";
        } else {
        echo "No records found";
        }
    }
    else {
        echo "<h6>Patient Info</h6>";
    }
    ?>

</body>
</html>
