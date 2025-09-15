<?php
include_once('connections/connection.php');
require_once('connections/pdo.php');

if (isset($_POST['Username']) && !empty($_POST['Username'])) {

  try {
    $stmt = $conn->prepare("INSERT INTO emp_tbl (Username, Password, FirstName, LastName) VALUES (:username,'123', :firstname, :lastname)");
    $stmt->bindParam(':username', $_POST['Username']);
    $stmt->bindParam(':firstname', $_POST['FirstName']);
    $stmt->bindParam(':lastname', $_POST['LastName']);
    
    $stmt->execute();
    header('Location: manage-employee.php'); // Redirect to the page where you manage employees
  } catch (PDOException $e) {
      echo "ERROR: " . $e->getMessage();
  }
  $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Add product</title>

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
    
    
    <form method="POST"  style="border:1px solid #ccc">
    <div class="row">
        <div class="col-md-12">
            <a style="font-size:15px" href="manage-user.php" >
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
    <br>
          <h2 style="text-align: center; font-weight: bold">Add New Employee</h2><br>
          <p>Please fill in this form to create an account.</p>
          <HR></HR>
            <div class="mb-3">
              <label for="Username" class="form-label">Username</label>
              <input type="text" id="Username" name="Username" class="box" required>
            </div>
            <div class="mb-3">
              <label for="FirstName" class="form-label">First Name</label>
              <input type="text" id="FirstName" name="FirstName" class="box" required>
            </div>
            <div class="mb-3">
              <label for="LastName" class="form-label">Last Name</label>
              <input type="text" id="LastName" name="LastName" class="box" required>
            </div>
            <div class="d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Add Employee</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>