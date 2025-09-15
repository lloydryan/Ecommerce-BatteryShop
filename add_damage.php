<?php
// Check if the product_Id is set in the URL
if (isset($_GET['product_Id'])) {
    $product_Id = $_GET['product_Id'];

    // Include the database connection file
    require_once('connections/pdo.php');

    // Fetch the product details
    try {
        $stmt = $conn->prepare("SELECT product_name, product_image, available_item FROM product_tbl WHERE product_Id = :product_Id");
        $stmt->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            // Product not found, display an error or redirect
            echo '<p>Error: Product not found.</p>';
            exit();
        }
    } catch (PDOException $e) {
        // Handle any database errors
        echo "Error: " . $e->getMessage();
        exit();
    }

    // Handle the form submission here
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Get the serial number of the damaged item from the form
            $damagedSerialNumber = $_POST['damagedSerialNumber'];

            // Ensure that the serial number is not empty
            if (empty($damagedSerialNumber)) {
                $error_message = "Please enter a serial number.";
            } else {
                // Insert a new record in the damage table to track damaged items
                $stmtInsertDamage = $conn->prepare("INSERT INTO damage (product_Id, serial_number) VALUES (:product_Id, :serial_number)");
                $stmtInsertDamage->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);
                $stmtInsertDamage->bindParam(':serial_number', $damagedSerialNumber, PDO::PARAM_STR);

                if ($stmtInsertDamage->execute()) {
                    // Update the available_item in the product table
                    $stmtUpdateProduct = $conn->prepare("UPDATE product_tbl SET available_item = available_item - 1 WHERE product_Id = :product_Id");
                    $stmtUpdateProduct->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);

                    if ($stmtUpdateProduct->execute()) {
                        // Serial number and available_item updated successfully
                        $successMessage = "Damage successfully recorded.";
                     
                    } else {
                        $error_message = "Error updating product information.";
                    }
                } else {
                    $error_message = "Error inserting damage record.";
                }
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
        }
    }

    // Display the form to deduct damaged quantity with product details
    ?>
 <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Damage</title>
    </head>
    <body>

    <?php
    include_once('navbar/navbar.php');
    ?>

    <div class="container-fluid">
        <div class="container">
            <h1>Deduct Damaged Quantity</h1>
            <?php
            if (isset($successMessage)) {
                echo '<div id="successMessage" style="display: none;">' . $successMessage . '</div>';
            }

            if (isset($errorMessage)) {
                echo '<div id="errorMessage" style="display: none;">' . $errorMessage . '</div>';
            }
            ?>
            <div class="card">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['product_image']); ?>"
                     class="card-img-top" alt="Product Image" style="max-width: 300px;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['product_name']; ?></h5>

                    <form method="POST">
                        <div class="form-group">
                            <label for="damagedSerialNumber">Serial Number of Damaged Item:</label>
                            <input type="text" name="damagedSerialNumber" class="form-control" id="damagedSerialNumber" required>
                        </div>
                        <input type="hidden" name="product_Id" value="<?php echo $product_Id; ?>">
                        <button type="submit" class="btn btn-danger">Mark as Damaged</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="css/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (document.getElementById("successMessage")) {
                Swal.fire({
                    icon: "success",
                    title: document.getElementById("successMessage").textContent,
                    showConfirmButton: true,
                    confirmButtonText: "Okay"
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = "damage.php";
                    }
                });
            }

            if (document.getElementById("errorMessage")) {
                Swal.fire({
                    icon: "error",
                    title: document.getElementById("errorMessage").textContent,
                    showConfirmButton: true,
                    confirmButtonText: "Okay"
                });
            }
        });
    </script>
    </body>
    </html>
    <?php
} else {
    // Product_Id not set in the URL, display an error or redirect
    echo '<script>
            Swal.fire({
                icon: "error",
                title: "Product Not Found",
                text: "The product_Id is missing in the URL.",
                confirmButtonText: "OK"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "your_error_page.php";
                }
            });
        </script>';
}
lloyd
?>