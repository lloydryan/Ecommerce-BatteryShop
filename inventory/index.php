<?php
session_start();
include_once('connections/connection.php');

if (isset($_POST['register'])) {
    // Registration Logic
    $name1 = $_POST['FirstName'];
    $name2 = $_POST['LastName'];
    $username = $_POST['Username'];
    $plainPassword = $_POST['Password']; // Use the plain text password
    $secretCode = $_POST['SecretCode'];

    // Check if the provided secret code matches the predefined secret code
    if ($secretCode === "SECRET") { 
        // Use prepared statements for registration
        $stmt = mysqli_prepare($conn, "INSERT INTO emp_tbl (Username, Password, FirstName, LastName) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $username, $plainPassword, $name1, $name2);
        if (mysqli_stmt_execute($stmt)) {
            echo "Sign Up Successful!";
        } else {
            echo "Registration failed!";
        }
    } else {
        echo "Invalid Secret Code. Registration failed!";
    }
}

if (isset($_POST['login'])) {
    // Login Logic
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    // Use prepared statements for login
    $stmt = mysqli_prepare($conn, "SELECT ID, Username, Password, FirstName, LastName FROM emp_tbl WHERE Username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id, $dbUsername, $dbPassword, $firstName, $lastName);
        mysqli_stmt_fetch($stmt);

        // Compare plain text passwords
        if ($password === $dbPassword) {
            $_SESSION["Login"] = true;
            $_SESSION["ID"] = $id;
            $_SESSION["FirstName"] = $firstName;
            $_SESSION["LastName"] = $lastName;
            $_SESSION["Username"] = $dbUsername;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Password does not match";
        }
    } else {
        echo "User does not exist!";
    }

    mysqli_stmt_close($stmt);
}


if (isset($_GET['Signout'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: index.php");
    exit;
}

if (isset($_SESSION['Login']) && $_SESSION['Login'] === true) {
    // User is already logged in, redirect to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Login and Registration</title>
    <style>
        label {
            color: #fff;
            font-size: 2rem;
            justify-content: center;
            display: flex;
            font-weight: bold;
            cursor: pointer;
            transition: .5s ease-in-out;
        }
        .index{
            background-image: url('1.jpg');
            position: relative;
            background-size: cover;
            height: 100vh;
            backdrop-filter: blur(5px);
        }
        .btn {
            outline: 0;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
            background: #40B3A2;
            min-width: 200px;
            border: 0;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
            box-sizing: border-box;
            padding: 16px 20px;
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            overflow: hidden;
            cursor: pointer;
            }

            .btn:hover {
            opacity: .95;
            }

            .btn .animation {
            border-radius: 100%;
            animation: ripple 0.6s linear infinite;
            }

            @keyframes ripple {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.1), 0 0 0 20px rgba(255, 255, 255, 0.1), 0 0 0 40px rgba(255, 255, 255, 0.1), 0 0 0 60px rgba(255, 255, 255, 0.1);
            }

            100% {
                box-shadow: 0 0 0 20px rgba(255, 255, 255, 0.1), 0 0 0 40px rgba(255, 255, 255, 0.1), 0 0 0 60px rgba(255, 255, 255, 0.1), 0 0 0 80px rgba(255, 255, 255, 0);
            }
            }


    </style>
</head>
<body class="index" >
<div class="container" >
    <div class="row h-100 justify-content-center align-items-center" style="padding-top: 50px">
        <div class="main">
            <input type="checkbox" id="chk" aria-hidden="true">

            <div class="login">
                <form class="form" id="login-form" method="POST">
                <div class="section-center">
                    <div class="section-path">
                        <div class="globe">
                        <div class="wrapper">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        </div>
                    </div>
                    </div>
                    <label for="chk" aria-hidden="true">Log in</label>
                    <input class="input" type="text" name="Username" placeholder="Username" required="">
                    <input class="input" type="password" name="Password" placeholder="Password" required="">
                    <button  type="submit" name="login" class="btn"><i class="animation"></i>LOG IN<i class="animation"></i></button>
                </form>
            </div>

            <div class="register">
                <form class="form" id="register-form" method="POST">
                    <label for="chk" aria-hidden="true">Register</label>
                    <input class="input" type="text" name="FirstName" placeholder="First Name" required="">
                    <input class="input" type="text" name="LastName" placeholder="Last Name" required="">
                    <input class="input" type="text" name="Username" placeholder="Username" required="">
                    <input class="input" type="password" name="Password" placeholder="Password" required="">
                    <input class="input" type="text" name="SecretCode" placeholder="Secret Code" required="">
                    <button  type="submit" name="register"class="btn"><i class="animation"></i>REGISTER<i class="animation"></i></button>                    
                </form>
            </div>
        </div>
    </div>
</div>


</body>
</html>
