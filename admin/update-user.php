<?php
$id = "";
$fname = "";
$address = "";
$lname = "";
$status = "";
$contactNo = ""; // Added for Contact No.
$successmsg = false;
include_once('connections/connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET['updateid'])) {
        header("Location: manage_user.php");
        exit; // Added to prevent further execution
    }
    $id = $_GET['updateid'];
    $sql = "SELECT * FROM users_tbl WHERE ID = $id";
    $result =  $conn->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: manage_user.php");
        exit; // Added to prevent further execution
    }

    $fname = $row['first_name'];
    $lname = $row['last_name'];
    $address = $row['Address1'];
    $username = $row['Username'];
    $contactNo = $row['ConNo']; // Corrected to 'ConNo'
} else {
    $id = $_GET['updateid'];
    $fname = isset($_POST['first_name']) ? $_POST['first_name'] : "";
    $lname = isset($_POST['last_name']) ? $_POST['last_name'] : "";
    $address = isset($_POST['Address1']) ? $_POST['Address1'] : "";
    $username = isset($_POST['Username']) ? $_POST['Username'] : "";
    $status = isset($_POST['Status']) ? $_POST['Status'] : "";
    $contactNo = isset($_POST['ConNo']) ? $_POST['ConNo'] : ""; // Corrected to 'ContactNo'

    do {
        $sql = "UPDATE users_tbl SET first_name='$fname',last_name='$lname',Address1='$address',Username='$username',Status='$status',ConNo='$contactNo' WHERE ID = $id"; // Corrected to 'ConNo'
        $result = $conn->query($sql);
        if ($result) {
            $successmsg = true;
        } else {
            echo "<script>alert('Naay mali baii!');</script>";
        }
    } while (false);
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>UPDATE FORM: user</title>

    <link rel="stylesheet" href="css/update.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
</head>

<body class="b1">

    <div class="container">

        <div class="admin-product-form-container centered">

            <form style="border:1px solid #ccc" method="POST">
                <?php
                if ($successmsg == true) {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="fa-sharp fa-regular fa-badge-check" style="color: #15e553;"></i>Note: </strong> Update Successful!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
                }
                ?>

                <h1>Update Form</h1>
                <p>Please fill in this form to create an account.</p>
                <hr>
                <label for="first_name">First Name</label>
                <input type="text" name="first_name" class="box" placeholder="First Name" required="" autocomplete="off"
                    value="<?php echo $fname; ?>">

                <label for="last_name">Last Name</label>
                <input type="text" name="last_name" class="box" placeholder="Last Name" required="" autocomplete="off"
                    value="<?php echo $lname; ?>">

                <label for="Address">Address</label>
                <input type="text" name="Address" class="box" placeholder="Address" required="" autocomplete="off"
                    value="<?php echo $address; ?>">

                <label for="Username">Username</label>
                <input type="text" name="Username" class="box" placeholder="Username" required="" autocomplete="off"
                    value="<?php echo $username; ?>"> <br>

                <!-- New Contact No. field -->
                <label for="ConNo">Contact No.</label>
                <input type="number" name="ConNo" class="box" placeholder="Contact Number" required=""
                    autocomplete="off" value="<?php echo $contactNo; ?>">

                <label>Status</label>
                <select class="box" name="Status" id="Status" class="form-select"
                    aria-label="Default select example">
                    <option selected disabled>Status</option>
                    <option value="Active" selected>Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Block">Block</option>
                </select> <br>

                <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>

                <button type="text" class="btn">Update</button>
                <a href="manage-user.php" style="text-decoration: none;"><button type="button"
                        class="btn">Back</button></a>
            </form>
        </div>

    </div>

    <style>


    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N"
        crossorigin="anonymous"></script>
</body>

</html>
