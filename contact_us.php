<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'dbconnect.php';
        $fname = $_POST["first-name"];
        $lname = $_POST["last-name"];
        $username = $fname." ".$lname;
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirm = $_POST["confirm-password"];
        $number = $_POST['store-number'];
        $desig = "manager";
        
        // Check if the email or store number already exists
        $existSql = "SELECT * FROM `signups` WHERE `emailID` = '$email'";
        $existstore = "SELECT * FROM `StoreNum` WHERE `StoreNum` = '$number'";
        $result = mysqli_query($conn, $existSql);
        $resultnum = mysqli_query($conn, $existstore);
        $numExistRows = mysqli_num_rows($result);
        $numexistnum = mysqli_num_rows($resultnum);
        
        if ($numExistRows > 0 || $numexistnum > 0 || $number <= 0) {
            echo "<script>alert('E-mail or store number already exists or is invalid! Try using another one or Login into your account.');</script>";
        } else {
            if ($password == $confirm) {
                // Hash the password before storing
                $hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user into signups table
                $insertSql = "INSERT INTO signups(UserName, emailID, password, StoreNum) VALUES('$fname', '$email', '$hash', '$number')";
                $insertResult = mysqli_query($conn, $insertSql);
                $insertnum = "INSERT INTO StoreNum(StoreNum) VALUES('$number')";
                $insertresnum = mysqli_query($conn, $insertnum);
                if ($insertResult and $insertresnum) {
                    
                    // Create store-specific tables
                    $maintable = "store".strval($number);
                    $inventory = "inventory".strval($number);
                    $orders = "orders".strval($number);
                    $bill = "bill".strval($number);
                    $warehouse = "warehouse".strval($number);
                    $staff = "staff".strval($number);
                    $userid = "admin@".$maintable.".com";
                    
                    // Create table queries
                    $createquery1 = "CREATE TABLE `$maintable` (username varchar(50),emailID varchar(30), userpassword varchar(255))";
                    $createquery2 = "CREATE TABLE `$inventory` (productID int auto_increment primary key, product varchar(30), quantity int, capacity int , price int , profit float)";
                    $createquery3 = "CREATE TABLE `$orders` (orderID int auto_increment primary key,productID int, quantity int, status VARCHAR(30) DEFAULT 'pending',ordervalue int, foreign key(productID) REFERENCES `$inventory`(productID))";
                    $createquery4 = "CREATE TABLE `$bill` (billID int auto_increment primary key,billvalue int, billdate DATETIME DEFAULT CURRENT_TIMESTAMP)";
                    $createquery5 = "CREATE TABLE `$staff` (staffID int auto_increment primary key, sname varchar(20), designation varchar(30) , ID varchar(50))";
                    $createquery6 = "CREATE TABLE `$warehouse` (product varchar (30))";

                    // Execute the table creation queries
                    mysqli_query($conn, $createquery1);
                    mysqli_query($conn, $createquery2);
                    mysqli_query($conn, $createquery3);
                    mysqli_query($conn, $createquery4);
                    mysqli_query($conn, $createquery5);
                    mysqli_query($conn, $createquery6);

                    
                    // Insert admin user into store-specific table
                    $insertuser = "INSERT INTO `$maintable` (username,emailID, userpassword) VALUES('$username','$userid', '$hash')";
                    $insertmanager = "INSERT INTO `$staff` (sname,designation,ID) VALUES('$username','$desig','$userid')";
                    mysqli_query($conn, $insertuser);
                    mysqli_query($conn, $insertmanager);
                    
                    // Redirect to the sign-in page
                    header("Location: thankyou.php");
                }
            } else {
                echo "<script>alert('Password and confirm password should be the same');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/contact_us.css">
    <title>Contact Us</title>
</head>
<body>
    <div class="container">
        <div class="logo_block">
            <img src="src/logo.png" class="logo" alt="Logo">
        </div>
        <div class="form">
            <h3>Contact Us</h3>
            <form method="POST" action='contact_us.php'>
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first-name" required>

                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last-name" required>

                <label for="email">Email address</label>
                <input type="email" id="email" placeholder="Enter email address" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" placeholder="*********" name="password" required>

                <label for="confirm-password">Confirm Password</label>
                <input type="password" id="confirm-password" placeholder="*********" name="confirm-password" required>

                <label for="store-number">Store No.</label>
                <input type="number" id="store-number" name="store-number" required>

                <p class="account-link"><a href="sign_in.php">Already have an account? </a></p>
                <button type="submit" class="submit-button" id="submit" name="submit">submit</button>
            </form>
        </div>
    </div>
</body>
</html>