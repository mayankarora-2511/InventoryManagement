<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $issue = $_POST['identity'];
    $inventory = $_SESSION['inventory'];
    $email = $_SESSION['email'];
    $orders = $_SESSION['orders'];
    $bill = $_SESSION['bill'];
    $staff = $_SESSION['staff'];
    $warehouse = $_SESSION['warehouse'];
    $store = $_SESSION['store'];
    $num = $_SESSION['number'];
    $man = "SELECT * FROM `$staff` WHERE ID = '$email'";
    $mana = mysqli_query($conn,$man);
    $desig =mysqli_fetch_assoc($mana)['designation'];
    $na = mysqli_fetch_assoc($mana)['sname'];
    $user = $_SESSION['username'];
    if ($desig == 'manager'){
        $del1 = "DELETE FROM StoreNum WHERE StoreNum = '$num'";
        $c1 = mysqli_query($conn, $del1);
        $del2 = "DELETE FROM signups WHERE StoreNum = '$num'";
        $c2 = mysqli_query($conn, $del2);
        if ($c1 and $c2){
            $del3 = "DROP TABLE `$bill`";
            $c3 = mysqli_query($conn,$del3);
            $del3 = "DROP TABLE `$orders`";
            $c3 = mysqli_query($conn,$del3);
            $del3 = "DROP TABLE `$warehouse`";
            $c3 = mysqli_query($conn,$del3);
            $del3 = "DROP TABLE `$inventory`";
            $c3 = mysqli_query($conn,$del3);
            $del3 = "DROP TABLE `$staff`";
            $c3 = mysqli_query($conn,$del3);
            $del3 = "DROP TABLE `$store`";
            $c3 = mysqli_query($conn,$del3);
            $ins = "INSERT INTO ending VALUES ('$user','$num','$issue')";
            $done = mysqli_query($conn,$ins);
            session_unset();
$_SESSION = array();
session_destroy();
            header("Location: contact_us.php");
        }
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remove store</title>
    <link rel="stylesheet" href="css/end.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <img src="src/logo.png" alt="Logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="stocks.php">Stocks and Inventory</a>
        <a href="products.php">Manage products</a>
        <div class="sidebar-drop">
            <a href="">Manage users</a>
            <div class="sidebar-dropdown">
                <a href='adduser.php'>Add user</a>
                <a href='removeuser.php'>Remove user</a>
            </div>
        </div>
        <a href="orders.php">Manage orders</a>
        <a href="billing.php">Billing</a>
        <a href="supplier.php">Supplier</a>
        <a href="#" class="active">End Store</a>
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
        <div class="metrics">
        <div class="container">
            <div class="form">
                <h3>Remove store!!</h3>
                <h2>Enter your issue</h2>
                <form method="post" action="">
                    <label for="store-number">Issue</label><br>
                    <textarea id="number" name="identity" rows="5" required></textarea>
                    <button type="submit" class="signin-button" id = "remove">Remove</button>
                </form>
            </div>
        </div>
            </div>
            

        <footer>
            <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
        </footer>
        </div>
</body>
</html>