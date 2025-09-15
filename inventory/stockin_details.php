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
            width: 100px; /* Adjust the width as needed */
            height: 100px; /* Adjust the height as needed */
        }
    </style>
    <title>Enter Serial Numbers</title>
</head>
<body>

<?php
include_once('navbar/navbar.php');
?>

<div class="container-fluid">
    <div class="container">
        <h1>Enter Serial Numbers</h1>
        <form method="POST">
            <div class="container">
                <table class="table">
                <thead>
                <tr>                  
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Type</th>
                    <th>Product Description</th>
                    <th>Enter Quantity</th>
                 </tr>
                 </thead>
                    <tbody>
                        <?php
                        require_once('connections/pdo.php');
                        // Retrieve selected products and transaction description from session

                        $selectedProducts = $_SESSION['selectedProducts'];

                        // Fetch and display selected products
                        try {
                            $stmt = $conn->prepare("SELECT product_name, product_image, product_type, product_desc, product_Id FROM product_tbl WHERE product_Id IN (".implode(",", $selectedProducts).")");
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // Each selected product row in the table
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
                                        <input type="number" name="quantities[<?php echo $row['product_Id']; ?>]" class="form-control" required>
                                    </td>
                                </tr>
                                <?php
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </tbody>
                </table>

                <div class="container">
                <div class="row">
            <!-- Content on the left (col-md-8) -->
                     <div class="col-md-10">

                    </div>
            <!-- Div on the right (col-md-4) -->
                    <div class="col-md-2">
                    <button type="submit" class="btn-PO ">
                        <div class="svg-wrapper-1">
                         <div class="svg-wrapper">
                            <svg height="24" width="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M1.946 9.315c-.522-.174-.527-.455.01-.634l19.087-6.362c.529-.176.832.12.684.638l-5.454 19.086c-.15.529-.455.547-.679.045L12 14l6-8-8 6-8.054-2.685z" fill="currentColor"></path>
                            </svg>
                         </div>
                        </div>
                            <span>Submit</span>
                     </button>                         
                    </div>
                </div>
                </div>







            </div>
        </form>

    </div>
</div>

<?php
// Include the database connection file
require_once('connections/pdo.php');

// Initialize error and success messages
$error_message = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve quantities and transaction description
    $quantities = $_POST['quantities'];

    try {
        $conn->beginTransaction();

        // Create a new transaction entry
        $stmtTransaction = $conn->prepare("INSERT INTO stockin_tbl (transaction_date, employee_ID) VALUES (NOW(), :employee_ID)");
        $stmtTransaction->bindParam(':employee_ID', $userID, PDO::PARAM_INT); // Insert the userID
        $stmtTransaction->execute();

        // Get the auto-generated transaction ID
        $transactionId = $conn->lastInsertId();

        // Insert stockin entries for selected products with quantities
        $stmtStockInEntry = $conn->prepare("INSERT INTO stockin_entries (transaction_id, product_Id, quantity, timestamp) VALUES (:transaction_id, :product_Id, :quantity, NOW())");

        foreach ($quantities as $product_Id => $quantity) { // Use $quantities array
            $stmtStockInEntry->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
            $stmtStockInEntry->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);
            $stmtStockInEntry->bindParam(':quantity', $quantity, PDO::PARAM_INT);

            $stmtStockInEntry->execute();

            // Update product quantities
            $stmtDeductQuantity = $conn->prepare("UPDATE product_tbl SET available_item = available_item + :quantity WHERE product_Id = :product_Id");
            $stmtDeductQuantity->bindParam(':quantity', $quantity, PDO::PARAM_INT); // Use $quantity
            $stmtDeductQuantity->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);
            $stmtDeductQuantity->execute();
        }

        $conn->commit();
        $successMessage = "Stock In successfully recorded. Transaction ID: $transactionId";

    } catch (PDOException $e) {
        $conn->rollBack();
        $error_message = "Error: " . $e->getMessage();
    }

    // Clear session data
    unset($_SESSION['selectedProducts']);
    unset($_SESSION['transactionDescription']);
}

?>


<script src="css/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Include SweetAlert2 code for success message and redirection -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Check if a success message exists
    <?php if (!empty($successMessage)) { ?>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo $successMessage; ?>',
            icon: 'success',
        }).then(function() {
            // Redirect to damage.php
            window.location.href = 'stockin.php';
        });
    <?php } ?>
</script>
</body>
</html>
