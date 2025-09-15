<?php
include_once('connections/connection.php');
require_once('connections/pdo.php');

if (isset($_POST['product_name']) && !empty($_POST['product_name'])) {

  try {
    $stmt = $conn->prepare("INSERT INTO product_tbl (product_name,product_type, product_desc, price, product_image) VALUES (:name,:type, :description, :price, :image)");
    $stmt->bindParam(':name', $_POST['product_name']);
    $stmt->bindParam(':type', $_POST['product_type']);
    $stmt->bindParam(':description', $_POST['desc']);
    $stmt->bindParam(':price', $_POST['price'], PDO::PARAM_INT);

    if (isset($_FILES['product_img']) && $_FILES['product_img']['error'] == 0) {
        $file_name = $_FILES['product_img']['name'];
        $file_size = $_FILES['product_img']['size'];
        $file_tmp = $_FILES['product_img']['tmp_name'];
        $file_type = $_FILES['product_img']['type'];

        $fp = fopen($file_tmp, 'r');
        $content = fread($fp, filesize($file_tmp));
        fclose($fp);

        $stmt->bindParam(':image', $content, PDO::PARAM_LOB);
    } else {
        // Set default image path here
        $default_image_path = 'image/default.jpg';
        $default_image_content = file_get_contents($default_image_path);
        $stmt->bindParam(':image', $default_image_content, PDO::PARAM_LOB);
    }
    
    $stmt->execute();
    header('Location: manage-product.php');
  } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
  }
  $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Add product</title>

    <link rel="stylesheet" href="css/update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">


</head>
<body class="b1" >



  <div class="container">
    <div class="admin-product-form-container centered">

      
      <form action=" " method="post" enctype="multipart/form-data"style="border:1px solid #ccc">
      <div class="row">
        <div class="col-md-12">
            <a style="font-size:15px" href="manage-product.php" >
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <br>
      <h2 style="text-align: center; font-weight: bold">Add New Product</h2><br>
        <div class="mb-3">
          <label for="product_name" class="form-label">Product Name</label>
          <input type="text" id="product_name" name="product_name"  class="box" required>
        </div>
        <div class="mb-3">
          <label for="product_type" class="form-label">Product Type</label>
          <input type="text" id="product_type" name="product_type"  class="box" required>
        </div>
        <div class="mb-3">
          <label for="desc" class="form-label">Description</label>
          <textarea id="desc" name="desc"  class="box" required></textarea>
        </div>
        <div class="mb-3">
          <label for="price" class="form-label">Price</label>
          <input type="number" id="price" name="price" min="1" class="box"  required>
        </div>
       
        <div class="mb-3">
          <label for="product_img" class="form-label">Image</label>
          <input type="file" id="product_img" name="product_img"  class="box" >
        </div>
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Add Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>

