<?php require_once "controllerUserData.php"; ?>
<?php
require_once "doctor-data.php";
require_once "connection.php";

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
    <link rel="stylesheet" href="style.css">
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
    .patient-container {
        padding: 0 50px;
        margin-top: 50px;
    }
    .table {
        margin-top: 50px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
        background-color: white;
    }
    select {
        width: 100%;
    }
    label {
        margin-top: 25px;
    }
    .helper-pdf {
        color: red;
        opacity: 60%;
        font-size: 11px;
        margin-top: 2px;
        margin-bottom: 20px;
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
    if($fetch_doc) {
        $docid = $fetch_doc['doc_id'];
        // Query to select all rows from the table
        $query = "SELECT patient_table.patient_id, patient_table.name, patient_table.email FROM patient_table INNER JOIN doctor_table ON doctor_table.doc_id = patient_table.doc_id WHERE doctor_table.doc_id = '$docid';";
        $result = mysqli_query($con, $query);

        // Check if there are any rows returned
        if (mysqli_num_rows($result) > 0) {
        // Output table header
        echo "<table class=\"table table-bordered table-striped\">";
            echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Report</th></tr>";

            // Output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $reports = "SELECT * FROM file_upload WHERE patient_id=$row[patient_id] ";
                $reportresult  = mysqli_query($con, $reports);
                if(mysqli_num_rows($reportresult) > 0){
                    $fetch_info = mysqli_fetch_assoc($reportresult);

                    echo "<tr>";
                    echo "<td>" . $row['patient_id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo '<td><a href="uploads/' . $fetch_info['filename'] . '">Report</a></td>';
                    echo "</tr>";
                } else {
                    echo "<tr>";
                    echo "<td>" . $row['patient_id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo '<td style="color:lightcoral; text-decoration:underline;">Report</a></td>';
                    echo "</tr>";
                }
            }

            // Close table
            echo "</table>";
        } else {
            echo "No records found";
        }
    }
    else {
        ?>
        <div class="patient-container">
            <div class="form patient-form mx-auto">
                <form action="home.php" method="POST" autocomplete="on" onsubmit="return validateForm()"
                enctype="multipart/form-data">
                    <h2 class="text-center">Patient Form</h2>
                    <input type="hidden" name="user-email" value="<?php echo $_SESSION['email']; ?>">
                    <label for="select-doctor">Select doctor *</label>
                    <select class="form-select" aria-label="Default select example" name="select-doctor"
                            id="select-doctor">
                        <option selected disabled hidden value="">Doctor Name</option>
                        <?php
                        // Populate dropdown menu with fetched names
                        foreach ($names as $id => $name) {
                            echo "<option value=\"$id\">$name</option>";
                        }
                        ?>
                    </select>
                    <div class="mb-3">
                        <label for="upload-report" class="form-label">Your past report</label>
                        <input class="form-control" type="file" id="upload-report" name="upload-report"
                               accept="application/pdf">
                        <p class="helper-pdf">File size should be less than 16mb</p>
                    </div>

                    <div class="form-group">
                        <input class="form-control button" type="submit" name="patient-data-submit" value="Done">
                    </div>
                </form>
            </div>
<?php }
    ?>

        </div>


    <script>
        function validateForm() {
            var selectDoctor = document.getElementById("select-doctor");
            var selectedValue = selectDoctor.options[selectDoctor.selectedIndex].value;
            if (selectedValue === "") {
                alert("Please select a doctor");
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }
    </script>

</body>
</html>
