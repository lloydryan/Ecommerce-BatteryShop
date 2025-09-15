<?php
include_once "connections/pdo.php";
?>
<style>
  .navbar {
    background: rgba(0, 0, 30, 0.9); /* Dark navy with slight transparency */
    padding: 10px 20px;
  }
  
  .navbar-brand img {
    width: 140px;
    height: 40px;
  }

  .nav-link {
    color: white !important;
    font-weight: 500;
    transition: 0.3s;
  }

  .nav-link:hover {
    color: #FF9333 !important;
    transform: scale(1.05);
  }

  .navbar-toggler {
    border: none;
  }

  .navbar-toggler:focus {
    box-shadow: none;
  }

  .dropdown-menu {
    background: #1b1b2f;
    border-radius: 10px;
    padding: 10px;
  }

  .dropdown-item {
    color: white !important;
    transition: 0.3s;
  }

  .dropdown-item:hover {
    background: #FF9333 !important;
    color: black !important;
  }

  .cart-badge {
    background: #FF9333;
    color: white;
    padding: 2px 6px;
    border-radius: 10px;
    font-size: 14px;
  }
</style>

<nav class="navbar fixed-top navbar-expand-lg">
  <div class="container-fluid">
    <a class="navbar-brand" href="home.php">
      <img src="header-footer/logo.png">
    </a>
    <button class="navbar-toggler bg-light p-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
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

        <?php if (isset($_SESSION['Username'])) { ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown">
              <?php echo $_SESSION['Username']; ?>
              <i class="fa-solid fa-user-circle" style="margin-left: 10px;"></i>
            </a>
            <ul class="dropdown-menu">
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
              <i class="fa-solid fa-user fa-2x"></i>
            </a>
            <ul class="dropdown-menu">
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
