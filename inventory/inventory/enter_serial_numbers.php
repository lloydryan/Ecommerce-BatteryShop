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
                    <th>Enter Serial NO.</th>
                    <th>Enter Quantity</th>
                 </tr>
                 </thead>
                    <tbody>
                        <?php
                        require_once('connections/pdo.php');
                        // Retrieve selected products and transaction description from session
                        session_start();
                        $selectedProducts = $_SESSION['selectedProducts'];
                        $transactionDescription = $_SESSION['transactionDescription'];

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
                                        <input type="text" name="serialNumbers[<?php echo $row['product_Id']; ?>]" class="form-control" required>
                                    </td>
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

                <input type="hidden" name="transactionDescription" value="<?php echo $transactionDescription; ?>">
                <button type="submit" class="btn btn-danger">Add Damage</button>
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
    // Retrieve serial numbers, quantities, and transaction description
    $serialNumbers = $_POST['serialNumbers'];
    $quantities = $_POST['quantities'];
    $transactionDescription = $_POST['transactionDescription'];

    try {
        $conn->beginTransaction();

        // Create a new transaction entry
        $stmtTransaction = $conn->prepare("INSERT INTO damage_tbl (transaction_date, description) VALUES (NOW(), :description)");
        $stmtTransaction->bindParam(':description', $transactionDescription, PDO::PARAM_STR);
        $stmtTransaction->execute();

        // Get the auto-generated transaction ID
        $transactionId = $conn->lastInsertId();

        // Insert damage entries for selected products with serial numbers and quantities
        $stmtDamageEntry = $conn->prepare("INSERT INTO damage_entries (transaction_id, product_Id, serial_number, quantity, timestamp) VALUES (:transaction_id, :product_Id, :serial_number, :quantity, NOW())");

        foreach ($serialNumbers as $product_Id => $serial_number) {
            $stmtDamageEntry->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
            $stmtDamageEntry->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);
            $stmtDamageEntry->bindParam(':serial_number', $serial_number, PDO::PARAM_STR);
            $stmtDamageEntry->bindParam(':quantity', $quantities[$product_Id], PDO::PARAM_INT);

            $stmtDamageEntry->execute();
        }
        $stmtDeductQuantity = $conn->prepare("UPDATE product_tbl SET available_item = available_item - :quantity WHERE product_Id = :product_Id");
        $stmtDeductQuantity->bindParam(':quantity', $quantities[$product_Id], PDO::PARAM_INT);
        $stmtDeductQuantity->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);
        $stmtDeductQuantity->execute();

        $conn->commit();
        $successMessage = "Damage entries successfully recorded. Transaction ID: $transactionId";

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
            window.location.href = 'damage.php';
        });
    <?php } ?>
</script>
</body>
</html>
