<?php
 session_start();
 if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $capacity = $_POST['capacity'];
    $price = $_POST['Price'];
    $profit = $_POST['Profit'];
    $inventory = $_SESSION['inventory'];
    $warehouse = $_SESSION['warehouse'];
    $order = $_SESSION['orders'];
    $extract2 = "SELECT `productID`, `product` FROM $inventory";
    $execute2 = mysqli_query($conn , $extract2);
    while($rand = mysqli_fetch_assoc($execute2)){
        if ($product == $rand['product']){
            echo"<script>alet('product already exists')</script>";
        }
    }
    if ($quantity <= $capacity){
        $insert = "INSERT INTO $inventory (product,quantity,capacity,price,profit) VALUES ('$product','$quantity','$capacity','$price','$profit')";
        $execute = mysqli_query($conn,$insert);
        $alter = "INSERT INTO $warehouse (product) VALUES ('$product')";
        $alterexecution = mysqli_query($conn, $alter);
        if ($execute & $alterexecution){
            echo"<script>alert('Product added')</script>";
            header("Location: dashboard.php");
        }
    }
    else{
        echo"<script>alert('error in adding product, check the details again')</script>";
    }
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage products</title>
    <link rel="stylesheet" href="css/products.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <img src="src/logo.png" alt="Logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="stocks.php">Stocks and Inventory</a>
        <a href="#" class="active">Manage products</a>
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
        <a href="./EndStore.php">End Store</a>
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


        </div>
    <div class="content">
    <div class = "tables">
        <table class="table">
        <h2>All products</h2>
            <thead>
                <th>Product ID</th>
                <th>Product</th>
                <th></th>
            </thead>
            <tbody>
        <?php 
            include 'dbconnect.php';
            $inventory = $_SESSION['inventory'];
            $extract = "SELECT `productID`, `product` FROM $inventory";
            $execute = mysqli_query($conn , $extract);
            while ($row = mysqli_fetch_assoc($execute))
            {
                echo"
                    <tr>
                        <td>" .$row['productID']. "</td>
                        <td>" .$row['product']. "</td>
                        <td><a href= removeproduct.php?productID="  .$row['productID']. ">Remove</a></td>
                    </tr>
                ";  
            }    
        ?>
        </tbody>
        </table>
        </div>
        <div class="button-div">
            <button class="signin-button" id = "add">
                Add product
            </button>
        </div>
    </div>
    <div class="form-div" id="add-form">
    <span class="close-btn" id="closeBtn">&times;</span>
        <form action="" method="post" class="add-products">
            <label for="product">Product</label>
            <input type="text" id="product" placeholder="product name" name="product" required>
            <label for="quantity">quantity</label>
            <input type="number" id="quantity" placeholder="quantity" name="quantity" required>
            <label for="capacity">Capacity</label>
            <input type="number" id="capacity" name="capacity" required>
            <label for="Price">Price</label>
            <input type="number" id="Price" name="Price" required>
            <label for="Profit">Profit</label>
            <input type="number" id="Profit" name="Profit" required>
            <button type="submit" class="add-button">ADD</button>
        </form>  
    </div>
    <footer>
        <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
    </footer>
</body>
<script>
    let form = document.getElementById("add-form");
    let add = document.getElementById("add");
    add.addEventListener("click",() =>{
        form.style.display = "flex";
    })
    const closeBtn = document.getElementById("closeBtn");
    closeBtn.addEventListener("click", () => {
  form.style.display = "none";
});
</script>
</html>