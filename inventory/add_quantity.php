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
<?php
// Check if the product_Id is set in the URL
if (isset($_GET['product_Id'])) {
    $product_Id = $_GET['product_Id'];

    // Include the database connection file
    require_once('connections/pdo.php');

    // Fetch the product details
    try {
        $stmt = $conn->prepare("SELECT product_name, product_image,product_type FROM product_tbl WHERE product_Id = :product_Id");
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
            // Get the quantity to add from the form
            $quantityToAdd = $_POST['quantityToAdd'];

            // Update the product quantity in the database
            $stmt = $conn->prepare("UPDATE product_tbl SET available_item = available_item + :quantityToAdd WHERE product_Id = :product_Id");
            $stmt->bindParam(':quantityToAdd', $quantityToAdd, PDO::PARAM_INT);
            $stmt->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                // Quantity added successfully
                $successMessage = "Quantity Added Successfully";
            } else {
                // Handle the update error, display an error message, or log it
                $errorMessage = 'Error updating quantity.';
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
        }
    }

    // Display the form to add quantity with product name and image
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Responsive Sidebar</title>
    </head>
    <body>

    <?php
    include_once('navbar/navbar.php');
    ?>

    <div class="container-fluid">
        <div class="container">
            <h1>Add Quantity</h1>
            
            <?php
            if (isset($successMessage)) {
                echo '<div id="successMessage" style="display: none;">' . $successMessage . '</div>';
            }

            if (isset($errorMessage)) {
                echo '<div id="errorMessage" style="display: none;">' . $errorMessage . '</div>';
            }
            ?>

            <form method="POST">
                <div class="form-group">
                    <label for="productName">Product Name:</label>
                    <input type="text" name="productName" class="form-control" id="productName" value="<?php echo $product['product_name']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="producttype">Product Type:</label>
                    <input type="text" name="producttype" class="form-control" id="producttype" value="<?php echo $product['product_type']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="productImage">Product Image:</label>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($product['product_image']); ?>" alt="Product Image" width="200" height="200">
                </div>
                <div class="form-group">
                    <label for="quantityToAdd">Quantity to Add:</label>
                    <input type="number" name="quantityToAdd" class="form-control" id="quantityToAdd"  min="0" required>
                </div>
                <input type="hidden" name="product_Id" value="<?php echo $product_Id; ?>">
                <button type="submit" class="btn btn-primary">Add Quantity</button>
            </form>
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
                        window.location.href = "stockin.php";
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
    echo '<p>Error: Product not found.</p>';
}
?>
