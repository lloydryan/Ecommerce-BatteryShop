<?php
session_start();

// Check if user is logged in, similar to your existing code

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection code here (similar to your existing code)
      // Include your database connection code here
      $db_host = "localhost"; // Change this to your database host
      $db_username = "root"; // Change this to your database username
      $db_password = ""; // Change this to your database password
      $db_name = "it12l"; // Change this to your database name
  
      // Create a database connection
      $conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);
  
      // Check if the connection was successful
      if (!$conn) {
          die("Connection failed: " . mysqli_connect_error());
      }


    // Get user details from the form
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $contactNumber = $_POST["contactNumber"];
    $address = $_POST["address"];
    $username = $_POST["username"];
    
    // Set default password and status
    $defaultPassword = "123";
    $status = "Active";

    // Perform SQL insert to add the user to the database
    $insertQuery = "INSERT INTO users_tbl (first_name, last_name, email, ConNo, Address1, Username, Password, Status) 
                    VALUES ('$firstName', '$lastName', '$email', '$contactNumber', '$address', '$username', '$defaultPassword', '$status')";

    if (mysqli_query($conn, $insertQuery)) {
        // User added successfully
        header("Location: manage-user.php"); // Redirect to the user management page
        exit();
    } else {
        // Error occurred while adding the user
        echo "Error: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Add user</title>

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
    <h1>Add User</h1>
    <p>Please fill in this form to create an account.</p>
    <HR></HR>


        <!-- Add form fields for user details (e.g., first name, last name, email, etc.) -->
         <div class="form-group">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" class="box"  name="firstName" required><br><br>
        </div>

        <!-- Add more form fields for other user details here -->
         <div class="form-group">
        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" class="box"  name="lastName" required><br><br>
        </div>

         <div class="form-group">
        <label for="email">Email Account:</label>
        <input type="email" id="email" class="box"  name="email" required><br><br>
        </div>

         <div class="form-group">
        <label for="contactNumber">Contact Number:</label>
        <input type="tel" id="contactNumber" class="box"  name="contactNumber" required><br><br>
        </div>

         <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" id="address" class="box"  name="address" required><br><br>
        </div>

         <div class="form-group">
        <label for="username">User Name:</label>
        <input type="text" id="username" class="box"  name="username" required><br><br>
        </div>
        <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>
        <!-- Default Password and Status are set in the PHP code -->

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    </div>
</body>
</html>