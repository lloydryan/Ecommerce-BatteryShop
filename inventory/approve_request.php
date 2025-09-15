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
<html>
<head>
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<?php
// Include your database connection code (e.g., PDO)
require_once('connections/pdo.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the request ID from the AJAX request
    $requestId = $_POST["requestId"];

    try {
        // Start a database transaction
        $conn->beginTransaction();

        // Insert the approved request into the return table
        $insertReturnSql = "INSERT INTO return_table (request_Id, item_serialNO) SELECT request_Id, item_serialNO FROM exchange_request WHERE request_Id = :requestId";
        $insertReturnStmt = $conn->prepare($insertReturnSql);
        $insertReturnStmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);
        $insertReturnStmt->execute();

        // Deduct available_item from the product table
        $deductItemSql = "UPDATE product_tbl SET available_item = available_item - 1 WHERE product_Id = (SELECT product_Id FROM exchange_request WHERE request_Id = :requestId)";
        $deductItemStmt = $conn->prepare($deductItemSql);
        $deductItemStmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);
        $deductItemStmt->execute();

        // Update the status to 'APPROVED' in the exchange_request table
        $updateStatusSql = "UPDATE exchange_request SET status = 'APPROVED' WHERE request_Id = :requestId";
        $updateStatusStmt = $conn->prepare($updateStatusSql);
        $updateStatusStmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);
        $updateStatusStmt->execute();

        // Commit the transaction
        $conn->commit();

        echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Status updated to APPROVED for Request ID: $requestId',
            icon: 'success',
            confirmButtonText: 'OKAY'
        }).then(function() {
            window.location.href = 'exchange.php'; // Redirect to exchange.php
        });
    </script>";
    } catch (PDOException $e) {
        // Rollback the transaction on error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle non-POST requests as needed
    echo "Invalid request method.";
}
?>
    </body>
</html>

