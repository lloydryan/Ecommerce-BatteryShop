<?php
   include_once('connections/connection.php');
// Check the connection
// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to select data from the product_tbl table
$sql = "SELECT product_Id, product_name FROM product_tbl";

// Execute the query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<label for='product'>Select a product:</label>";
    echo "<select id='product' name='product'>";
    
    // Output data of each row as dropdown options
    while ($row = $result->fetch_assoc()) {
        $productId = $row["product_Id"];
        $productName = $row["product_name"];
        echo "<option value='$productId'>$productName</option>";
    }
    
    echo "</select>";
} else {
    echo "No products found in the database.";
}

// Close the database connection
$conn->close();
?>
