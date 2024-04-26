<?php
require "connection.php";

// Fetch names and IDs from MySQL table
$doctors = "SELECT doc_id, name FROM doctor_table";
$result = mysqli_query($con, $doctors);

// Check if query executed successfully
if (mysqli_num_rows($result) > 0) {
    // Store fetched names and IDs in an array
    $names = array();
    while($row = mysqli_fetch_assoc($result)) {
        $names[$row["doc_id"]] = $row["name"];
    }
} else {
    echo "0 results";
}

