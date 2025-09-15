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

// Check if the 'ID' parameter is set in the URL
if (isset($_GET['ID'])) {
    $employeeID = $_GET['ID'];
    
    // Fetch employee details from the database based on the provided ID
    require_once('connections/pdo.php'); // Include your database connection script
    try {
        $stmt = $conn->prepare("SELECT * FROM emp_tbl WHERE ID = :employeeID");
        $stmt->bindParam(':employeeID', $employeeID, PDO::PARAM_INT);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "ERROR: " . $e->getMessage();
    }
    $conn = null;
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>UPDATE FORM: Employee</title>

    <link rel="stylesheet" href="css/update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>
<body >


<div class="container">

<div class="admin-product-form-container centered">
       
        <form action="editsyntax_employee.php" method="POST" style="border:1px solid #ccc">
        <div class="container">
             <div class="row">
                <div class="col-md-12">
                 <a style="font-size:15px" href="manage-employee.php">
                <i class="fas fa-arrow-left"></i> Back
                </a>
             </div>
        </div>    
        <br>
        <!-- You can display the employee details here in input fields for editing -->
        <h2>Edit Employee Details</h2>
        <hr>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="box" id="username" name="username" value="<?php echo $employee['Username']; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="text" class="box" id="password" name="password" value="<?php echo $employee['Password']; ?>">
            </div>
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" class="box" id="firstName" name="firstName" value="<?php echo $employee['FirstName']; ?>">
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" class="box" id="lastName" name="lastName" value="<?php echo $employee['LastName']; ?>">
            </div>
            <!-- Hidden input field to pass the employee ID to the update script -->
            <input type="hidden" name="employeeID" value="<?php echo $employee['ID']; ?>">
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <style>


    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
        crossorigin="anonymous"></script>
</body>

</html>
