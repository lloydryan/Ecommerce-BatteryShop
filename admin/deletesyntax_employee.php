<?php
require_once('connections/pdo.php');

try {
    $sql = "DELETE FROM emp_tbl WHERE ID = :ID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':ID', $_GET['ID'], PDO::PARAM_STR);
    $stmt->execute();
    header('Location: manage-employee.php');
} catch(PDOException $e) {
    echo "ERROR: ". $e->getMessage();
}

$conn = null;
?>
