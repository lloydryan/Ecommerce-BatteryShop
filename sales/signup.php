<?php
include_once('connections/connection.php');
session_start();

        $success = false;
        $user = false;
$invalid = false;
        
          if(isset($_POST['signup'])){
          $fname = $_POST['firstname'];
          $lname = $_POST['lastname'];  
    $email = $_POST['email'];
    $ConNo = $_POST['ConNum'];
    $address1 = $_POST['address'];
          $username = $_POST['username'];
          $pass = $_POST['password'];
          $conpass = $_POST['ConPassword'];

    // Check if username already exists
    $stmt = $conn->prepare("SELECT * FROM customer_tbl WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

          if ($result->num_rows > 0) {
              $user = true;
    } else {
              if($pass === $conpass){
            $sql = "INSERT INTO customer_tbl (first_name, last_name, email, ConNo, Address1, Username, Password) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssss", $fname, $lname, $email, $ConNo, $address1, $username, $pass);
            
            if($stmt->execute()){
                  $success = true;
              }
        } else {
            $invalid = true;
        }
    }
    $stmt->close();
      }   
      ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - BatteryShop</title>
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
            background: linear-gradient(135deg, #000957 0%, #344CB7 50%, #577BC1 100%);
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

        .signup-container {
            margin-top: 80px;
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.2);
            overflow: hidden;
            animation: slideUp 0.8s ease-out;
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

        .signup-image {
            background: linear-gradient(135deg, #000957 0%, #344CB7 50%, #577BC1 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
        }

        .signup-image::before {
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

        .signup-image::after {
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
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .image-content p {
            font-size: 1.1rem;
            line-height: 1.6;
            opacity: 0.9;
            max-width: 280px;
            margin: 0 auto;
        }

        .signup-form {
            
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-height: 600px;
            overflow-y: auto;
        }

        .form-header {
            text-align: center;
            margin-bottom: 15px;
            margin-top: 230px;
        }

        .logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #000957, #344CB7, #577BC1);
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 6px 20px rgba(0, 9, 87, 0.25);
        }

        .logo i {
            font-size: 20px;
            color: white;
        }

        .form-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        .form-header p {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            
        }

        .form-group {
            margin-bottom: 8px;
            position: relative;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            background: #f9fafb;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-control:focus {
            border-color: #344CB7;
            background: white;
            box-shadow: 0 0 0 4px rgba(52, 76, 183, 0.12);
            transform: translateY(-2px);
        }

        .form-label {
            position: absolute;
            top: 12px;
            left: 16px;
            color: #9ca3af;
            font-size: 14px;
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
            color: #344CB7;
            font-weight: 600;
            background: #f9fafb;
        }

        .signup-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #000957 0%, #344CB7 50%, #577BC1 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(0, 9, 87, 0.25);
            position: relative;
            overflow: hidden;
            margin-top: 8px;
        }

        .signup-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .signup-btn:hover::before {
            left: 100%;
        }

        .signup-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }

        .form-links {
            text-align: center;
            margin-top: 20px;
        }

        .form-links a {
            color: #344CB7;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: color 0.3s ease;
            display: inline-block;
            margin: 4px 0;
        }

        .form-links a:hover {
            color: #577BC1;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
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
            margin-top: 16px;
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
            color: #344CB7;
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .signup-container {
                grid-template-columns: 1fr;
                max-width: 400px;
                margin: 20px;
                min-height: auto;
            }
            
            .signup-image {
                display: none;
            }
            
            .signup-form {
                padding: 30px 24px;
                max-height: none;
            }
            
            .form-header h2 {
                font-size: 1.6rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .signup-container {
                margin: 10px;
                border-radius: 16px;
            }
            
            .signup-form {
                padding: 24px 16px;
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

        /* Custom scrollbar for form */
        .signup-form::-webkit-scrollbar {
            width: 6px;
        }

        .signup-form::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .signup-form::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .signup-form::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body>
    <?php include_once('header-footer/header.php') ?>
    <?php
   if ($success == true) {
    echo '<script>
        Swal.fire({
                title: "Sign Up Successful!",
                text: "Your account has been created successfully. Click OK to go to the login page.",
            icon: "success",
            confirmButtonText: "OK",
        }).then(function(result) {
            if (result.isConfirmed) {
                    window.location.href = "login.php";
            }
        });
    </script>';
}
    
    if ($user == true) {
        echo '<script>
            Swal.fire({
                title: "Username Already Exists!",
                text: "Please choose a different username.",
                icon: "error",
                confirmButtonText: "OK",
            });
        </script>';
    }
    
    if ($invalid == true) {
        echo '<script>
            Swal.fire({
                title: "Password Mismatch!",
                text: "Passwords do not match. Please try again.",
                icon: "error",
                confirmButtonText: "OK",
        });
    </script>';
}
    ?>
    
    <div class="signup-container">
        <!-- Left Side - Image Section -->
        <div class="signup-image">
            <div class="image-content">
                <h1>Join Us Today!</h1>
                <p>Create your account and start exploring our premium battery collection. Get access to exclusive deals and fast delivery.</p>
                        </div>
                                            </div>
        
        <!-- Right Side - Signup Form -->
        <div class="signup-form">
            <div class="form-header">
                <div class="logo">
                    <i class="fas fa-battery-full"></i>
                                            </div>
                <h2>BatteryShop</h2>
                <p>Create your account</p>
                                        </div>

            <form method="POST" id="signupForm">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="firstname" id="firstname" class="form-control" required placeholder=" " />
                        <label class="form-label" for="firstname">First Name</label>
                                        </div>
                    <div class="form-group">
                        <input type="text" name="lastname" id="lastname" class="form-control" required placeholder=" " />
                        <label class="form-label" for="lastname">Last Name</label>
                                        </div>
                                        </div>

                <div class="form-row">
                    <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control" required placeholder=" " />
                        <label class="form-label" for="email">Email</label>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="ConNum" id="ConNum" class="form-control" required placeholder=" " />
                        <label class="form-label" for="ConNum">Contact Number</label>
                                    </div> 
                                        </div>

                <div class="form-group full-width">
                    <input type="text" name="address" id="address" class="form-control" required placeholder=" " />
                    <label class="form-label" for="address">Address</label>
                                        </div>
                                        
                <div class="form-group full-width">
                    <input type="text" name="username" id="username" class="form-control" required placeholder=" " />
                    <label class="form-label" for="username">Username</label>
                                        </div>

                <div class="form-row">
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" required placeholder=" " />
                        <label class="form-label" for="password">Password</label>
                                        </div>
                    <div class="form-group">
                        <input type="password" name="ConPassword" id="ConPassword" class="form-control" required placeholder=" " />
                        <label class="form-label" for="ConPassword">Confirm Password</label>
                                       </div>
                                    </div>

                <button class="signup-btn" type="submit" name="signup" id="signupBtn">
                    Create Account
                </button>
            </form>

            <div class="form-links">
                <div class="divider">
                    <span>Already have an account?</span>
                </div>
                <a href="login.php" class="d-block mb-3">Sign in here</a>
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
            const form = document.getElementById('signupForm');
            const signupBtn = document.getElementById('signupBtn');
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
                signupBtn.classList.add('btn-loading');
                signupBtn.disabled = true;
                
                // Re-enable button after 3 seconds (in case of error)
                setTimeout(() => {
                    signupBtn.classList.remove('btn-loading');
                    signupBtn.disabled = false;
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

            // Password confirmation validation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('ConPassword');
            
            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.style.borderColor = '#ef4444';
                } else {
                    confirmPassword.style.borderColor = '#10b981';
                }
            }
            
            password.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', validatePassword);

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
            const formElements = document.querySelectorAll('.form-group, .signup-btn, .form-links');
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