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
    <title>STOCK IN</title>
</head>
<body>

<?php
include_once('navbar/navbar.php');
?>

<div class="container-fluid">
    <div class="container">
        <?php
        session_start();
        // Include the database connection file
        require_once('connections/pdo.php');

        // Initialize error and success messages
        $error_message = "";
        $successMessage = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedProducts = isset($_POST['selectedProducts']) ? $_POST['selectedProducts'] : [];

            // Store selected products and transaction description in session for processing
            $_SESSION['selectedProducts'] = $selectedProducts;

            // Redirect to a page for entering serial numbers
            header("Location: stockin_details.php");
            exit();
        }

        // Fetch and display products
        try {
            $stmt = $conn->prepare("SELECT product_name, product_image, product_type, product_desc, product_Id, available_item FROM product_tbl");
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>

        <div class="container-fluid">
            <div class="container">
                <h1>Select Item for Stock In</h1>
                <form method="POST">
                    <div class="container">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Product Image</th>
                                <th>Product Name</th>
                                <th>Product Type</th>
                                <th>Product Description</th>
                                <th>Available Item</th>
                                <th>Select Item</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                // Each product row in the table
                                $availableItem = $row["available_item"];
                                $status = ($availableItem < 5) ? '<span class="text-danger"><b>LOW</b></span>' : '<span class="text-success"><b>High</b></span>';
                                ?>
                                <tr>
                                    <td>
                                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>"
                                             alt="Product Image" class="product-image">
                                    </td>
                                    <td><?php echo $row['product_name']; ?></td>
                                    <td><?php echo $row['product_type']; ?></td>
                                    <td><?php echo $row['product_desc']; ?></td>
                                    <td><?php echo $availableItem . " " . $status; ?></td>
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

                        <button type="submit" class="btn btn-danger">Next</button>
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
    </div>
</div>
</body>
</html>
