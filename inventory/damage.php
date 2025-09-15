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
    <link rel="stylesheet" href="node_modules/ionicons/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS to set a fixed width and height for the images */
        .product-image {
            width: 80px; /* Adjust the width as needed */
            height: 80px; /* Adjust the height as needed */
        }
    </style>
    <title>Damage</title>
</head>
<body>

<?php
include_once('navbar/navbar.php');
?>

<div class="container-fluid">
    <div class="container">
<?php
// Include the database connection file
require_once('connections/pdo.php');

// Initialize error and success messages
$error_message = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedProducts = isset($_POST['selectedProducts']) ? $_POST['selectedProducts'] : [];
    $transactionDescription = isset($_POST['transactionDescription']) ? $_POST['transactionDescription'] : "";

    // Store selected products and transaction description in session for processing
    $_SESSION['selectedProducts'] = $selectedProducts;
    $_SESSION['transactionDescription'] = $transactionDescription;

    // Redirect to a page for entering serial numbers
    header("Location: damage_details.php");
    exit();
}

// Fetch and display products
try {
    $stmt = $conn->prepare("SELECT product_name, product_image, product_type, product_desc, product_Id FROM product_tbl");
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="container-fluid">
    <div class="container">
        <h1>Select Item to Tagged as Damage</h1>
        <form method="POST">
            <div class="container">
                <table class="table text-center align-middle">
                <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Type</th>
                    <th>Product Description</th>
                    <th>Select Item</th>
                </tr>
                </thead>
                    <tbody>
                        <?php
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            // Each product row in the table
                            ?>
                            <tr>
                                <td>
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>"
                                        alt="Product Image" class="product-image">
                                </td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['product_type']; ?></td>
                                <td><?php echo $row['product_desc']; ?></td>
                                <td>
                                    <label>
                                        <input type="checkbox" name="selectedProducts[]"
                                            value="<?php echo $row['product_Id']; ?>">
                                        Select
                                    </label>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <div class="form-group">
                    <label for="transactionDescription">Transaction Description:</label>
                    <input type="text" name="transactionDescription" class="form-control" id="transactionDescription" required>
                </div>

                <div class="container">
                <div class="row">
            <!-- Content on the left (col-md-8) -->
                     <div class="col-md-10">

                    </div>
            <!-- Div on the right (col-md-4) -->
                    <div class="col-md-2">
                    <button class="next" type="submit">
                        Next
                    <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                    <path clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" fill-rule="evenodd"></path>
                     </svg>
                    </button>                            
                    </div>
                </div>
            </div>
            </div>
        </form>

    </div>
</div>

<?php
// Close the database connection
$conn = null;
?>

<script src="css/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

</body>
</html>
