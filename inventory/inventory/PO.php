<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Responsive Sidebar</title>
</head>
<body>

<?php
include_once('navbar/navbar.php');
?>

<div class="container-fluid">
    <div class="container">
        <!-- Purchase Order Form -->
        <form method="post">
            <h2>Purchase Order</h2>
            <div class="mb-3">
                <label for="supplier_Id">Supplier:</label>
                <select id="supplier_Id" name="supplier_Id" class="form-control" required>
                    <?php
                    // Include the database connection
                    include_once('connections/connection.php');

                    // Retrieve supplier data from the database
                    $sql = "SELECT supplier_Id, supplier_name FROM supplier_tbl";
                    $result = mysqli_query($conn, $sql);

                    // Populate the dropdown with supplier names
                    while ($row = mysqli_fetch_assoc($result)) {
                        $supplier_Id = $row['supplier_Id'];
                        $supplier_name = $row['supplier_name'];
                        echo "<option value='$supplier_Id'>$supplier_name</option>";
                    }
                    ?>
                </select>
            </div>



            <?php
// Retrieve product data from the database
$sql = "SELECT product_Id, product_name, product_type FROM product_tbl";
$result = mysqli_query($conn, $sql);

// Create an empty array to store product options
$productOptions = [];

// Check if there are any products retrieved from the database
if (mysqli_num_rows($result) > 0) {
    // Populate the productOptions array with product names and types
    while ($row = mysqli_fetch_assoc($result)) {
        $product_Id = $row['product_Id'];
        $product_name = $row['product_name'];
        $product_type = $row['product_type'];
        $productOptions[] = ["value" => $product_Id, "name" => "$product_name ($product_type)"];
    }
} else {
    // No products found in the database
    $productOptions[] = ["value" => "", "name" => "No products found"];
}
?>

<div id="product-container">
    <!-- Initial product dropdown and quantity input -->
    <div class="product-row mb-3">
        <div class="mb-3">
            <label for="product">Product:</label>
            <select name="product[]" class="form-control" required>
                <?php
                // Output product options from the $productOptions array
                foreach ($productOptions as $option) {
                    $value = $option["value"];
                    $name = $option["name"];
                    echo "<option value='$value'>$name</option>";
                }
                ?>
            </select>
        </div>
                    <div class="mb-3">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity[]" class="form-control" min="0" required>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary" id="addProduct">Add Product</button>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $supplier_Id = $_POST["supplier_Id"];
            $productIds = $_POST["product"];
            $quantities = $_POST["quantity"];

            // First, insert into the purchase_order table to get the purchaseOrder_id
            $sql = "INSERT INTO purchase_order (supplier_Id, order_date) 
                    VALUES ('$supplier_Id', NOW())";

            if (mysqli_query($conn, $sql)) {
                // Get the last inserted purchaseOrder_id
                $purchaseOrder_id = mysqli_insert_id($conn);

                // Now, insert into the purchaseorder_detail table for each product and quantity
                for ($i = 0; $i < count($productIds); $i++) {
                    $product_Id = $productIds[$i];
                    $quantity = $quantities[$i];

                    $sql = "INSERT INTO purchaseorder_details (purchaseOrder_id, product_Id, quantity) 
                            VALUES ('$purchaseOrder_id', '$product_Id', '$quantity')";

                    if (!mysqli_query($conn, $sql)) {
                        $errorMessage = "Error: " . mysqli_error($conn);
                        break; // Exit the loop on error
                    }
                }

                if (!isset($errorMessage)) {
                    $successMessage = "Order Successful";
                }
            } else {
                $errorMessage = "Error: " . mysqli_error($conn);
            }
        }
        ?>

        <?php
        if (isset($successMessage)) {
            echo "<script>
                Swal.fire({
                    title: 'Success!',
                    text: '$successMessage',
                    icon: 'success',
                    confirmButtonText: 'OKAY'
                }).then(function(result) {
                    if (result.isConfirmed) {
                        window.location.href = 'PO.php'; // Redirect to 'PO.php'
                    }
                });
            </script>";
        }

        if (isset($errorMessage)) {
            echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: '$errorMessage',
                    icon: 'error',
                    confirmButtonText: 'OKAY'
                });
            </script>";
        }
        ?>

    </div>
</div>

<!-- Include SweetAlert2 library and your existing code for scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="css/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const addButton = document.getElementById("addProduct");
    const productContainer = document.getElementById("product-container");

    addButton.addEventListener("click", function() {
        // Clone the last product row
        const lastProductRow = productContainer.querySelector(".product-row:last-child");
        const clonedProductRow = lastProductRow.cloneNode(true);

        // Clear the selected value in the cloned product dropdown
        const clonedSelect = clonedProductRow.querySelector("select");
        clonedSelect.selectedIndex = 0;

        // Clear the quantity input in the cloned row
        const clonedInput = clonedProductRow.querySelector("input");
        clonedInput.value = "";

        // Append the cloned row to the product container
        productContainer.appendChild(clonedProductRow);
    });
});
</script>

</body>
</html>

<?php
// Close the database connection at the end of your code
mysqli_close($conn);
?>
