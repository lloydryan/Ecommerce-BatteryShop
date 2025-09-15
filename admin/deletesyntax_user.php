<?php
include_once('connections/connection.php');

if (isset($_POST['deleteid'])) {
    $userId = $_POST['deleteid'];

    // Perform the deletion from your database
    $query = "DELETE FROM users_tbl WHERE ID = $userId";

    if (mysqli_query($conn, $query)) {
        echo 'success';
    } else {
        echo 'error';
    }
} else { 
    echo 'error'; // Handle the case when deleteid is not set
}
