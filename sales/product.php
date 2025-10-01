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
    <title>Products - BatteryShop</title>
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

        .search-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 30px;
            margin: -40px auto 40px;
            max-width: 1000px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 14px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .filter-select {
            min-width: 200px;
            padding: 14px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            background: #f9fafb;
            transition: all 0.3s ease;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 14px 30px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .search-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .products-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 60px;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .product-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-in-stock {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-low-stock {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .badge-out-of-stock {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .product-content {
            padding: 25px;
        }

        .product-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .product-type {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 20px;
        }

        .quantity-section {
            margin-bottom: 20px;
        }

        .quantity-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }

        .quantity-input {
            width: 80px;
            padding: 8px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .add-to-cart-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .add-to-cart-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .add-to-cart-btn:hover::before {
            left: 100%;
        }

        .add-to-cart-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .add-to-cart-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .add-to-cart-btn:disabled:hover {
            transform: none;
            box-shadow: none;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 40px 0;
        }

        .no-results-icon {
            font-size: 4rem;
            color: #9ca3af;
            margin-bottom: 20px;
        }

        .no-results h3 {
            font-size: 1.5rem;
            color: #374151;
            margin-bottom: 10px;
        }

        .no-results p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.2rem;
            }
            
            .search-section {
                margin: -20px 20px 30px;
                padding: 20px;
            }
            
            .search-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-input,
            .filter-select {
                min-width: auto;
            }
            
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }
        }

        @media (max-width: 480px) {
            .hero-section {
                padding: 80px 0 40px;
            }
            
            .hero-title {
                font-size: 1.8rem;
            }
            
            .search-section {
                margin: -10px 10px 20px;
                padding: 15px;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>
<body>



    <?php include_once('header-footer/header.php') ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title" data-aos="fade-up">Premium Battery Collection</h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="200">
                Discover our wide range of high-quality batteries for all your devices
            </p>
        </div>
    </section>

    <!-- Search Section -->
    <div class="search-section slide-up">
        <form class="search-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input 
                class="search-input" 
                type="search" 
                placeholder="Search for batteries..." 
                name="searchInput" 
                autocomplete="off" 
                value="<?php echo isset($_POST['searchInput']) ? htmlspecialchars($_POST['searchInput']) : ''; ?>"
            >
            
            <select class="filter-select" name="productType" id="productType">
                <option value="">All Categories</option>
          <?php
                require_once('connections/pdo.php');
          $stmt = $conn->prepare("SELECT DISTINCT product_type FROM product_tbl ORDER BY product_type");
          $stmt->execute();
          $productTypes = $stmt->fetchAll(PDO::FETCH_COLUMN);

          foreach ($productTypes as $type) {
                    $selected = (isset($_POST['productType']) && $_POST['productType'] == $type) ? 'selected' : '';
                    echo "<option value=\"$type\" $selected>" . ucfirst($type) . "</option>";
          }
          ?>
        </select>
        
            <button class="search-btn" type="submit" name="search-button">
                <i class="fas fa-search me-2"></i>Search
            </button>
      </form>
    </div>

    <!-- Products Section -->
    <div class="products-container">
        <?php
        require_once('connections/pdo.php');
        $search_result_set = array();

        if (isset($_POST['search-button'])) {
            $term = $_POST['searchInput'];
            $productType = $_POST['productType'];

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
        } else {
            $stmt = $conn->prepare("SELECT * FROM product_tbl ORDER BY product_name");
            $stmt->execute();
            $search_result_set = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if (count($search_result_set) == 0 && isset($_POST['search-button'])) {
            echo '<div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No products found</h3>
                    <p>Try adjusting your search terms or browse all products</p>
                  </div>';
        } else {
            echo '<div class="products-grid">';
            
            foreach ($search_result_set as $index => $row) {
                $availableItem = $row['available_item'];
                $stockStatus = '';
                $badgeClass = '';
                
                if ($availableItem >= 21) {
                    $stockStatus = 'In Stock';
                    $badgeClass = 'badge-in-stock';
                } elseif ($availableItem > 0) {
                    $stockStatus = 'Low Stock';
                    $badgeClass = 'badge-low-stock';
                } else {
                    $stockStatus = 'Out of Stock';
                    $badgeClass = 'badge-out-of-stock';
                }
                
                echo '<div class="product-card fade-in" data-aos="fade-up" data-aos-delay="' . ($index * 100) . '">
                        <form action="" method="POST">
                            <div class="product-image-container">
                                <img src="data:image/jpeg;base64,' . base64_encode($row['product_image']) . '" 
                                     class="product-image" 
                                     alt="' . htmlspecialchars($row['product_name']) . '">
                                <div class="product-badge ' . $badgeClass . '">' . $stockStatus . '</div>
                            </div>
                            
                            <div class="product-content">
                                <h3 class="product-name">' . htmlspecialchars($row['product_name']) . '</h3>
                                <p class="product-type">' . ucfirst($row['product_type']) . '</p>
                                <div class="product-price">$' . number_format($row['price'], 2) . '</div>
                                
                                <input type="hidden" name="id" value="' . $row['product_Id'] . '">
                                <input type="hidden" name="name" value="' . htmlspecialchars($row['product_name']) . '">
                                <input type="hidden" name="type" value="' . $row['product_type'] . '">
                                <input type="hidden" name="price" value="' . $row['price'] . '">
                                
                                <div class="quantity-section">
                                    <label class="quantity-label" for="qty">Quantity:</label>
                                    <input type="number" 
                                           value="1" 
                                           min="1" 
                                           max="100" 
                                           name="qty" 
                                           class="quantity-input"
                                           id="qty">
                                </div>
                                
                                <button type="submit" 
                                        name="addCart" 
                                        class="add-to-cart-btn" 
                                        ' . (($availableItem > 0) ? '' : 'disabled') . '>
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    ' . (($availableItem > 0) ? 'Add to Cart' : 'Out of Stock') . '
                                </button>
                            </div>
                        </form>
                      </div>';
            }
            
            echo '</div>';
        }
        ?>
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
                title: 'Success!',
                text: 'Product added to cart successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        <?php } ?>

        // Add loading animation to forms
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.querySelector('button[name="addCart"]')) {
                        const btn = this.querySelector('button[name="addCart"]');
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
                        btn.disabled = true;
                        
                        // Re-enable after 2 seconds
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.disabled = false;
                        }, 2000);
                    }
                });
            });

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';

            // Add hover effects to product cards
            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });

            // Add quantity input validation
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value < 1) this.value = 1;
                    if (this.value > 100) this.value = 100;
                });
            });

            // Add search form enhancement
            const searchForm = document.querySelector('.search-form');
            if (searchForm) {
                searchForm.addEventListener('submit', function() {
                    const searchInput = this.querySelector('.search-input');
                    const filterSelect = this.querySelector('.filter-select');
                    
                    if (searchInput.value.trim() === '' && filterSelect.value === '') {
                        // Show all products if no search criteria
                        return true;
                    }
                });
            }
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

        // Add smooth animations to product cards
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all product cards
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>