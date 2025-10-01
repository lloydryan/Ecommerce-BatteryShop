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
    session_destroy();
    header("Location: home.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart - BatteryShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="active_link.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(102, 126, 234, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(118, 75, 162, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/theOne.png') center/cover;
            opacity: 0.1;
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 16px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }

        .cart-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .cart-content {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 40px;
            align-items: start;
        }

        .cart-items {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 25px 0;
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item:hover {
            background: #f9fafb;
            border-radius: 12px;
            padding: 25px 15px;
            margin: 0 -15px;
        }

        .item-image {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            object-fit: cover;
            margin-right: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .item-details {
            flex: 1;
            margin-right: 20px;
        }

        .item-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .item-type {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .item-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: #667eea;
        }

        .item-controls {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: flex-end;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f9fafb;
            border-radius: 8px;
            padding: 8px 12px;
        }

        .quantity-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
        }

        .quantity-input {
            width: 60px;
            padding: 6px 8px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .item-actions {
            display: flex;
            gap: 8px;
        }

        .btn-update {
            background: linear-gradient(135deg, #10b981, #059669);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-remove {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        .cart-summary {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: sticky;
            top: 100px;
        }

        .summary-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 24px;
            text-align: center;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .summary-row:last-child {
            border-bottom: none;
            font-weight: 800;
            font-size: 1.2rem;
            color: #1a1a1a;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: 16px;
            border-radius: 12px;
            margin-top: 16px;
        }

        .summary-label {
            color: #6b7280;
            font-weight: 500;
        }

        .summary-value {
            color: #1a1a1a;
            font-weight: 600;
        }

        .checkout-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 16px;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .checkout-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .checkout-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .empty-cart {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .empty-cart-icon {
            font-size: 4rem;
            color: #9ca3af;
            margin-bottom: 24px;
        }

        .empty-cart h3 {
            font-size: 1.8rem;
            color: #374151;
            margin-bottom: 12px;
        }

        .empty-cart p {
            color: #6b7280;
            font-size: 1.1rem;
            margin-bottom: 32px;
        }

        .continue-shopping {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .continue-shopping:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .cart-content {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .cart-summary {
                position: static;
            }

            .cart-item {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .item-image {
                width: 100%;
                height: 200px;
                margin-right: 0;
                margin-bottom: 16px;
            }

            .item-details {
                margin-right: 0;
                margin-bottom: 16px;
            }

            .item-controls {
                align-items: center;
            }

            .item-actions {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .cart-container {
                padding: 20px 10px;
            }

            .cart-title {
                font-size: 2rem;
            }

            .cart-items,
            .cart-summary {
                padding: 20px;
            }
        }

        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <?php include_once('header-footer/header.php') ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title" data-aos="fade-up">Shopping Cart</h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Review your items and proceed to checkout
            </p>
        </div>
    </section>

    <div class="cart-container">

        <?php if (empty($cart)): ?>
            <div class="empty-cart" data-aos="fade-up">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added any items to your cart yet.</p>
                <a href="product.php" class="continue-shopping">
                    <i class="fas fa-shopping-bag"></i>
                    Continue Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="cart-content">
                <div class="cart-items" data-aos="fade-right">
                    <?php foreach ($cart as $key => $item): ?>
                        <div class="cart-item fade-in">
                            <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" class="item-image">
                            
                            <div class="item-details">
                                <h3 class="item-name"><?php echo $item['name']; ?></h3>
                                <p class="item-type"><?php echo $item['type']; ?></p>
                                <div class="item-price">₱<?php echo number_format($item['price'], 2); ?></div>
                            </div>

                            <div class="item-controls">
                                <form method="POST" class="quantity-control">
                                    <label class="quantity-label">Qty:</label>
                                    <input type="number" name="iqty" min="1" max="50" value="<?php echo $item['qty']; ?>" class="quantity-input">
                                    <input type="hidden" name="item" value="<?php echo $item['name']; ?>">
                                    <button type="submit" name="itemUpdate" class="btn-update">
                                        <i class="fas fa-sync-alt"></i>
                                        Update
                                    </button>
                                </form>

                                <div class="item-actions">
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="item" value="<?php echo $item['name']; ?>">
                                        <button type="submit" name="itemRemove" class="btn-remove" onclick="return confirm('Are you sure you want to remove this item?')">
                                            <i class="fas fa-trash"></i>
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary" data-aos="fade-left">
                    <h3 class="summary-title">Order Summary</h3>
                    
                    <div class="summary-row">
                        <span class="summary-label">Subtotal:</span>
                        <span class="summary-value">₱<?php echo number_format($total_price, 2); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Tax (6%):</span>
                        <span class="summary-value">₱6.00</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Shipping:</span>
                        <span class="summary-value">₱150.00</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Total:</span>
                        <span class="summary-value">₱<?php echo number_format($total_price + 6 + 150, 2); ?></span>
                    </div>

                    <form method="POST">
                        <input type="hidden" name="checkout" value="1">
                        <button type="submit" class="checkout-btn">
                            <i class="fas fa-credit-card"></i>
                            Proceed to Checkout
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php include_once('header-footer/footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });

        // Show success message
        <?php if(isset($_GET['success'])) { ?>
            Swal.fire({
                title: 'Order Placed Successfully!',
                text: 'Your order has been processed and will be delivered soon.',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 5000,
                timerProgressBar: true
            }).then(() => {
                window.location.href = "orders.php";
            });
        <?php } ?>

        // Show error message
        <?php if(isset($_GET['error'])) { ?>
            Swal.fire({
                title: 'Login Required!',
                text: 'You need to login first to proceed with checkout.',
                icon: 'warning',
                confirmButtonText: 'Login Now'
            }).then(() => {
                window.location.href = "login.php";
            });
        <?php } ?>

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';

        // Add hover effects to cart items
        document.addEventListener('DOMContentLoaded', function() {
            const cartItems = document.querySelectorAll('.cart-item');
            cartItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add quantity input validation
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 1) this.value = 1;
                    if (this.value > 50) this.value = 50;
                });
            });

            // Add loading animation to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.querySelector('button[name="itemUpdate"]') || this.querySelector('button[name="itemRemove"]')) {
                        const btn = this.querySelector('button[type="submit"]');
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        btn.disabled = true;
                        
                        // Re-enable after 2 seconds
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, 2000);
                    }
                });
            });
        });

        // Navbar scroll effect
        const navbar = document.querySelector('.navbar');
        window.onscroll = () => {
            if (window.scrollY > 10) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        };
    </script>
</body>
</html>