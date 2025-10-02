<?php
include_once ('connections/connection.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Exchange Request - Mark's Battery Shop</title>
    <link rel="stylesheet" href="v2.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script defer src="active_link.js"></script>
    <style>
        :root {
            --primary-color: #000957;
            --secondary-color: #344CB7;
            --accent-color: #577BC1;
            --warning-color: #FFEB00;
            --dark-color: #333;
            --light-color: #eee;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .main-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            margin: 20px auto;
            padding: 0;
            overflow: hidden;
            max-width: 1400px;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .header-content {
            position: relative;
            z-index: 2;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 3;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .content-section {
            padding: 40px 30px;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .product-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
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
            background: var(--warning-color);
            color: var(--primary-color);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .product-info {
            padding: 25px;
        }

        .product-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .product-type {
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        .product-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 20px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .request-btn {
            background: linear-gradient(135deg, var(--danger-color), #c82333);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
            font-size: 0.95rem;
        }

        .request-btn:hover {
            background: linear-gradient(135deg, #c82333, #a71e2a);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-products i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .search-section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 9, 87, 0.25);
            outline: none;
        }

        .filter-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            color: #666;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .filter-btn.active,
        .filter-btn:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 10px;
                border-radius: 15px;
            }

            .header-section {
                padding: 30px 20px;
            }

            .header-section h1 {
                font-size: 2rem;
            }

            .content-section {
                padding: 30px 20px;
            }

            .products-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .back-button {
                position: static;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Header Section -->
        <div class="header-section">
            <div class="back-button">
                <a href="product.php" class="back-btn">
                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                </a>
            </div>
            <div class="header-content">
                <h1><i class="fas fa-exchange-alt me-3"></i>Product Exchange Request</h1>
                <p>Select a product you'd like to exchange and submit your request</p>
            </div>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <!-- Search and Filter Section -->
            <div class="search-section">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="search-input" id="searchInput" placeholder="Search products by name, type, or description...">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select search-input" id="typeFilter">
                            <option value="">All Product Types</option>
                            <option value="Battery">Battery</option>
                            <option value="Charger">Charger</option>
                            <option value="Accessory">Accessory</option>
                        </select>
                    </div>
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-filter="all">All Products</button>
                    <button class="filter-btn" data-filter="battery">Batteries</button>
                    <button class="filter-btn" data-filter="charger">Chargers</button>
                    <button class="filter-btn" data-filter="accessory">Accessories</button>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid">
                <?php
                require_once('connections/pdo.php');
                try {
                    $stmt = $conn->prepare("SELECT product_name, product_image, product_type, product_desc, product_Id, price FROM product_tbl ORDER BY product_name ASC");
                    $stmt->execute();
                    $products = $stmt->fetchAll();
                    
                    if (empty($products)) {
                        echo '<div class="no-products">
                                <i class="fas fa-box-open"></i>
                                <h3>No Products Available</h3>
                                <p>There are currently no products available for exchange requests.</p>
                              </div>';
                    } else {
                        foreach ($products as $row) {
                            $productType = strtolower($row['product_type']);
                            ?>
                            <div class="product-card" data-type="<?php echo $productType; ?>" data-name="<?php echo strtolower($row['product_name']); ?>" data-description="<?php echo strtolower($row['product_desc']); ?>">
                                <div class="product-image-container">
                                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>"
                                         alt="<?php echo htmlspecialchars($row['product_name']); ?>" 
                                         class="product-image">
                                    <div class="product-badge"><?php echo $row['product_type']; ?></div>
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name"><?php echo htmlspecialchars($row['product_name']); ?></h3>
                                    <div class="product-type"><?php echo htmlspecialchars($row['product_type']); ?></div>
                                    <p class="product-description"><?php echo htmlspecialchars($row['product_desc']); ?></p>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">Price: <strong>₱<?php echo number_format($row['price'], 2); ?></strong></span>
                                    </div>
                                    <a href='add_request.php?product_Id=<?php echo $row['product_Id']; ?>'
                                       class="request-btn">
                                        <i class="fas fa-exchange-alt"></i>
                                        Request Exchange
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                } catch (PDOException $e) {
                    echo '<div class="no-products">
                            <i class="fas fa-exclamation-triangle"></i>
                            <h3>Error Loading Products</h3>
                            <p>There was an error loading the products. Please try again later.</p>
                          </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Search and Filter Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const filterButtons = document.querySelectorAll('.filter-btn');
            const productCards = document.querySelectorAll('.product-card');

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedType = typeFilter.value.toLowerCase();
                const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;

                productCards.forEach(card => {
                    const productName = card.dataset.name;
                    const productDescription = card.dataset.description;
                    const productType = card.dataset.type;
                    
                    const matchesSearch = productName.includes(searchTerm) || 
                                        productDescription.includes(searchTerm);
                    const matchesType = selectedType === '' || productType === selectedType;
                    const matchesFilter = activeFilter === 'all' || productType === activeFilter;

                    if (matchesSearch && matchesType && matchesFilter) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeIn 0.5s ease-in';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show no results message if no products match
                const visibleCards = Array.from(productCards).filter(card => card.style.display !== 'none');
                const noResultsMsg = document.getElementById('noResultsMsg');
                
                if (visibleCards.length === 0 && productCards.length > 0) {
                    if (!noResultsMsg) {
                        const noResults = document.createElement('div');
                        noResults.id = 'noResultsMsg';
                        noResults.className = 'no-products';
                        noResults.innerHTML = '<i class="fas fa-search"></i><h3>No Products Found</h3><p>Try adjusting your search or filter criteria.</p>';
                        document.getElementById('productsGrid').appendChild(noResults);
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.remove();
                }
            }

            // Event listeners
            searchInput.addEventListener('input', filterProducts);
            typeFilter.addEventListener('change', filterProducts);
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    filterProducts();
                });
            });

            // Add fadeIn animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(20px); }
                    to { opacity: 1; transform: translateY(0); }
                }
            `;
            document.head.appendChild(style);
        });
    </script>

    <?php
    // Close the database connection
    $conn = null;
    ?>
</body>
</html>