<?php
include_once('connections/connection.php');
session_start();


if(isset($_GET['logout'])) {
  // Unset all session variables
  $_SESSION = array();
  
  // Destroy the session
  session_destroy();
  
  // Redirect to login page
  header("Location: home.php");
  exit;
}

// initialize the shopping cart if it is not set already
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array();

}
// Handle the "Add to Cart" button action
if (isset($_POST['addCart'])) {
    $product_id = $_POST['id'];
    $product_name = $_POST['name'];
    $type=$_POST['type'];
    $price=$_POST['price'];
    $qty=$_POST['qty'];

    // Check if the product already exists in the cart
    $item_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            // If the product already exists, increase the quantity
            $item['qty'] += $qty;
            $item_exists = true;
            break;
        }
    }

    if(!$item_exists){
    // Fetch the product image from the product_tbl for the newly added product
    $sql = "SELECT product_image FROM product_tbl WHERE product_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $product_image);

    // Assuming there is only one result, fetch the image and encode as base64
    if (mysqli_stmt_fetch($stmt)) {
        // Encode the image as base64 and store in the cart item
        $image_url = 'data:image/jpeg;base64,' . base64_encode($product_image);

        // Add the item to the shopping cart with the image URL
        $_SESSION['cart'][] = array(
            'id' => $product_id,
            'name' => $product_name,
            'type' => $type,
            'price' => $price,
            'qty' => $qty,
            'image_url' => $image_url
            // Add other item details as needed
        );
    }
    mysqli_stmt_close($stmt);
  }
    // Redirect back to the product page with a success message
    header('Location: product.php?success=true');
    exit;
}
?>

<!doctype html>
  <html lang="en">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Battery</title>
      <link rel="stylesheet" href="style.css">
      <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
      <script defer src="active_link.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.js"></script>
      <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.min.css"/>

  </head>
  <body style="
        background-image: url('assets/theOne.png'); /* Set the image URL */
        background-size: cover; /* Scale the image to cover the entire container */
        background-position: center; /* Center the image horizontally and vertically */
        background-repeat: no-repeat; /* Prevent the image from repeating */ ">



  <?php

  include_once('header-footer/header.php')

  ?>

    

        <br><br><br><br>

<section class="search search-expand-lg">
  <div class="container">
    <div class="row">
    <div class="col-8 flex-end">
      <form class="d-flex" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <input class="form-control me-2" type="search" placeholder="Search product name here" aria-label="Search" id="searchInput" name="searchInput" autocomplete="off" value="<?php echo isset($_POST['searchInput']) ? $_POST['searchInput'] : ''; ?>">
        
        <!-- Add the dropdown menu here -->
        <select class="form-select" name="productType" id="productType">
          <option value="">All Product Types</option>
          <?php
          // Fetch distinct product types from the database
          $stmt = $conn->prepare("SELECT DISTINCT product_type FROM product_tbl ORDER BY product_type");
          $stmt->execute();
          $productTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);

          // Create options for each product type
          foreach ($productTypes as $type) {
            echo "<option value=\"$type\">" . ucfirst($type) . "</option>";
          }
          ?>
        </select>
        
        <button class="btn" type="submit" id="search-button" name="search-button" style="background-color:rgb(255, 208, 0);"><b>Search</b> </button>
      </form>
    </div>
    </div>
  </div>
</section>

      <div class="container"> 
          <div class="row">
          
              <?php
              require_once('connections/pdo.php');

              $search_result_set = array(); // initialize an empty array to hold the search results

             // ...

              // Check if the form is submitted
              if (isset($_POST['search-button'])) {
                $term = $_POST['searchInput'];
                $productType = $_POST['productType'];

                // Construct the SQL query based on the selected product type
                $query = "SELECT * FROM product_tbl WHERE product_name LIKE :searchInput";
                $params = ['searchInput' => '%' . $term . '%'];

                if (!empty($productType)) {
                  $query .= " AND product_type = :productType";
                  $params['productType'] = $productType;
                }

                $query .= " ORDER BY product_name";

                $stmt = $conn->prepare($query);
                $stmt->execute($params);
                $search_result_set = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($search_result_set) == 0) {
                  echo '<h4>No results found for your search term "' . $term . '"</h4>';
                } else {
                  // display search results
                }
              } else {
                // Default query without any filters
                $stmt = $conn->prepare("SELECT * FROM product_tbl ORDER BY product_name");
                $stmt->execute();
                $search_result_set = $stmt->fetchAll(PDO::FETCH_ASSOC);
              }

              // ...

              ?>
          </div>
      </div>

  <br><br><br>

  <div class="container my-4">
    <div class="row g-4">
        <?php
        require_once('connections/pdo.php');
        $query = "SELECT * FROM product_tbl ORDER BY product_name";
        $params = [];

        if (isset($_POST['search-button'])) {
            $term = $_POST['searchInput'];
            $productType = $_POST['productType'];

            $query = "SELECT * FROM product_tbl WHERE product_name LIKE :searchInput";
            $params['searchInput'] = "%$term%";

            if (!empty($productType)) {
                $query .= " AND product_type = :productType";
                $params['productType'] = $productType;
            }
            $query .= " ORDER BY product_name";
        }

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $row) { ?>
            <div class="col-md-4 col-sm-6">
                <form action="" method="POST">
                    <div class="card product-card shadow-lg border-0 p-3 rounded">
                        <img src='data:image/jpeg;base64,<?= base64_encode($row['product_image']); ?>' class="card-img-top rounded" alt="Product Image" style="height: 250px; object-fit: cover;">
                        <div class="card-body text-center">
                            <h5 class="fw-bold text-dark"><?= $row['product_name']; ?></h5>
                            <h6 class="text-muted"><?= ucfirst($row['product_type']); ?></h6>

                            <?php
                            $availableItem = $row['available_item'];
                            $stockStatus = ($availableItem >= 21) ? '<span class="badge bg-success">In Stock</span>' :
                                (($availableItem > 0) ? '<span class="badge bg-warning text-dark">Low Stock</span>' :
                                    '<span class="badge bg-danger">Out of Stock</span>');
                            echo $stockStatus;
                            ?>

                            <p class="mt-2 fw-bold">Price: $<?= number_format($row['price'], 2); ?></p>
                            <input type="hidden" name="id" value="<?= $row['product_Id']; ?>">
                            <input type="hidden" name="name" value="<?= $row['product_name']; ?>">
                            <input type="hidden" name="type" value="<?= $row['product_type']; ?>">
                            <input type="hidden" name="price" value="<?= $row['price']; ?>">

                            <label for="qty">Qty:</label>
                            <input type="number" value="1" min="1" max="100" name="qty" class="form-control w-50 mx-auto">

                            <button type="submit" name="addCart" class="btn btn-warning mt-3 w-100 fw-bold" <?= ($availableItem > 0) ? '' : 'disabled'; ?>>
                                <i class="fas fa-shopping-cart"></i> <?= ($availableItem > 0) ? 'Add to Cart' : 'Out of Stock'; ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    <?php if(isset($_GET['success'])) { ?>
        Swal.fire({title: 'Added!', text: 'Item added to cart.', icon: 'success'}).then(() => window.location.href = "product.php");
    <?php } ?>
</script>

<script>
    function showOutOfStockAlert() {
        Swal.fire({
            icon: 'error',
            title: 'Out of Stock',
            text: 'This product is currently out of stock and cannot be added to the cart.',
        });
    }
</script>

  </div>
</div>


<?php

include_once('header-footer/footer.php')

?>



<!-- Add the following code to your HTML file -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Show a success message when the item is added to the cart
  <?php if(isset($_GET['success'])) { ?>
    Swal.fire({
      title: 'Success!',
      text: 'The item was added to the cart.',
      icon: 'success',
      button: "OK",
  }).then(function () {
      window.location.href = "product.php";
  });
    
  <?php } ?>
  
  // Show an error message when the item is already in the cart
  <?php if(isset($_GET['error'])) { ?>
    Swal.fire({
      title: 'Error!',
      text: 'The item is already in the cart.',
      icon: 'error',
      button: "OK",
  }).then(function () {
      window.location.href = "product.php";
  });
    
  <?php } ?>
</script>







<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
AOS.init();
</script>

<script>
 const navbar = document.querySelector('.navbar');

window.onscroll = () => {
  if (window.scrollY > 10) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
};
</script>
<script>
  const search = document.querySelector('.search');
  const searchHeight = search.offsetHeight;

  window.addEventListener('scroll', () => {
    if (window.pageYOffset > search.offsetTop + searchHeight - 70) {
      search.classList.add('fixed');
    } else {
      search.classList.remove('fixed');
    }
  });
</script>
<script>
  window.onload = function() {
  window.scrollTo(0, 0);
}


</script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    </body>
  </html>