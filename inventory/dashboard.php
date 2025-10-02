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

// Include database connection
include_once('connections/connection.php');

// Get inventory statistics
$stats = array();

// Total products
$product_query = "SELECT COUNT(*) as total FROM product_tbl";
$product_result = mysqli_query($conn, $product_query);
$product_data = mysqli_fetch_assoc($product_result);
$stats['products'] = $product_data['total'];

// Total stock value
$stock_value_query = "SELECT SUM(price * available_item) as total_value FROM product_tbl";
$stock_value_result = mysqli_query($conn, $stock_value_query);
$stock_value_data = mysqli_fetch_assoc($stock_value_result);
$stats['stock_value'] = $stock_value_data['total_value'] ?? 0;

// Low stock products (≤ 10)
$low_stock_query = "SELECT COUNT(*) as total FROM product_tbl WHERE available_item <= 10";
$low_stock_result = mysqli_query($conn, $low_stock_query);
$low_stock_data = mysqli_fetch_assoc($low_stock_result);
$stats['low_stock'] = $low_stock_data['total'];

// Out of stock products
$out_of_stock_query = "SELECT COUNT(*) as total FROM product_tbl WHERE available_item = 0";
$out_of_stock_result = mysqli_query($conn, $out_of_stock_query);
$out_of_stock_data = mysqli_fetch_assoc($out_of_stock_result);
$stats['out_of_stock'] = $out_of_stock_data['total'];

// Recent stock-in transactions
$recent_stockin_query = "SELECT s.transaction_id, s.transaction_date, e.FirstName, e.LastName, 
                        COUNT(se.stockin_Id) as items_count
                        FROM stockin_tbl s 
                        LEFT JOIN emp_tbl e ON s.employee_ID = e.ID 
                        LEFT JOIN stockin_entries se ON s.transaction_id = se.transaction_id
                        GROUP BY s.transaction_id, s.transaction_date, e.FirstName, e.LastName
                        ORDER BY s.transaction_date DESC 
                        LIMIT 5";
$recent_stockin_result = mysqli_query($conn, $recent_stockin_query);

// Recent damage reports
$recent_damage_query = "SELECT d.transaction_id, d.transaction_date, d.description, e.FirstName, e.LastName,
                       COUNT(de.entry_id) as items_count
                       FROM damage_tbl d 
                       LEFT JOIN emp_tbl e ON d.employee_ID = e.ID 
                       LEFT JOIN damage_entries de ON d.transaction_id = de.transaction_id
                       GROUP BY d.transaction_id, d.transaction_date, d.description, e.FirstName, e.LastName
                       ORDER BY d.transaction_date DESC 
                       LIMIT 5";
$recent_damage_result = mysqli_query($conn, $recent_damage_query);

// Top products by stock
$top_products_query = "SELECT product_name, available_item, price 
                      FROM product_tbl 
                      ORDER BY available_item DESC 
                      LIMIT 5";
$top_products_result = mysqli_query($conn, $top_products_query);

// Low stock products details
$low_stock_details_query = "SELECT product_name, available_item, price 
                           FROM product_tbl 
                           WHERE available_item <= 10 
                           ORDER BY available_item ASC 
                           LIMIT 5";
$low_stock_details_result = mysqli_query($conn, $low_stock_details_query);

// Monthly stock-in data for chart
$monthly_stockin_query = "SELECT MONTH(transaction_date) as month, COUNT(*) as total 
                         FROM stockin_tbl 
                         WHERE YEAR(transaction_date) = YEAR(CURDATE()) 
                         GROUP BY MONTH(transaction_date) 
                         ORDER BY month";
$monthly_stockin_result = mysqli_query($conn, $monthly_stockin_query);

$monthly_stockin_data = array();
while($row = mysqli_fetch_assoc($monthly_stockin_result)) {
    $monthly_stockin_data[$row['month']] = $row['total'];
}

// Fill missing months with 0
for($i = 1; $i <= 12; $i++) {
    if(!isset($monthly_stockin_data[$i])) {
        $monthly_stockin_data[$i] = 0;
    }
}

// Monthly damage data for chart
$monthly_damage_query = "SELECT MONTH(transaction_date) as month, COUNT(*) as total 
                        FROM damage_tbl 
                        WHERE YEAR(transaction_date) = YEAR(CURDATE()) 
                        GROUP BY MONTH(transaction_date) 
                        ORDER BY month";
$monthly_damage_result = mysqli_query($conn, $monthly_damage_query);

$monthly_damage_data = array();
while($row = mysqli_fetch_assoc($monthly_damage_result)) {
    $monthly_damage_data[$row['month']] = $row['total'];
}

// Fill missing months with 0
for($i = 1; $i <= 12; $i++) {
    if(!isset($monthly_damage_data[$i])) {
        $monthly_damage_data[$i] = 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Inventory Dashboard - Mark's Battery Shop</title>
    <style>
        .dashboard-card {
            background: linear-gradient(135deg, var(--blue1), var(--blue2));
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: 400px;
        }
        .chart-container canvas {
            max-height: 300px !important;
        }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .welcome-header {
            background: linear-gradient(135deg, var(--blue1), var(--blue3));
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        .alert-warning {
            background: linear-gradient(135deg, #ffc107, #ff8c00);
            border: none;
            color: white;
        }
        .alert-danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            color: white;
        }
        .table th {
            background-color: var(--blue1);
            color: white;
            border: none;
        }
        .badge {
            font-size: 0.8rem;
        }
        .quick-action-btn {
            background: linear-gradient(135deg, var(--blue1), var(--blue2));
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            margin: 5px;
        }
        .quick-action-btn:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <?php include_once('navbar/navbar.php'); ?>

    <div id="content" class="flex-grow-1 d-flex flex-column overflow-auto" style="min-height: 100vh; max-width: 100vw;">
        <div class="container-fluid p-4">
            <!-- Welcome Header -->
            <div class="welcome-header">
                <h1><i class="fas fa-warehouse me-3"></i>Inventory Management Dashboard</h1>
                <p class="mb-0">Welcome back, <?php echo $firstName . ' ' . $lastName; ?>! Monitor your battery inventory in real-time.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="dashboard-card text-center">
                        <i class="fas fa-boxes stat-icon"></i>
                        <div class="stat-number"><?php echo $stats['products']; ?></div>
                        <div class="stat-label">Total Products</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="dashboard-card text-center">
                        <i class="fas fa-dollar-sign stat-icon"></i>
                        <div class="stat-number">₱<?php echo number_format($stats['stock_value'], 0); ?></div>
                        <div class="stat-label">Stock Value</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="dashboard-card text-center">
                        <i class="fas fa-exclamation-triangle stat-icon"></i>
                        <div class="stat-number"><?php echo $stats['low_stock']; ?></div>
                        <div class="stat-label">Low Stock Items</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="dashboard-card text-center">
                        <i class="fas fa-times-circle stat-icon"></i>
                        <div class="stat-number"><?php echo $stats['out_of_stock']; ?></div>
                        <div class="stat-label">Out of Stock</div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row mb-4">
                <!-- Stock-in Chart -->
                <div class="col-lg-6 mb-4">
                    <div class="chart-container">
                        <h4 class="mb-3"><i class="fas fa-arrow-up me-2"></i>Monthly Stock-In</h4>
                        <canvas id="stockinChart"></canvas>
                    </div>
                </div>

                <!-- Damage Chart -->
                <div class="col-lg-6 mb-4">
                    <div class="chart-container">
                        <h4 class="mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Monthly Damage Reports</h4>
                        <canvas id="damageChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tables Row -->
            <div class="row">
                <!-- Low Stock Alert -->
                <div class="col-lg-6 mb-4">
                    <div class="table-container">
                        <h4 class="mb-3"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Low Stock Alert</h4>
                        <?php if(mysqli_num_rows($low_stock_details_result) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($low_stock_details_result)): ?>
                                            <tr>
                                                <td><?php echo $row['product_name']; ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $row['available_item'] == 0 ? 'danger' : 'warning'; ?>">
                                                        <?php echo $row['available_item']; ?>
                                                    </span>
                                                </td>
                                                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>All products are well stocked!
                            </div>
                        <?php endif; ?>
            </div>
        </div>
    
                <!-- Top Products by Stock -->
                <div class="col-lg-6 mb-4">
                    <div class="table-container">
                        <h4 class="mb-3"><i class="fas fa-trophy me-2"></i>Top Products by Stock</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($top_products_result)): ?>
                                        <tr>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><span class="badge bg-success"><?php echo $row['available_item']; ?></span></td>
                                            <td>₱<?php echo number_format($row['available_item'] * $row['price'], 2); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <!-- Recent Stock-In -->
                <div class="col-lg-6 mb-4">
                    <div class="table-container">
                        <h4 class="mb-3"><i class="fas fa-arrow-down me-2"></i>Recent Stock-In</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php while($row = mysqli_fetch_assoc($recent_stockin_result)): ?>
                                            <tr>
                                                <td>#<?php echo $row['transaction_id']; ?></td>
                                                <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($row['transaction_date'])); ?></td>
                                                <td><span class="badge bg-primary"><?php echo $row['items_count']; ?></span></td>
                                            </tr>
                                        <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Damage Reports -->
                <div class="col-lg-6 mb-4">
                    <div class="table-container">
                        <h4 class="mb-3"><i class="fas fa-warning me-2"></i>Recent Damage Reports</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Employee</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php while($row = mysqli_fetch_assoc($recent_damage_result)): ?>
                                            <tr>
                                                <td>#<?php echo $row['transaction_id']; ?></td>
                                                <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($row['transaction_date'])); ?></td>
                                                <td><span class="badge bg-danger"><?php echo $row['items_count']; ?></span></td>
                                            </tr>
                                        <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="table-container">
                        <h4 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h4>
                        <div class="text-center">
                            <a href="stockin.php" class="quick-action-btn">
                                <i class="fas fa-arrow-down me-2"></i>Stock In
                            </a>
                            <a href="damage.php" class="quick-action-btn">
                                <i class="fas fa-exclamation-triangle me-2"></i>Report Damage
                            </a>
                            <a href="PO.php" class="quick-action-btn">
                                <i class="fas fa-file-invoice me-2"></i>Purchase Order
                            </a>
                            <a href="exchange.php" class="quick-action-btn">
                                <i class="fas fa-exchange-alt me-2"></i>Exchange
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="css/main.js"></script>
    <script>
        // Stock-in Chart
        const stockinCtx = document.getElementById('stockinChart').getContext('2d');
        const stockinChart = new Chart(stockinCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Stock-In Transactions',
                    data: [
                        <?php echo $monthly_stockin_data[1]; ?>,
                        <?php echo $monthly_stockin_data[2]; ?>,
                        <?php echo $monthly_stockin_data[3]; ?>,
                        <?php echo $monthly_stockin_data[4]; ?>,
                        <?php echo $monthly_stockin_data[5]; ?>,
                        <?php echo $monthly_stockin_data[6]; ?>,
                        <?php echo $monthly_stockin_data[7]; ?>,
                        <?php echo $monthly_stockin_data[8]; ?>,
                        <?php echo $monthly_stockin_data[9]; ?>,
                        <?php echo $monthly_stockin_data[10]; ?>,
                        <?php echo $monthly_stockin_data[11]; ?>,
                        <?php echo $monthly_stockin_data[12]; ?>
                    ],
                    backgroundColor: 'rgba(52, 75, 183, 0.8)',
                    borderColor: '#344CB7',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 2.5,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Damage Chart
        const damageCtx = document.getElementById('damageChart').getContext('2d');
        const damageChart = new Chart(damageCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Damage Reports',
                    data: [
                        <?php echo $monthly_damage_data[1]; ?>,
                        <?php echo $monthly_damage_data[2]; ?>,
                        <?php echo $monthly_damage_data[3]; ?>,
                        <?php echo $monthly_damage_data[4]; ?>,
                        <?php echo $monthly_damage_data[5]; ?>,
                        <?php echo $monthly_damage_data[6]; ?>,
                        <?php echo $monthly_damage_data[7]; ?>,
                        <?php echo $monthly_damage_data[8]; ?>,
                        <?php echo $monthly_damage_data[9]; ?>,
                        <?php echo $monthly_damage_data[10]; ?>,
                        <?php echo $monthly_damage_data[11]; ?>,
                        <?php echo $monthly_damage_data[12]; ?>
                    ],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 2.5,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>

<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>