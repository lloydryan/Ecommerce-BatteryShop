<div class="container-fluid m-0 p-0 min-vh-100">
<div class="d-flex" id="wrapper" >
    <!-- Sidebar -->
    <div class="border-end bg-light" id="sidebar" >
        <div class="p-4">
        <div class="navigation">
            <h1 class="fs-4" style="width:300px">Mark's Shop</h1>         
            <ul>
                <li>
                    <a href="dashboard.php">
                        <span class="icon">
                            <ion-icon name="home-outline"></ion-icon>
                        </span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="manage-user.php">
                        <span class="icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </span>
                        <span class="title">Customers</span>
                    </a>
                </li>

                <li>
                    <a href="manage-product.php">
                        <span class="icon">
                            <ion-icon name="battery-charging-outline"></ion-icon>
                        </span>
                        <span class="title">Product</span>
                    </a>
                </li>

                <li>
                    <a href="manage-employee.php">
                        <span class="icon">
                            <ion-icon name="person-circle-outline"></ion-icon>
                        </span>
                        <span class="title">Employee</span>
                    </a>
                </li>

                <li>
                    <a href="profile.php">
                        <span class="icon">
                            <ion-icon name="settings-outline"></ion-icon>
                        </span>
                        <span class="title">Settings</span>
                    </a>
                </li>
                <li>
                    <a href="?signout=true">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    </div>

    <div id="content" class="flex-grow-1 d-flex flex-column overflow-auto" style="min-height: 100vh; max-width: 100vw;">
                    <nav class="navbar navbar-expand-lg navbar-light  border-bottom">
                        <button class="btn btn-primary" id="sidebarToggle">
                        <i id="toggleIcon" class="bi bi-x"></i> <!-- Use bi-list as the initial icon -->
                        </button>
                    </nav>