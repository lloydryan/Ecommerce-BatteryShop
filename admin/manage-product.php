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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MANAGE PRODUCT</title>
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
    <link rel="stylesheet" href="css/tabledesign.css">
    <link rel="stylesheet" href="css/pagestyle.css">
   
</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="d-flex" id="wrapper">  
    <?php
  include_once ('navbar/navbar1.php');
      ?>
            <div id="content" class="active">
                    <nav class="navbar navbar-expand-lg navbar-light  border-bottom">
                        <button class="btn btn-primary" id="sidebarToggle">
                        <i id="toggleIcon" class="bi bi-x"></i> <!-- Use bi-list as the initial icon -->
                        </button>
                    </nav>
<br>
    
<div class="container-fluid">
  <div class="container">
    <!-- Your content goes here -->  
   

                    <div class="container" >
                    <h1>Products</h1>      
                            <a href="add-product.php" class="btn btn-primary" >
                            <i class="fa-solid fa-circle-plus fa-xl"></i> Add New Product
                            </a>
                    </div>
          
       
            <br>
                      <div class="row justify-content-center" >
                        
                        <div class="table-responsive" >               
                                <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; width: 100% ">
                                  <table class="table table-striped mb-0">
                                    <thead style="background-color: #0275d8; ">
                                      <tr>
                                        <center>
                                          <th class="text-center" scope="col">Product</th>
                                          <th class="text-center" scope="col">Name</th>
                                          <th class="text-center" scope="col">Type</th>
                                          <th class="text-center" scope="col">Description</th>
                                          <th class="text-center" scope="col">Price</th>
                                          <th class="text-center" scope="col" style="width: 100px;">Actions</th>
                                        </center>
                                      </tr> 
                                    </thead>
                                    <tbody>
                                      <?php
                                      require_once('connections/pdo.php');
                                      try {
                                        $stmt = $conn->prepare("SELECT * FROM product_tbl");
                                        $stmt->execute();
                                        foreach ($stmt->fetchAll() as $row) {
                                      ?>
                                      <tr>
                                        <td>

                                          <?php
                                            // output the image as data URI
                                            $img_data = base64_encode($row['product_image']);
                                            $img_type = 'image/jpeg'; // replace with the appropriate image type
                                          ?>

                                          <img src="data:<?php echo $img_type; ?>;base64,<?php echo $img_data; ?>" width="200" height="200">

                                        </td>
                                        <td class="text-center"><?php echo $row['product_name']; ?></td>
                                        <td class="text-center"><?php echo $row['product_type']; ?></td>
                                        <td class="text-center"><?php echo $row['product_desc']; ?></td>
                                        <td class="text-center"><?php echo $row['price']; ?></td>
                                        <td class="text-center">
                                          <a href="#" onclick="showConfirmation('<?php echo $row['product_Id']; ?>')" style="color:red; ma ">
                                          Delete <i class="fa-sharp fa-solid fa-trash fa-xl"></i></i>
                                            </a>
                                            <div style="padding:5px;"></div>
                                          <a href="edit-product.php?product_Id=<?php echo $row['product_Id']; ?>" >
                                          Edit <i class="fa-solid fa-pen-to-square fa-xl"></i>   
                                          </a>
                                        
                                        

                                            <script>
                                            function showConfirmation(product_Id) {
                                              Swal.fire({
                                                title: 'Are you sure?',
                                                text: 'You are about to delete this record!',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Yes, delete it!',
                                                cancelButtonText: 'No, cancel',
                                              }).then((result) => {
                                                if (result.isConfirmed) {
                                                  window.location.href = 'deletesyntax_product.php?product_Id=' + product_Id + '&confirm_delete=yes';
                                                }
                                              })
                                            }
                                            </script>
                                          </td>
                                      </tr>
                                      <?php
                                        }
                                      } catch(PDOException $e) {
                                        echo "ERROR: " . $e->getMessage();
                                      }
                                      $conn = null;
                                      ?>
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                            </div>
                          </div>
           

                          <script src="css/main.js"></script>

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
