<?php

include_once ('connections/connection.php');

$success = false;
$user = false;
$lngth = false;

if (isset($_POST['submit'])) {
    $name = $_POST['fullname'];
    $username = $_POST['Username'];
    $pass = $_POST['Password'];
    $secretCode = $_POST['SecretCode']; // Get the value of the Secret Code field

    // Check if the provided secret code matches the predefined secret code
    if ($secretCode === "SECRET") {
        $sql = "INSERT INTO admin_tbl (FullName, Username, Password) VALUES ('$name', '$username', '$pass')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $messages[] = 'Sign Up Successful !';
        }
    } else {
        $message[] = 'Invalid Secret Code. Registration failed!';
    }
}

// header("Location:signup.php?signup=success");




session_start();

if(isset($_POST['login'])){
  $username = $_POST['Username'];
  $pass = $_POST['Password'];
  
  //mysql Query
  $result = mysqli_query($conn,"SELECT * FROM admin_tbl where Username = '$username'");

  //getting Data
  $row = mysqli_fetch_assoc($result);

  //if user Exist
  if(mysqli_num_rows($result) > 0){
    if($pass == $row['Password']){
      $_SESSION["login"] = true;
      $_SESSION["id"] = $row['ID'];
      $_SESSION["FullName"] = $row['FullName'];
      $_SESSION["Username"] = $row['Username']; // store the full name in session
      header("Location: dashboard.php");
      exit();
    }else{
        $message[] = 'Password do not match!';    
    }
  }else{
    $message[] = 'User does not exist!';    
  }     
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

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
  // User is already logged in, redirect to dashboard
  header("Location: dashboard.php");
  exit();
}
?>
  
  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" /> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.all.min.js"></script>
</head>
<body>
     <div class= "container" id ="container">
     <div class="form-container sign-up-container">
    <form method="POST">
        <h1>Create account</h1>
        <div class="social-container">
            <a href="#" class="social"><i class="fa-brands fa-facebook-f"></i></a>
            <a href="#" class="social"><i class="fa-brands fa-google-plus-g"></i></a>
            <a href="#" class="social"><i class="fa-brands fa-instagram"></i></a>
        </div>
        <input type="text" placeholder="Fullname" required autocomplete="off" name="fullname">
        <input type="text" placeholder="Username" required autocomplete="off" name="Username">
        <input type="password" placeholder="Password" required autocomplete="off" name="Password">
        <!-- Add the secret code input field -->
        <input type="text" placeholder="Secret Code" required autocomplete="off" name="SecretCode">
        <button type="submit" name="submit">Register</button>
    </form>
</div>

        <div class="form-container sign-in-container">
        <form method="POST">
              <h1>Sign In</h1>
  
                <div class ="social-container">
                    <a href="#" class="social"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="social"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="social"><i class="fa-brands fa-instagram"></i></i></a>
                </div>
                    <input type="text" placeholder="Username" required autocomplete="off" name="Username"> 
                    <input type="password" placeholder="Password" required autocomplete="off" name="Password">     
                    <button type="submit" name="login">Login</button><br>   
                    <?php
                if(isset($message)){
                    foreach($message as $message){
                        echo '<span class="message">'.$message.'</span>';
                    }
                }
                ?>
            </form>
        </div> 
        <div class="overlay-container">
            <div class="overlay">
                <div class= "overlay-panel overlay-left">
                    <h1>Welcome to</h1>
                    <h2>IcecreamMan</h2>
                    <P>"Gives you the pleasure you've been seeking"</P>
                    <button class="press" id="signIn">Login</button>
                </div>
                <div class= "overlay-panel overlay-right">
                
                    <h1>Admin LogIn</h1>
                    <h2>IcecreamMan</h2>
                    <P>"Gives you the pleasure you've been seeking"</P>
                    <button class="press" id="signUp">Register</button>
                    <br>
                    <?php
                if(isset($messages)){
                    foreach($messages as $messages){
                        echo '<span class="message">'.$messages.'</span>';
                    }
                }
                ?>
                </div>
            </div>
        </div>
        
     </div>
     
     <script>
        const signUpButton = document.getElementById("signUp");
        const signInButton = document.getElementById("signIn");
        const container = document.getElementById("container");

        signUpButton.addEventListener('click',()=>{
            container.classList.add("right-panel-active");
        })

        signInButton.addEventListener('click',()=>{
            container.classList.remove("right-panel-active");
        })
     </script>

</body>
</html>  