<?php
    require_once('connections/pdo.php');

    try {
        $sql = "UPDATE product_tbl SET product_name = :product_name, product_type = :product_type, product_desc = :product_desc, price = :price";

        $imageParam = '';

        if (!empty($_FILES['product_image']['tmp_name'])) {
            $sql .= ", product_image = :product_image";
            $imageParam = file_get_contents($_FILES['product_image']['tmp_name']);
        }

        $sql .= " WHERE product_Id = :product_Id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':product_Id', $_GET['product_Id'], PDO::PARAM_STR);
        $stmt->bindParam(':product_name', $_POST['product_name'], PDO::PARAM_STR);
        $stmt->bindParam(':product_type', $_POST['product_type'], PDO::PARAM_STR);
        $stmt->bindParam(':product_desc', $_POST['product_desc'], PDO::PARAM_STR);
        $stmt->bindParam(':price', $_POST['price'], PDO::PARAM_STR);

        if (!empty($imageParam)) {
            $stmt->bindParam(':product_image', $imageParam, PDO::PARAM_LOB);
            $target_dir = "image/product/";
            $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
            move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
        }

        $stmt->execute();
        header('Location: manage-product.php');
    } catch(PDOException $e) {
        echo "ERROR: ". $e->getMessage();
    }

    $conn = null;
?>
