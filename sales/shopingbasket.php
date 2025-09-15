<?php
include_once('connections/connection.php');
session_start();

// Get the shopping cart from the session
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

// Handle the "Update" button action
if (isset($_POST['itemUpdate'])) {
        $newQuantity = intval($_POST['iqty']);
        $itemName = $_POST['item'];

        foreach ($_SESSION['cart'] as &$item) {
            if ($item['name'] === $itemName) {
                $item['qty'] = $newQuantity;
                break;
            }
        }
        header("Location: shopingbasket.php");
        exit;
    }
if (isset($_POST['checkout'])) {

    $user_id = isset($_SESSION['ID']) ? $_SESSION['ID'] : 0;
    if ($user_id == 0) {
        header('Location: shopingbasket.php?error=true');
        exit;
    }

    // Calculate the total price after initializing it
    $total_price = 0;
    foreach ($cart as $item) {
        $total_price += $item['price'] * $item['qty'];
    }

    // Insert data into order_tbl without specifying the transaction_id field
    $order_total = $total_price + 6 + 150; // Calculate the total order amount

    $stmt = mysqli_prepare($conn, "INSERT INTO `order_tbl`(`User_Id`, `order_total`) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, 'id', $user_id, $order_total);

    // Get the current date and time in the correct format
    $order_date = date('Y-m-d H:i:s');
    mysqli_stmt_execute($stmt);
    
    // Retrieve the auto-incremented transaction ID
    $transaction_id = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    // Insert each item in the cart into order_detail table with the transaction ID
    foreach ($_SESSION['cart'] as $item) {
        $product_id = $item['id'];
        $product_name = $item['name'];
        $product_price = $item['price'];
        $qty = $item['qty'];
        $total_price = $product_price * $qty;

        $stmt = mysqli_prepare($conn, "INSERT INTO `order_detail`(`transaction_id`, `user_id_fk`, `product_id_fk`, `product_name`, `product_price`, `qty`, `total_price`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iiissdd', $transaction_id, $user_id, $product_id, $product_name, $product_price, $qty, $total_price);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // Clear the shopping cart
    unset($_SESSION['cart']);

    // Redirect to the success page with the transaction ID
    header('Location: shopingbasket.php?success=true');
    exit();
}

// Remove items from the cart using unset()
if (isset($_POST['itemRemove'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['name'] === $_POST['item']) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            header("Location: shopingbasket.php");
            break; // Exit the loop to avoid multiple removes
        }
    }
}

// Calculate the total price
$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['price'] * $item['qty'];
}

if (isset($_GET['logout'])) {
    $_SESSION = array();
    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: home.php");
    exit;
}
?>


<!doctype html>
<html lang="en">
<head>
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Shopping Basket</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script defer src="active_link.js"></script>
</head>
<body class="image-container2">
  <?php
    include_once('header-footer/header.php');
  ?>




<div class="wrapper">
        <h1>Shopping Cart</h1>
        <div class="project">
            <div class="shop">
                <!-- Your PHP code for displaying cart items goes here -->
                <?php
// Display images in the HTML along with other product details
foreach ($cart as $key => $item) {
    ?>
    <div class="box">
        <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>">
        <div class="content">
            <h3><?php echo $item['name']; ?></h3>
            <h5>Type: <?php echo $item['type']; ?></h5>
            <h4>Price: ₱<?php echo $item['price']; ?></h4>
            <form method="POST">
                <p class="unit">Quantity: <input type="number" name="iqty" min="1" max="50" value="<?php echo $item['qty']; ?>"></p>
                <input type="hidden" name="item" value="<?php echo $item['name']; ?>">
                <input type="hidden" name="totalprice" value="<?php echo $total_price; ?>">
                <button type="submit" name="itemUpdate" class="btn1"><i class="fa fa-pencil"></i>Update</button>
                <button type="submit" name="itemRemove" class="btn2"><i aria-hidden="true" class="fa fa-trash"></i>Remove</button>
             </form>
        </div>
    </div>
    <?php
} ?>

            </div>
            <form method="POST">
            <div class="right-bar">
                <p><span>Subtotal:</span> <span>₱<?php echo $total_price; ?></span></p>
                <hr>
                <p><span>Tax (6%)</span> <span>₱6</span></p>
                <hr>
                <p><span>Shipping</span> <span>₱150</span></p>
                <hr>
                <p><span>Total:</span> <span>₱<?php echo $total_price + 6 + 150; ?></span></p>
                <input type="hidden" name="checkout" value="1">
                <button type="submit" class="checkout-button"><i class="fa fa-shopping-cart"></i>Checkout</button>

            </div>
            </form>
        </div>
    </div>
  <br><br><br><br>
  <?php
    include_once('header-footer/footer.php');
  ?>
  <br><br><br><br>
  <?php
    include_once('header-footer/footer.php');
  ?>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Show a success message when the item is added to the cart
  <?php if(isset($_GET['success'])) { ?>
    Swal.fire({
      title: 'Success!',
      text: 'Checked out Successful.',
      icon: 'success',
      button: "OK",
  }).then(function () {
      window.location.href = "shopingbasket.php";
  });
    
  <?php } ?>
  
  // Show an error message when the item is already in the cart
  <?php if(isset($_GET['error'])) { ?>
    Swal.fire({
      title: 'Warning!',
      text: 'You need to Login First!.',
      icon: 'warning',
      button: "OK",
  }).then(function () {
      window.location.href = "login.php";
  });
    
  <?php } ?>
</script>


<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
AOS.init();
</script>

<script>
 const navbar = document.querySelector('.navbar');

    navbar.classList.add('scrolled');

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
  <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #ffe3e2;
            font-family: montserrat;
        }
        .wrapper {
            max-width: 1000px;
            margin: 0 auto;
        }
        .wrapper h1 {
            padding: 20px 0;
            text-align: center;
            text-transform: uppercase;
        }
        .project {
            display: flex;
        }
        .shop {
            flex: 75%;
        }
        .checkout-button{
            border: none;
            position: absolute;
            border-radius: 0;
            padding: 10px 15px;
            border-radius: 6px;
            background-color: #8EE4B9;
        }
        .box {
            display: flex;
            width: 100%;
            height: 200px;
            overflow: hidden;
            margin-bottom: 30px;
            background: #fff;
            transition: all 0.6s ease;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            flex-wrap: nowrap;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }
        .box:hover {
            border: none;
            transform: scale(1.01);
        }
        .box img {
            width: 300px;
            height: 200px;
            object-fit: cover;
        }
        .content {
            padding: 20px;
            width: 100%;
            position: relative;
        }
        .content h4 {
            margin-bottom: 35px;
        }
        .btn1{
           position: absolute;
           border: none;
           bottom: 100px;
           right: 20px;
           padding: 10px 27px;
           background-color: #3a71a9;
           color: white;
           cursor: pointer;
           border-radius: 5px;
        }
        .btn2 {
            border: none;
            position: absolute;
            bottom: 40px;
            right: 20px;
            padding: 10px 25px;
            background-color: #3a71a9;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .btn2:hover {
            background-color: #76bfb6;
            color: #fff;
            font-weight: 600;
        }
        .unit input {
            width: 40px;
            padding: 5px;
            text-align: center;
        }
        .btn1 i {
            margin-right: 5px;
        }
        .btn2 i {
            margin-right: 5px;
        }
        .right-bar {
            flex: 25%;
            margin-left: 20px;
            padding: 20px;
            height: 400px;
            border-radius: 5px;
            background: #fff;
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }
        .right-bar hr {
            margin-bottom: 25px;
        }
        .right-bar p {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 20px;
        }
        .right-bar a {
            background-color: #76bfb6;
            color: #fff;
            text-decoration: none;
            display: block;
            text-align: center;
            height: 40px;
            line-height: 40px;
            font-weight: 900;
        }
        .right-bar i {
            margin-right: 15px;
        }
        .right-bar a:hover {
            background-color: #3972a7;
        }
        @media screen and (max-width: 700px) {
            .content h3 {
                margin-bottom: 15px;
            }
            .content h4 {
                margin-bottom: 20px;
            }
            .btn2 {
                display: none;
            }
            .box {
                height: 150px;
            }
            .box img {
                height: 150px;
                width: 200px;
            }
        }
        @media screen and (max-width: 900px) {
            .project {
                flex-direction: column;
            }
            .right-bar {
                margin-left: 0;
                margin-bottom: 20px;
            }
        }
        @media screen and (max-width: 1250px) {
            .wrapper {
                max-width: 95%;
            }
        }
    </style>
</body>
</html>