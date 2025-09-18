<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== true) {
    header("Location: index.php");
    exit();
}

// You can now access user information like:
$userID = $_SESSION['ID'];
$firstName = $_SESSION['FirstName'];
$lastName = $_SESSION['LastName'];
$username = $_SESSION['Username'];

// Handle sign-out
if (isset($_GET['Signout'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Exchange Requests</title>
</head>
<body>

<?php
include_once('navbar/navbar.php');
require_once('connections/pdo.php');

// Query to retrieve data from the exchange_request table with a status of 'pending'
$sql = "SELECT request_Id, product_Id, item_serialNO, status FROM exchange_request WHERE status = 'pending'";
$stmt = $conn->query($sql);

?>

<div class="container-fluid  flex-grow-1 overflow-auto px-4" >
<h1>List of Request to Exchange</h1>
<div class="container">
<div class="table-responsive-sm table-fixed-height ">

    <table class="table text-center align-middle table-bordered table-striped" >
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Product ID</th>
                <th>Item Serial Number</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Loop through the database results and display each row as a table row with a form
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['request_Id'] . "</td>";
                echo "<td>" . $row['product_Id'] . "</td>";
                echo "<td>" . $row['item_serialNO'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>";
                echo "<form action='approve_request.php' method='post'>";
                echo "<input type='hidden' name='requestId' value='" . $row['request_Id'] . "'>";
                echo "<button class='btn btn-success' type='submit'>Approve</button>";
                echo "</form>";
                echo "<form action='decline_request.php' method='post'>";
                echo "<input type='hidden' name='requestId' value='" . $row['request_Id'] . "'>";
                echo "<button class='btn btn-danger' type='submit'>Decline</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
        </table>
    </div>
    </div>
</div>
</div>

<script src="css/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

</body>
</html>
