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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="active_link.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(120, 219, 255, 0.2) 0%, transparent 50%);
            z-index: -1;
        }

        .login-container {
            width: 100%;
            max-width: 800px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 480px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
            margin-top: 80px;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-image {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
        }

        .login-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('images/repair.jpg') center/cover;
            opacity: 0.15;
            z-index: 1;
        }

        .login-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(30, 60, 114, 0.8) 0%, 
                rgba(42, 82, 152, 0.7) 50%, 
                rgba(102, 126, 234, 0.6) 100%);
            z-index: 2;
        }

        .image-content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: white;
        }

        .image-content h1 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 16px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .image-content p {
            font-size: 1rem;
            line-height: 1.5;
            opacity: 0.9;
            max-width: 240px;
            margin: 0 auto;
        }

        .login-form {
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            
        }

        .form-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        .logo i {
            font-size: 20px;
            color: white;
        }

        .form-header h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .form-header p {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 8px;
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 500;
            background: #f9fafb;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-label {
            position: absolute;
            top: 14px;
            left: 16px;
            color: #9ca3af;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            pointer-events: none;
            background: none;
            padding: 0 6px;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -8px;
            left: 12px;
            font-size: 11px;
            color: #667eea;
            font-weight: 600;
            background: #f9fafb;
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .form-links {
            text-align: center;
            margin-top: 24px;
        }

        .form-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
            display: inline-block;
            margin: 4px 0;
        }

        .form-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 24px 0;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
            z-index: 1;
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: #9ca3af;
            font-size: 13px;
            position: relative;
            z-index: 2;
            font-weight: 500;
        }

        .footer-links {
            text-align: center;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
        }

        .footer-links a {
            color: #9ca3af;
            text-decoration: none;
            font-size: 12px;
            margin: 0 12px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #667eea;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 320px;
                margin: 20px;
            }
            
            .login-image {
                display: none;
            }
            
            .login-form {
                padding: 32px 24px;
            }
            
            .form-header h2 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .login-container {
                margin: 10px;
                border-radius: 16px;
            }
            
            .login-form {
                padding: 30px 20px;
            }
        }

        /* Loading Animation */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
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

    <div class="login-container">
        <!-- Left Side - Image Section -->
        <div class="login-image">
            <div class="image-content">
                <h1>Welcome Back!</h1>
                <p>Sign in to your account and discover the best battery solutions for your needs. Join thousands of satisfied customers who trust our quality products.</p>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-form">
            <div class="form-header">
                <div class="logo">
                    <i class="fas fa-battery-full"></i>
                </div>
                <h2>BatteryShop</h2>
                <p>Sign in to your account</p>
            </div>

            <form method="POST" id="loginForm">
                <div class="form-group">
                    <input type="text" name="Username" id="username" class="form-control" required
                        value="<?php echo isset($Username) ? $Username : ''; ?>" placeholder=" " />
                    <label class="form-label" for="username">Username</label>
                </div>

                <div class="form-group">
                    <input type="password" name="Password" id="password" class="form-control" required placeholder=" " />
                    <label class="form-label" for="password">Password</label>
                </div>

                <button class="login-btn" type="submit" id="loginBtn">
                    Sign In
                </button>
            </form>

            <div class="form-links">
                <a href="#!" class="d-block mb-2">Forgot your password?</a>
                <div class="divider">
                    <span>Don't have an account?</span>
                </div>
                <a href="signup.php" class="d-block mb-3">Create new account</a>
            </div>

            <div class="footer-links">
                <a href="#!">Terms of use</a>
                <a href="#!">Privacy policy</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            const inputs = document.querySelectorAll('.form-control');

            // Add floating label effect
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    if (!this.value) {
                        this.parentElement.classList.remove('focused');
                    }
                });

                // Check if input has value on page load
                if (input.value) {
                    input.parentElement.classList.add('focused');
                }
            });

            // Add loading animation on form submit
            form.addEventListener('submit', function(e) {
                loginBtn.classList.add('btn-loading');
                loginBtn.disabled = true;
                
                // Re-enable button after 3 seconds (in case of error)
                setTimeout(() => {
                    loginBtn.classList.remove('btn-loading');
                    loginBtn.disabled = false;
                }, 3000);
            });

            // Add input validation feedback
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.style.borderColor = '#10b981';
                    } else {
                        this.style.borderColor = '#e5e7eb';
                    }
                });
            });

            // Add smooth animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            // Observe form elements
            const formElements = document.querySelectorAll('.form-group, .login-btn, .form-links');
            formElements.forEach(el => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                el.style.transition = 'all 0.6s ease';
                observer.observe(el);
            });
        });
    </script>
</body>
</html>