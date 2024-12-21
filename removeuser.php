<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $id = $_POST['identity'];
    if ($id == 1) {
        echo "
            <script>
            alert('Manager cannot be removed');
            window.location = 'removeuser.php';
            </script>
        ";
    } else {
        $store = $_SESSION['storenum'];
        $staff = $_SESSION['staff'];
        $loggedin = $_SESSION['email'];
        $ex = "SELECT * FROM $staff where `ID`='$loggedin'";
        $exe = mysqli_query($conn, $ex);
        $exec = mysqli_fetch_assoc($exe);
        $desig = $exec['designation'];
        $select = "SELECT * FROM $staff WHERE `staffID`= $id";
        $check = mysqli_query($conn, $select);
        if (mysqli_num_rows($check) > 0 && $desig == 'manager') {
            $row = mysqli_fetch_assoc($check);
            $email = $row['ID'];
            $delquery = "DELETE FROM $staff WHERE `staffID`=$id";
            $result = mysqli_query($conn, $delquery);
            $delquery = "DELETE FROM $store WHERE `emailID`= '$email'";
            $result = mysqli_query($conn, $delquery);
            header("Location: dashboard.php");
            echo "<script>alert('Team member removed')</script>";
        } elseif (mysqli_num_rows($check) == 0) {
            echo "<script>alert('No user found')</script>";
        } else {
            echo "<script>
            alert('You don't have access');
            window.location = 'dashboard.php';
            </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>remove user</title>
    <link rel="stylesheet" href="css/remove.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <img src="src/logo.png" alt="Logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="stocks.php">Stocks and Inventory</a>
        <a href="products.php">Manage products</a>
        <div class="sidebar-drop">
            <a href="" class="active">Manage users</a>
            <div class="sidebar-dropdown">
                <a href='adduser.php'>Add user</a>
                <a href='removeuser.php'>Remove user</a>
            </div>
        </div>
        <a href="orders.php">Manage orders</a>
        <a href="billing.php">Billing</a>
        <a href="supplier.php">Supplier</a>
    </div>
    <div class="content">
        <?php
        echo "<div class='header'>
            <div class='welcome'>Welcome, " . $_SESSION['username'] . "<span class='wave'>👋</span></div>
            <div class='user-info'>
                <span>"
            . $_SESSION['username'] . "  <i class='fas fa-caret-down'></i>
                    <div class='dropdown'>
                        <a href='logout.php'>Logout</a>
                    </div>
                </span>
            </div>
        </div>"
            ?>
    </div>
    <div class="content">
        <div class="container">
            <div class="form">
                <h3>Remove user!!</h3>
                <h2>Enter User ID</h2>
                <form method="post" action="">
                    <label for="store-number">User ID</label>
                    <input type="number" id="number" name="identity" required>
                    <button type="submit" class="signin-button">Remove</button>
                </form>
            </div>
        </div>
    </div>
    <footer>
        <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
    </footer>
</body>

</html>