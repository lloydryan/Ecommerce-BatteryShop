<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
  header("Location: home.php");
  exit();
}
if (isset($_GET['signout'])) {
    // Unset all session variables
    $_SESSION = array();
  
    // Destroy the session
    session_destroy();
  
    // Redirect to login page
    header("Location: home.php");
    exit;
}

// Include database connection
include_once('connections/connection.php');

// Get dashboard statistics
$stats = array();

// Total customers
$customer_query = "SELECT COUNT(*) as total FROM customer_tbl WHERE Status = 'Active'";
$customer_result = mysqli_query($conn, $customer_query);
$customer_data = mysqli_fetch_assoc($customer_result);
$stats['customers'] = $customer_data['total'];

// Total products
$product_query = "SELECT COUNT(*) as total FROM product_tbl";
$product_result = mysqli_query($conn, $product_query);
$product_data = mysqli_fetch_assoc($product_result);
$stats['products'] = $product_data['total'];

// Total employees
$employee_query = "SELECT COUNT(*) as total FROM emp_tbl";
$employee_result = mysqli_query($conn, $employee_query);
$employee_data = mysqli_fetch_assoc($employee_result);
$stats['employees'] = $employee_data['total'];

// Total orders
$order_query = "SELECT COUNT(*) as total FROM order_tbl";
$order_result = mysqli_query($conn, $order_query);
$order_data = mysqli_fetch_assoc($order_result);
$stats['orders'] = $order_data['total'];

// Recent orders
$recent_orders_query = "SELECT o.transaction_id, o.order_date, c.first_name, c.last_name, o.order_total 
                       FROM order_tbl o 
                       JOIN customer_tbl c ON o.User_Id = c.ID 
                       ORDER BY o.order_date DESC 
                       LIMIT 5";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);

// Low stock products
$low_stock_query = "SELECT product_name, available_item 
                   FROM product_tbl 
                   WHERE available_item <= 10 
                   ORDER BY available_item ASC 
                   LIMIT 5";
$low_stock_result = mysqli_query($conn, $low_stock_query);

// Recent customers
$recent_customers_query = "SELECT first_name, last_name, email, Status 
                          FROM customer_tbl 
                          ORDER BY ID DESC 
                          LIMIT 5";
$recent_customers_result = mysqli_query($conn, $recent_customers_query);

// Monthly sales data for chart
$monthly_sales_query = "SELECT MONTH(order_date) as month, SUM(order_total) as total 
                       FROM order_tbl 
                       WHERE YEAR(order_date) = YEAR(CURDATE()) 
                       GROUP BY MONTH(order_date) 
                       ORDER BY month";
$monthly_sales_result = mysqli_query($conn, $monthly_sales_query);

$monthly_data = array();
while($row = mysqli_fetch_assoc($monthly_sales_result)) {
    $monthly_data[$row['month']] = $row['total'];
}

// Fill missing months with 0
for($i = 1; $i <= 12; $i++) {
    if(!isset($monthly_data[$i])) {
        $monthly_data[$i] = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mark's Battery Shop</title>
    <!-- ======= Styles ====== -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link rel="stylesheet" href="css/pagestyle.css">
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
        .table th {
            background-color: var(--blue1);
            color: white;
            border: none;
        }
        .badge {
            font-size: 0.8rem;
        }
    </style>
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="d-flex" id="wrapper">
        <?php include_once('navbar/navbar1.php'); ?>

        <div id="content" class="flex-grow-1 d-flex flex-column overflow-auto" style="min-height: 100vh; max-width: 100vw;">
          
            <div class="container-fluid p-4">
                <!-- Welcome Header -->
                <div class="welcome-header">
                    <h1><i class="fas fa-tachometer-alt me-3"></i>Admin Dashboard</h1>
                    <p class="mb-0">Welcome back, <?php echo $_SESSION["FullName"]; ?>! Here's what's happening with your battery shop.</p>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="dashboard-card text-center">
                            <i class="fas fa-users stat-icon"></i>
                            <div class="stat-number"><?php echo $stats['customers']; ?></div>
                            <div class="stat-label">Active Customers</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="dashboard-card text-center">
                            <i class="fas fa-battery-half stat-icon"></i>
                            <div class="stat-number"><?php echo $stats['products']; ?></div>
                            <div class="stat-label">Total Products</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="dashboard-card text-center">
                            <i class="fas fa-shopping-cart stat-icon"></i>
                            <div class="stat-number"><?php echo $stats['orders']; ?></div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="dashboard-card text-center">
                            <i class="fas fa-user-tie stat-icon"></i>
                            <div class="stat-number"><?php echo $stats['employees']; ?></div>
                            <div class="stat-label">Employees</div>
                        </div>
                    </div>
                </div>

                <!-- Charts and Tables Row -->
                <div class="row">
                    <!-- Sales Chart -->
                    <div class="col-lg-8 mb-4">
                        <div class="chart-container">
                            <h4 class="mb-3"><i class="fas fa-chart-line me-2"></i>Monthly Sales</h4>
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Low Stock Alert -->
                    <div class="col-lg-4 mb-4">
                        <div class="table-container">
                            <h4 class="mb-3"><i class="fas fa-exclamation-triangle me-2 text-warning"></i>Low Stock Alert</h4>
                            <?php if(mysqli_num_rows($low_stock_result) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($row = mysqli_fetch_assoc($low_stock_result)): ?>
                                                <tr>
                                                    <td><?php echo $row['product_name']; ?></td>
                                                    <td><span class="badge bg-danger"><?php echo $row['available_item']; ?></span></td>
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
                </div>

                <!-- Recent Orders and Customers -->
                <div class="row">
                    <!-- Recent Orders -->
                    <div class="col-lg-6 mb-4">
                        <div class="table-container">
                            <h4 class="mb-3"><i class="fas fa-receipt me-2"></i>Recent Orders</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($recent_orders_result)): ?>
                                            <tr>
                                                <td>#<?php echo $row['transaction_id']; ?></td>
                                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($row['order_date'])); ?></td>
                                                <td>₱<?php echo number_format($row['order_total'], 2); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Customers -->
                    <div class="col-lg-6 mb-4">
                        <div class="table-container">
                            <h4 class="mb-3"><i class="fas fa-user-plus me-2"></i>Recent Customers</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($recent_customers_result)): ?>
                                            <tr>
                                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $row['Status'] == 'Active' ? 'success' : 'danger'; ?>">
                                                        <?php echo $row['Status']; ?>
                                                    </span>
                                                </td>
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
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <a href="add-product.php" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-2"></i>Add Product
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="add-user.php" class="btn btn-success w-100">
                                        <i class="fas fa-user-plus me-2"></i>Add Customer
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="add-employee.php" class="btn btn-info w-100">
                                        <i class="fas fa-user-tie me-2"></i>Add Employee
                                    </a>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <a href="manage-product.php" class="btn btn-warning w-100">
                                        <i class="fas fa-cogs me-2"></i>Manage Products
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="css/main.js"></script>
    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Sales (₱)',
                    data: [
                        <?php echo $monthly_data[1]; ?>,
                        <?php echo $monthly_data[2]; ?>,
                        <?php echo $monthly_data[3]; ?>,
                        <?php echo $monthly_data[4]; ?>,
                        <?php echo $monthly_data[5]; ?>,
                        <?php echo $monthly_data[6]; ?>,
                        <?php echo $monthly_data[7]; ?>,
                        <?php echo $monthly_data[8]; ?>,
                        <?php echo $monthly_data[9]; ?>,
                        <?php echo $monthly_data[10]; ?>,
                        <?php echo $monthly_data[11]; ?>,
                        <?php echo $monthly_data[12]; ?>
                    ],
                    borderColor: '#344CB7',
                    backgroundColor: 'rgba(52, 75, 183, 0.1)',
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
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString();
                            }
                        }
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

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>