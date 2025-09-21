<div class="container-fluid m-0 p-0 min-vh-100">
<div class="d-flex" id="wrapper" >
    <!-- Sidebar -->
    <div class="border-end bg-light" id="sidebar" >
        <div class="p-4">
            <div class="navigation">
                <h1 class="fs-4">Inventory Management</h1>
                <ul class="list-unstyled">
                    <li>
                        <a href="dashboard.php">
                            <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                            <span class="title">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="PO.php">
                            <span class="icon"><ion-icon name="archive-outline"></ion-icon></span>
                            <span class="title">Purchase Order</span>
                        </a>
                    </li>
                    <li>
                        <a href="stockin.php">
                            <span class="icon"><ion-icon name="battery-charging-outline"></ion-icon></span>
                            <span class="title">Stock In</span>
                        </a>
                    </li>
                    <li>
                        <a href="damage.php">
                            <span class="icon"><ion-icon name="warning-outline"></ion-icon></span>
                            <span class="title">Damage</span>
                        </a>
                    </li>
                    <li>
                        <a href="exchange.php">
                            <span class="icon"><ion-icon name="swap-horizontal-outline"></ion-icon></span>
                            <span class="title">Exchange</span>
                        </a>
                    </li>
                    <li>
                        <a href="?Signout=true">
                            <span class="icon"><ion-icon name="log-out-outline"></ion-icon></span>
                            <span class="title">Sign Out</span>
                        </a>
                    </li> 
                </ul>
            </div>
        </div>
    </div>

    <div id="content" class="flex-grow-1 d-flex flex-column overflow-auto" style="min-height: 100vh; max-width: 100vw;">  
        <nav class="navbar navbar-expand-lg navbar-light  border-bottom toggle">
            <button class="btn" id="sidebarToggle">
                <i id="toggleIcon" class="bi bi-x"></i>
            </button>
        </nav>
