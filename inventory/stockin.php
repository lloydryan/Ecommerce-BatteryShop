<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== true) {
    header("Location: index.php");
    exit();
}

// You can now access user information like:
$userID = $_SESSION['ID'];
$firstName = $_SESSION['FirstName'];
$lastName = $_SESSION['LastName'];
$username = $_SESSION['Username'];

// Handle sign-out
if (isset($_GET['Signout'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="node_modules/ionicons/dist/css/ionicons.min.css">
    <link rel="stylesheet" href="css/style.css">
 
    <title>STOCK IN</title>
</head>
<body>
<?php

        // Include the database connection file
        require_once('connections/pdo.php');

        // Initialize error and success messages
        $error_message = "";
        $successMessage = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedProducts = isset($_POST['selectedProducts']) ? $_POST['selectedProducts'] : [];

            // Store selected products and transaction description in session for processing
            $_SESSION['selectedProducts'] = $selectedProducts;

            // Redirect to a page for entering serial numbers
            header("Location: stockin_details.php");
            exit();
        }

        // Fetch and display products
        try {
            $stmt = $conn->prepare("SELECT product_name, product_image, product_type, product_desc, product_Id, available_item FROM product_tbl");
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
 ?>
<?php
include_once('navbar/navbar.php');
?>

<div class="container-fluid">
    <h1>Select Item for Stock In</h1><br>
        <div class="container" >
            <form method="POST">
                <div class="row justify-content-center" >                      
                    <div class="table-responsive" >               
                        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px; width: 100% ">
                            <table class="table table-striped mb-0">
                                <thead >
                                <tr>
                                    <th>Product Image</th>
                                    <th>Product Name</th>
                                    <th>Product Type</th>
                                    <th>Product Description</th>
                                    <th>Available Item</th>
                                    <th>Select Item</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // Each product row in the table
                                    $availableItem = $row["available_item"];
                                    $status = ($availableItem < 5) ? '<span class="text-danger"><b>LOW</b></span>' : '<span class="text-success"><b>High</b></span>';
                                    ?>
                                    <tr>
                                        <td>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>"
                                                alt="Product Image" class="product-image">
                                        </td>
                                        <td><?php echo $row['product_name']; ?></td>
                                        <td><?php echo $row['product_type']; ?></td>
                                        <td><?php echo $row['product_desc']; ?></td>
                                        <td><?php echo $availableItem . " " . $status; ?></td>
                                        <td>
                                            <label>
                                                <input type="checkbox" name="selectedProducts[]"
                                                    value="<?php echo $row['product_Id']; ?>">
                                                Select
                                            </label>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
                    <div class="container">
                        <div class="row">
                    <!-- Content on the left (col-md-8) -->
                            <div class="col-md-10">

                            </div>
                    <!-- Div on the right (col-md-4) -->
                            <div class="col-md-2">
                            <button class="next" id="nextBtn" type="submit">
                                Next
                            <svg fill="currentColor" viewBox="0 0 24 24" class="icon">
                            <path clip-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm4.28 10.28a.75.75 0 000-1.06l-3-3a.75.75 0 10-1.06 1.06l1.72 1.72H8.25a.75.75 0 000 1.5h5.69l-1.72 1.72a.75.75 0 101.06 1.06l3-3z" fill-rule="evenodd"></path>
                            </svg>
                            </button>                            
                            </div>
                        </div>
                    </div>



                    </div>
                </form>

            </div>
        </div>
    </div>

<?php
// Close the database connection
$conn = null;
?>

<script src="css/main.js"></script>
<script>
// Enable/disable Next button based on checkbox selection
const checkboxes = document.querySelectorAll("input[name='selectedProducts[]']");
const nextBtn = document.getElementById("nextBtn");

function toggleNextButton() {
    const checked = document.querySelectorAll("input[name='selectedProducts[]']:checked").length > 0;
    nextBtn.disabled = !checked;
}

checkboxes.forEach(cb => cb.addEventListener("change", toggleNextButton));
</script>
       
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    </div>
</div>
</body>
</html>
