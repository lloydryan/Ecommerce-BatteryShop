<?php
include_once('connections/connection.php');
session_start();

$success = false;
$invalid=false;

if (isset($_SESSION['ID'])) {
  $ID = $_SESSION['ID'];
  $Username = $_SESSION['Username'];

  $stmt = $conn->prepare("SELECT * FROM customer_tbl WHERE Username=? AND Password=? AND Status != 'Block'");
  $stmt->bind_param("ss", $Username, $Password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if ($row['Status'] == 'Block') {
      // user is blocked
      $block = true;
    } else {
      // user is not blocked
      $_SESSION['ID'] = $ID;
      $_SESSION['Username'] = $Username;
      $success = true;
    }
  }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $Username = $_POST['Username'];
  $Password = $_POST['Password'];

  $stmt = $conn->prepare("SELECT * FROM customer_tbl WHERE Username=? AND Password=?");
  $stmt->bind_param("ss", $Username, $Password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if ($row['Status'] == 'Block') {
      // user is blocked
      $block = true;
    } else {
      $_SESSION['ID'] = $row['ID'];
      $_SESSION['Username'] = $row['Username'];
      $success = true;
    }
  } else {
    // login failed
    $invalid = true;
  }

  $stmt->close();
  $conn->close();
}

if (isset($_GET['logout'])) {
  // Unset all session variables
  $_SESSION = array();

  // Destroy the session
  session_destroy();

  // Redirect to login page
  header("Location: home.php");
  exit;
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
          height: 636px;
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
if ($invalid == true) {
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
                            <img src=" images/repair.jpg"
                                alt="login form" class="img-fluid" />
                        </div>
                        <div class="col-md-6 col-lg-7 d-flex align-items-center">
                            <div class="card-body p-4 p-lg-5 text-black">

                                <form method="POST">

                                    <div class="d-flex align-items-center mb-3 pb-1">
                                        <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                                        <span class="h1 fw-bold mb-0">Logo</span>
                                    </div>

                                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                                    <div class="form-outline mb-4">
                                        <input type="text" name="Username" id="form2Example17" class="form-control form-control-lg" required
                                            value="<?php echo isset($Username) ? $Username : ''; ?>" />
                                        <label class="form-label" for="form2Example17">Username</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" name="Password" id="form2Example27" class="form-control form-control-lg" required />
                                        <label class="form-label" for="form2Example27">Password</label>
                                    </div>

                                    <div class="pt-1 mb-4">
                                        <button class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                                    </div>

                                    <a class="small text-muted" href="#!">Forgot password?</a>
                                    <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="signup.php"
                                            style="color: #393f81;">Register here</a></p>
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