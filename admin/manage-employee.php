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
    <title>MANAGE EMPLOYEE</title>
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

    <?php
  include_once ('navbar/navbar1.php');
      ?>
          
    
<div class="container-fluid" >

    <!-- Your content goes here -->  

                    <h1>Employees</h1>      
                            <a href="add-employee.php" class="btn btn-primary" >
                            <i class="fa-solid fa-circle-plus fa-xl"></i> Add New Employee
                            </a>
   
                    <br><br>

          <div class="container">
          <div class="row justify-content-center" >            
            <div class="table-responsive" >               
                    <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; width: 100% ">
                      <table class="table table-striped mb-0">
                        <thead style="background-color: #0275d8; ">
                          <tr>
                            <center>
                              <th class="text-center" scope="col">ID</th>
                              <th class="text-center" scope="col">Username</th>
                              <th class="text-center" scope="col">Password</th>
                              <th class="text-center" scope="col">FirstName</th>
                              <th class="text-center" scope="col">LastName</th>
                              <th class="text-center" scope="col" style="width: 100px;">Actions</th>
                            </center>
                          </tr> 
                        </thead>
                        <tbody>
                          <?php
                          require_once('connections/pdo.php');
                          try {
                            $stmt = $conn->prepare("SELECT * FROM emp_tbl"); // Change this to your table name 'emp_tbl'
                            $stmt->execute();
                            foreach ($stmt->fetchAll() as $row) {
                          ?>
                          <tr>
                            <td class="text-center"><?php echo $row['ID']; ?></td>
                            <td class="text-center"><?php echo $row['Username']; ?></td>
                            <td class="text-center"><?php echo $row['Password']; ?></td>
                            <td class="text-center"><?php echo $row['FirstName']; ?></td>
                            <td class="text-center"><?php echo $row['LastName']; ?></td>
                            <td class="text-center">
                               <a href="#" onclick="showConfirmation('<?php echo $row['ID']; ?>')" style="color:red; ma ">
                              Delete <i class="fa-sharp fa-solid fa-trash fa-xl"></i></i>
                                </a>
                                <div style="padding:5px;"></div>
                                
                              <a href="edit-employee.php?ID=<?php echo $row['ID']; ?>" >
                              Edit <i class="fa-solid fa-pen-to-square fa-xl"></i>   
                              </a>
                            
                             

                                <script>
                                function showConfirmation(ID) {
                                  Swal.fire({
                                    title: 'Are you sure?',
                                    text: 'You are about to delete this record!',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes, delete it!',
                                    cancelButtonText: 'No, cancel',
                                  }).then((result) => {
                                    if (result.isConfirmed) {
                                      window.location.href = 'deletesyntax_employee.php?ID=' + ID + '&confirm_delete=yes';
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
