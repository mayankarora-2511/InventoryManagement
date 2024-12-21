<?php
 session_start();
 if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $fname = $_POST["first-name"];
    $lname = $_POST["last-name"];
    $username = $fname." ".$lname;
    $password = $_POST["password"];
    $confirm = $_POST["confirm-password"];
    $designation = $_POST["role"];
    $storenum = $_SESSION['store'];
    $em = $designation."@".$storenum.".com";
    if ($designation == 'manager'){
        echo "
        <script>
            alert('New manager cannot be added, the original credentials need to be passed on');
            window.location = 'adduser.php';
        </script>
        ";
    }
    $staff = $_SESSION['staff'];
    $store = $_SESSION['storenum'];
    $loggedin = $_SESSION['email'];
    $existuser = "SELECT * FROM $store WHERE `emailID` = '$em';";
    $result = mysqli_query($conn, $existuser);
    $numExistRows = mysqli_num_rows($result);
    if ($numExistRows > 0) {
        echo"<script> alert('Staff already exists') </script>";
    }else{
        if ($password == $confirm) {
            $password = password_hash($password,PASSWORD_DEFAULT);
            $ex = "SELECT * FROM $staff where `ID`='$loggedin'";
            $exe = mysqli_query($conn,$ex);
            $exec = mysqli_fetch_assoc($exe);
            $desig = $exec['designation'];
            if ($desig == 'manager'){
                $insertuser = "INSERT INTO $staff (sname,designation,ID) VALUES ('$username','$designation','$em');";
                $insertresult = mysqli_query($conn, $insertuser);
                $insertstore = "INSERT INTO $store (username,emailID,userpassword) VALUES ('$username','$em','$password');";
                $insertion = mysqli_query($conn, $insertstore);
                echo "
                    <script>
                    alert('New user added' "  . $em . " is the user ID');
                    window.location = './dashboard.php';
                    </script>
                ";
            }
            
        }elseif($password != $confirm){
            echo"<script>alert('password and confirm-password donot match')</script>";
        }else{
            echo"<script>
            alert('you don't have access');
            window.location = './dashboard.php';
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
    <title>add user</title>
    <link rel="stylesheet" href="css/adduser.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <img src="src/logo.png" alt="Logo">
        <a href="dashboard.php" class="active">Dashboard</a>
        <a href="stocks.php">Stocks and Inventory</a>
        <a href="products.php">Manage products</a>
        <div class="sidebar-drop">
        <a href="" class="manage">Manage users</a>
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
            <div class='welcome'>Welcome, "  .  $_SESSION['username']  .  "<span class='wave'>👋</span></div>
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
        <div class="container">
        <div class="form">
            <h3>Add user</h3>
            <form method="POST" action=''>
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" required>

                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" required>

                <label for="role">Role</label>
                <input type="text" id="role" placeholder="user-role" name="role" required>

                <label for="password">Password</label>
                <input type="password" id="password" placeholder="*********" name="password" required>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" placeholder="*********" name="confirm-password" required>

                

                <button type="submit" class="submit-button" id="submit" name="submit">submit</button>
            </form>
        </div>
    

        </div>
    
    <footer>
        <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
    </footer>
    </div>
    </div>
</body>
</html>