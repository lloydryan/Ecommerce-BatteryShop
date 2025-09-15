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
    <!-- Your HTML content here -->

    <?php
    require_once('connections/pdo.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['requestId'])) {
            $requestId = $_POST['requestId'];

            // Update the status to 'DECLINE' in the database
            $sql = "UPDATE exchange_request SET status = 'DECLINE' WHERE request_Id = :requestId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':requestId', $requestId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Status updated successfully
                echo "<script>
                        Swal.fire({
                            title: 'Success!',
                            text: 'Status updated to DECLINE for Request ID: $requestId',
                            icon: 'success',
                            confirmButtonText: 'OKAY'
                        }).then(function() {
                            window.location.href = 'exchange.php'; // Redirect to exchange.php
                        });
                    </script>";
            } else {
                // Error updating status
                echo "Error updating status for Request ID: $requestId";
            }
        }
    }
    ?>
    </body>
</html>
