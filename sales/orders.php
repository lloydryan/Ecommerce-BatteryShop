<?php
include_once('connections/connection.php');
session_start();

// Check if the user is logged in and get their user ID from the session
$user_id = isset($_SESSION['ID']) ? $_SESSION['ID'] : 0;

$sql = "SELECT * FROM order_tbl WHERE User_Id = $user_id";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    header("Location: home.php");
    exit;
}

mysqli_close($conn);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    
    <link rel="stylesheet" href="style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" crossorigin="anonymous" /> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script defer src="active_link.js"></script>

    <style>
        body {
            background-color: #f8f9fa;
        }
        .container-table {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }
        table {
            text-align: center;
        }
        thead tr {
            background-color: #FF9333;
            color: white;
            text-align: center;
        }
        th, td {
            vertical-align: middle;
            text-align: center;
        }
        a.view-receipt {
            text-decoration: none;
            font-weight: bold;
            color: #007bff;
        }
        a.view-receipt:hover {
            text-decoration: underline;
        }
        .fa-eye {
            color: #007bff;
            margin-right: 5px;
        }
    </style>
</head>
<body>
<?php include_once('header-footer/header.php') ?>

<div class="container mt-4">
    <h1>Order History</h1>
    <div class="row justify-content-center">
        <div class="container-table" style="height: auto; width: 98%;">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>   
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Customer Name</th>
                        <th>Amount</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['transaction_id']; ?></td>
                            <td><?= $row['order_date']; ?></td>
                            <td><?= $_SESSION['Username']; ?></td>
                            <td>₱ <?= number_format($row['order_total'], 2); ?></td>
                            <td>
                                <i class="fa-regular fa-eye"></i>
                                <a href="view_receipt.php?id=<?= $row['transaction_id']; ?>" class="view-receipt">View Receipt</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
