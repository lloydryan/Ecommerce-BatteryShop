<?php
require_once('connections/pdo.php');

try {
    $sql = "DELETE FROM product_tbl WHERE product_Id = :product_Id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':product_Id', $_GET['product_Id'], PDO::PARAM_STR);
    $stmt->execute();
    header('Location: manage-product.php');
} catch(PDOException $e) {
    echo "ERROR: ". $e->getMessage();
}

$conn = null;
?>
