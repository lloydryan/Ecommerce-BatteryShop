<?php

session_start();

// Check if the user is logged in and the user's ID is in the session
if (isset($_SESSION['ID'])) {
    $user_Id = $_SESSION['ID'];
} else {
    // Redirect or handle the case where the user is not logged in
    // You might want to redirect the user to a login page
    header("Location: login.php");
    exit();
}

// Check if the product_Id is set in the URL
if (isset($_GET['product_Id'])) {
    $product_Id = $_GET['product_Id'];

    // Include the database connection file
    require_once('connections/pdo.php');

    // Fetch the product details
    try {
        $stmt = $conn->prepare("SELECT product_name, product_image, product_type FROM product_tbl WHERE product_Id = :product_Id");
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
            $exchangeSerialNumber = $_POST['exchangeSerialNumber'];

            // Ensure that the serial number is not empty
            if (empty($exchangeSerialNumber)) {
                $error_message = "Please enter a serial number.";
            } else {
                // Insert a new record in the exchange_request table with default pending status
                $stmtInsertExchange = $conn->prepare("INSERT INTO exchange_request (product_Id, item_serialNO, status, user_Id) VALUES (:product_Id, :serial_number, 'PENDING', :user_Id)");
                $stmtInsertExchange->bindParam(':product_Id', $product_Id, PDO::PARAM_INT);
                $stmtInsertExchange->bindParam(':serial_number', $exchangeSerialNumber, PDO::PARAM_STR);
                $stmtInsertExchange->bindParam(':user_Id', $user_Id, PDO::PARAM_INT);


                if ($stmtInsertExchange->execute()) {
                    // Serial number added to exchange_request table successfully
                    $successMessage = "Exchange request successfully recorded.";

                } else {
                    $error_message = "Error inserting exchange request record.";
                }
            }
        } catch (PDOException $e) {
            // Handle any database errors
            echo "Error: " . $e->getMessage();
        }
    }

    // Display the form to request an exchange with product details
    ?>
  <!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Exchange Request</title>
      <link rel="stylesheet" href="style.css">
      <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
      <script defer src="active_link.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
      <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css"/>

  </head>
    <body>
     <?php include_once('header-footer/header.php') ?>
<div class="back-button">
    <a href="request.php" class="btn btn-primary">Back to Request Page</a>
</div>


    <div class="container-fluid">
        <div class="container">
            <h1>Request Exchange</h1>
            <?php
            if (isset($successMessage)) {
                echo '<div id="successMessage" style="display: none;">' . $successMessage . '</div>';
            }

            if (isset($errorMessage)) {
                echo '<div id="errorMessage" style="display: none;">' . $errorMessage . '</div>';
            }
            ?>
            <div class="card">
            <input type="hidden" name="user_Id" value="<?php echo $user_Id; ?>">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['product_image']); ?>"
                     class="card-img-top" alt="Product Image" style="max-width: 300px;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $product['product_name']; ?></h5>
                    <p><strong>Product Type:</strong> <?php echo $product['product_type']; ?></p>

                    <form method="POST">
                        <div class="form-group">
                            <label for="exchangeSerialNumber">Serial Number for Exchange:</label>
                            <input type="text" name="exchangeSerialNumber" class="form-control" id="exchangeSerialNumber" required>
                        </div>
                        <input type="hidden" name="product_Id" value="<?php echo $product_Id; ?>">
                        <button type="submit" class="btn btn-primary">Request Exchange</button>
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
                        window.location.href = "product.php";
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>

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
?>
