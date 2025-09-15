<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
  header("Location: home.php");
  exit();
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection script
    require_once('connections/pdo.php');

    // Get the form data
    $employeeID = $_POST['employeeID'];
    $username = $_POST['username'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $password = $_POST['password']; // Get the value of the Password field

    // Update the employee details in the database
    try {
        $stmt = $conn->prepare("UPDATE emp_tbl SET Username = :username, FirstName = :firstName, LastName = :lastName, Password = :password WHERE ID = :employeeID");
        $stmt->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR); // Bind the plain text password
        
        if ($stmt->execute()) {
            // Redirect to the edit-employee.php page with a success message
            $_SESSION['success_message'] = "Employee details updated successfully.";
            header("Location: manage-employee.php");
            exit;
        } else {
            // Redirect to the edit-employee.php page with an error message
            $_SESSION['error_message'] = "Error updating employee details.";
            header("Location: edit-employee.php?ID=" . $employeeID);
            exit;
        }
    } catch(PDOException $e) {
        echo "ERROR: " . $e->getMessage();
    }
    $conn = null;
} else {
    // If the form was not submitted, redirect to the edit-employee.php page
    header("Location: edit-employee.php");
    exit;
}

?>
