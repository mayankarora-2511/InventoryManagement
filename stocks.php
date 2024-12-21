<?php
 session_start();
 if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $inventory = $_SESSION['inventory'];
    $prodid = $_POST['prodid'];
    $capa = $_POST['capacity'];
    $q = "UPDATE `$inventory` set capacity = '$capa' WHERE productID = '$prodid'";
    $done = mysqli_query($conn,$q);
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <link rel="stylesheet" href="css/stocks.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <img src="src/logo.png" alt="Logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="#"  class="active">Stocks and Inventory</a>
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
        <div class='content'>
            
        <div class = "tables">
        <table class="table">
        <h2>Stock details</h2>
            <thead>
                <th>Product ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Capacity</th>
                <th>Price</th>
                <th>Profit</th>
                <th></th>
            </thead>
            <tbody>
        <?php 
            include 'dbconnect.php';
            $inventory = $_SESSION['inventory'];
            $extract = "SELECT * FROM $inventory";
            $execute = mysqli_query($conn , $extract);
            while ($row = mysqli_fetch_assoc($execute))
            {
                echo"
                    <tr>
                <td>" .$row['productID']. "</td>
                <td>" .$row['product']. "</td>
                <td>" .$row['quantity']. "</td>
                <td>" .$row['capacity']. "</td>
                <td>" .$row['price']. "</td>
                <td>" .$row['profit']. "</td>
                <td><a href='removeproduct.php?productID=" .$row['productID']. "'>Remove</a></td>
            </tr>
                ";  
            }    
        ?>
        </tbody>
        </table>
        </div>
        <div class="button-div">
            <button class="add-button" id="update">
                UPDATE CAPACITY
            </button>
        </div>
        </div>  
    <footer>
        <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
    </footer>
    <div class="form-div" id="update-capacity">
        <span class="close-btn" id="closeBtn">&times;</span>
        <form action="" method="post" class="add-products">
            <label for="product">Product ID</label>
            <input type="number" id="product" placeholder="product ID" name="prodid" required>
            <label for="quantity">Capacity</label>
            <input type="number" id="quantity" placeholder="quantity" name="capacity" required>
            <div id="inner"></div>
            <button type="submit" class="add-button">UPDATE</button>
        </form>
    </div>
</body>
<script>
    let form = document.getElementById("update-capacity");
    let add = document.getElementById("update");
    add.addEventListener("click", () => {
        console.log("clicked");
        form.style.display = "flex";
    })
    let prod = document.getElementById("product");
    if (prod < 0){
        alert("Product ID cannot be negative")
    }
    const closeBtn = document.getElementById("closeBtn");
    closeBtn.addEventListener("click", () => {
        form.style.display = "none";
    });
</script>
</html>