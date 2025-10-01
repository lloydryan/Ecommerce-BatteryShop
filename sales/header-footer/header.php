<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once "connections/pdo.php";
?>
<style>
  /* Modern Navbar Styles */
  .navbar {
    background: rgba(30, 60, 114, 0.85);
    backdrop-filter: blur(15px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: 12px 0;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  }

  .navbar.scrolled {
    background: rgba(30, 60, 114, 0.95);
    backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
  }
  
  .navbar-brand {
    transition: transform 0.3s ease;
  }

  .navbar-brand:hover {
    transform: scale(1.05);
  }
  
  .navbar-brand img {
    width: 160px;
    height: 45px;
    filter: brightness(1.1);
    transition: filter 0.3s ease;
  }

  .navbar-brand img:hover {
    filter: brightness(1.3);
  }

  .nav-link {
    color: rgba(255, 255, 255, 0.9) !important;
    font-weight: 500;
    font-size: 15px;
    padding: 8px 16px !important;
    margin: 0 4px;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .nav-link::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
  }

  .nav-link:hover::before {
    left: 100%;
  }

  .nav-link:hover {
    color: #ffffff !important;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  }

  .navbar-toggler {
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.3s ease;
  }

  .navbar-toggler:hover {
    border-color: rgba(255, 255, 255, 0.6);
    background: rgba(255, 255, 255, 0.1);
  }

  .navbar-toggler:focus {
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
  }

  .navbar-toggler-icon {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
  }

  .dropdown-menu {
    background: rgba(30, 60, 114, 0.95);
    backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 8px 0;
    margin-top: 8px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    animation: slideDown 0.3s ease;
  }

  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .dropdown-item {
    color: rgba(255, 255, 255, 0.9) !important;
    padding: 10px 20px;
    transition: all 0.3s ease;
    border-radius: 6px;
    margin: 2px 8px;
    font-size: 14px;
  }

  .dropdown-item:hover {
    background: linear-gradient(135deg, #FF6B35, #F7931E) !important;
    color: white !important;
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
  }

  .dropdown-divider {
    border-color: rgba(255, 255, 255, 0.1);
    margin: 8px 0;
  }

  .cart-badge {
    background: linear-gradient(135deg, #FF6B35, #F7931E);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
    animation: pulse 2s infinite;
  }

  @keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }

  .cart-badge:hover {
    animation: none;
    transform: scale(1.1);
  }

  /* User dropdown specific styles */
  .nav-item.dropdown .nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 25px;
    padding: 8px 16px !important;
    transition: all 0.3s ease;
  }

  .nav-item.dropdown .nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-1px);
  }

  .nav-item.dropdown .nav-link i {
    font-size: 18px;
    transition: transform 0.3s ease;
  }

  .nav-item.dropdown:hover .nav-link i {
    transform: scale(1.1);
  }

  /* Right-aligned dropdown menu */
  .dropdown-menu-end {
    right: 0;
    left: auto;
  }

  /* User menu specific styling */
  .navbar-nav:last-child {
    margin-left: auto;
  }

  .navbar-nav:last-child .nav-item {
    margin-left: 10px;
  }

  /* Responsive improvements */
  @media (max-width: 991px) {
    .navbar-collapse {
      background: rgba(30, 60, 114, 0.9);
      backdrop-filter: blur(20px);
      border-radius: 12px;
      margin-top: 10px;
      padding: 15px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-link {
      margin: 2px 0;
      padding: 12px 16px !important;
    }

    .dropdown-menu {
      position: static !important;
      transform: none !important;
      box-shadow: none;
      background: rgba(0, 0, 0, 0.1);
      border: none;
      margin: 0;
    }
  }

  /* Smooth scrolling for better UX */
  html {
    scroll-behavior: smooth;
  }

  /* Active link highlighting */
  .nav-link.active {
    background: rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    font-weight: 600;
  }

  /* Loading animation for cart badge */
  .cart-badge.loading {
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const navbar = document.querySelector('.navbar');
  const navLinks = document.querySelectorAll('.nav-link');
  
  // Add scroll effect to navbar
  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // Add active class to current page link
  const currentPage = window.location.pathname.split('/').pop();
  navLinks.forEach(link => {
    const href = link.getAttribute('href');
    if (href && href.includes(currentPage)) {
      link.classList.add('active');
    }
  });

  // Add smooth hover effects
  navLinks.forEach(link => {
    link.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-2px)';
    });
    
    link.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0)';
    });
  });

  // Add loading effect to cart badge when updating
  const cartBadge = document.querySelector('.cart-badge');
  if (cartBadge) {
    cartBadge.addEventListener('click', function() {
      this.classList.add('loading');
      setTimeout(() => {
        this.classList.remove('loading');
      }, 1000);
    });
  }

  // Mobile menu improvements
  const navbarToggler = document.querySelector('.navbar-toggler');
  const navbarCollapse = document.querySelector('.navbar-collapse');
  
  if (navbarToggler && navbarCollapse) {
    navbarToggler.addEventListener('click', function() {
      navbarCollapse.classList.toggle('show');
    });

    // Close mobile menu when clicking on a link
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (window.innerWidth < 992) {
          navbarCollapse.classList.remove('show');
        }
      });
    });
  }
});
</script>

<nav class="navbar fixed-top navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">
      <img src="header-footer/logo.png">
    </a>
    <button class="navbar-toggler bg-light p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Main Navigation Links (Left Side) -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="home.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="product.php">Find Battery</a>
        </li>
        <li class="nav-item">
          <?php
          $count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
          ?>
          <a class="nav-link" href="shopingbasket.php">
            Cart <span class="cart-badge"><?php echo $count; ?></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
      </ul>

      <!-- User Menu (Right Side) -->
      <ul class="navbar-nav">
        <?php if (isset($_SESSION['Username'])) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-user-circle me-2"></i>
              <?php echo $_SESSION['Username']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="request.php">Request for exchange</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="orders.php">Orders <i class="fa-thin fa-table-list"></i></a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="?logout=true">Logout <i class="fa-solid fa-right-to-bracket"></i></a></li>
            </ul>
          </li>
        <?php } else { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-user me-2"></i>
              Account
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="signup.php">SignUp <i class="fa-solid fa-user-plus"></i></a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="login.php">Login <i class="fa-solid fa-right-to-bracket"></i></a></li>
            </ul>
          </li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
