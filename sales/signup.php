<?php
include 'connections/connection.php';
?>
  <?php
        $success = false;
        $user = false;
        $invalid =false;
        
          if(isset($_POST['signup'])){
          
          $fname = $_POST['firstname'];
          $lname = $_POST['lastname'];  
          $email= $_POST['email'];
          $ConNo= $_POST['ConNum'];
          $address1 =$_POST['address'];
          $username = $_POST['username'];
          $pass = $_POST['password'];
          $conpass = $_POST['ConPassword'];

          $query = "select * from users_tbl where username = '$username';";
          $result = $conn->query($query);

          if ($result->num_rows > 0) {
              $user = true;
          }
          else{
              if($pass === $conpass){
              $sql = "insert into users_tbl (first_name,last_name,email,ConNo,Address1,Username,Password) values ('$fname','$lname','$email','$ConNo','$address1','$username','$pass');";
              mysqli_query($conn,$sql);
              if($result){
                 
                  $success = true;
              }
            }else{
                 $invalid =true;
              }

        // header("Location:signup.php?signup=success");
      
          }   
      }   
      ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="active_link.js"></script>
    <style>
        /* Updated login styling */
        body {
            background-color: #9A616D;

        }

        .container {
            height: 100vh;
        }

        .card {
            border-radius: 1rem;
        }
        .img-fluid{
          max-width: 100%;
          height: 772px;
        }

        .card img {
            border-radius: 1rem 0 0 1rem;
        }

        .card-body {
            background-color: #fff;
        }

        .card-body h1 {
            color: #ff6219;
        }

        .card-body h5 {
            letter-spacing: 1px;
        }

        .form-label {
            color: #393f81;
        }

        .btn-dark {
            background-color: #005ce6;
        }

        /* End of updated login styling */
    </style>
</head>
<body>
    <?php include_once('header-footer/header.php') ?>

    <?php
   if ($success == true) {
    echo '<script>
        Swal.fire({
            title: "Login Successful!",
            text: "Click OK to go to the home page",
            icon: "success",
            confirmButtonText: "OK",
        }).then(function(result) {
            if (result.isConfirmed) {
                window.location.href = "home.php";
            }
        });
    </script>';
}
    ?>

    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
                <div class="card">  
                    <div class="row g-0">
                        <div class="col-md-6 col-lg-5 d-none d-md-block">
                            <img src="images/repair.jpg" alt="login form" class="img-fluid" />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-sm-6 text-black">
                                <form method="POST">
                                    <div class="d-flex flex-column">
                                        <!-- Left Side -->
                                        <div class="d-flex">
                                            <div class="form-outline mb-4 me-3 w-50">
                                                <input type="text" name="firstname" id="form2Example17" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="form2Example17">First Name</label>
                                            </div>
                                            <div class="form-outline mb-4 w-50">
                                                <input type="text" name="lastname" id="form2Example18" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="form2Example18">Last Name</label>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                        <div class="form-outline mb-4 me-3 w-50">
                                            <input type="email" name="email" id="form2Example19" class="form-control form-control-lg" required/>
                                            <label class="form-label" for="form2Example19">Email</label>
                                        </div>
                                        <div class="form-outline mb-4 w-50">
                                            <input type="text" name="ConNum" id="form2Example20" class="form-control form-control-lg" required/>
                                            <label class="form-label" for="form2Example20">Contact Number</label>
                                        </div>
                                        </div>
                                    </div> 
                                    <div class="d-flex flex-column">
                                        <!-- Right Side -->
                                        <div class="form-outline mb-4">
                                            <input type="text" name="username" id="form2Example22" class="form-control form-control-lg" required/>
                                            <label class="form-label" for="form2Example22">Username</label>
                                        </div>

                                        <div class="form-outline mb-4">
                                            <input type="text" name="address" id="form2Example21" class="form-control form-control-lg" required/>
                                            <label class="form-label" for="form2Example21">Address</label>
                                        </div>
                                        
                                        <div class="d-flex">
                                        <div class="form-outline mb-4 me-3  w-50">
                                            <input type="password" name="password" id="form2Example23" class="form-control form-control-lg" required />
                                            <label class="form-label" for="form2Example23">Password</label>
                                        </div>
                                        <div class="form-outline mb-4 w-50">
                                            <input type="password" name="ConPassword" id="form2Example24" class="form-control form-control-lg" required />
                                            <label class="form-label" for="form2Example24">Confirm Password</label>
                                        </div>
                                       </div>
                                    </div>

                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-dark btn-lg btn-block"name="signup" type="submit">Sign Up</button>
                                    </div>

                                    <a class="small text-muted" href="#!">Forgot password?</a>
                                    <p class="mb-5 pb-lg-2" style="color: #393f81;">Already have an account? <a href="login.php"
                                            style="color: #393f81;">Login here</a></p>
                                    <a href="#!" class="small text-muted">Terms of use.</a>
                                    <a href="#!" class="small text-muted">Privacy policy</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>