<?php
include_once ('connections/connection.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="v2.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script defer src="active_link.js"></script>
</head>
<body>
<div class="back-button">
    <a href="product.php" class="btn btn-primary">Back to Product Page</a>
</div>
<div class="container">
    <h1>Select Product to Request for Change</h1>
</div>
<table class="table">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Name</th>
                    <th>Product Type</th>
                    <th>Product Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once('connections/pdo.php');
                try {
                    $stmt = $conn->prepare("SELECT product_name, product_image, product_type,product_desc, product_Id FROM product_tbl");
                    $stmt->execute();
                    foreach ($stmt->fetchAll() as $row) {
                        ?>
                        <tr>
                            <td>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>"
                                    alt="Product Image" class="product-image" class="product-image" width="100">
                            </td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['product_type']; ?></td>
                            <td><?php echo $row['product_desc']; ?></td>
                            <td>
                                <a href='add_request.php?product_Id=<?php echo $row['product_Id']; ?>'
                                   class="btn btn-danger"><i class='bi bi-plus-circle'></i> Request for exchange </a>
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
    </div>
</div>

<?php
// Close the database connection
$conn = null;
?>

    
</body>
</html>