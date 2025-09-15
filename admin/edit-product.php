

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Edit product</title>

    <link rel="stylesheet" href="css/update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">


</head>
<body class="b1" >
   
   
   

   
<?php require_once('connections/pdo.php'); ?>
<?php
  // Get the ID from the URL
  if (isset($_GET['product_Id'])) {
    $product_Id = $_GET['product_Id'];
  } else {
    // handle the case where product_Id is not set
    exit('product_Id is not set');
  }

  $stmt = $conn->prepare("SELECT * FROM product_tbl WHERE product_Id = ?");
  $stmt->execute([$product_Id]);
  $row = $stmt->fetch();
?>
     <div class="container">
       <div class="admin-product-form-container centered">
 

      
      <form action="editsyntax_product.php?product_Id=<?php echo $product_Id; ?>" method="post" enctype="multipart/form-data" style="border:1px solid #ccc">
      <div class="row">
        <div class="col-md-12">
            <a style="font-size:15px" href="product-user.php" >
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
        <br>
      
        <h2 style="text-align: center; font-weight: bold">Edit Product</h2><br>
      <h3 style="font-size: 25px; font-family: Times New Roman;"><strong>Product Name:</strong> <?php echo $row['product_name'] ?></h3><br>
        
      <div class="d-flex align-items-center justify-content-center">
        <?php
           // output the image as data URI
            $img_data = base64_encode($row['product_image']);
            $img_type = 'image/jpeg'; // replace with the appropriate image type
        ?><img src="data:<?php echo $img_type; ?>;base64,<?php echo $img_data; ?>" width="200" height="200" style="border-radius:10px;">
        
      </div>
      <HR></HR>
        <div class="mb-3">
          <label for="product_name" class="form-label"> New Product Name</label>
          <input type="text" id="product_name" name="product_name" value="<?php echo $row['product_name']; ?>" class="box">
        </div>
        <div class="mb-3">
          <label for="product_type" class="form-label"> New Product Type</label>
          <input type="text" id="product_type" name="product_type" value="<?php echo $row['product_type']; ?>" class="box">
        </div>
        <div class="mb-3">
          <label for="product_desc" class="form-label"> New Description</label>
          <textarea id="product_desc" name="product_desc" class="box"><?php echo $row['product_desc']; ?></textarea>
        </div>
        <div class="mb-3">
          <label for="price" class="form-label"> New Price</label>
          <input type="number" id="price" name="price" min="1" value="<?php echo $row['price']; ?>" class="box">
        </div>
        <div class="mb-3">
          <label for="product_image" class="form-label"> New Image</label>
          <br>
          
          <input type="file" id="product_image" name="product_image" class="box"  >
 
        </div>  

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
  </body>
</html>
