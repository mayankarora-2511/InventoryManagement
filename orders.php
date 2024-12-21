<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $productid = $_POST['product'];
    $orderquantity = $_POST['quantity'];
    $status = "pending";
    $inventory = $_SESSION['inventory'];
    $ids = "SELECT productID from `$inventory`";
    $idss = mysqli_query($conn, $ids);
    $found = false; // Flag to track if the productID is found

while ($row =  mysqli_fetch_assoc($idss)) {
    if ($row['productID'] == $productid) {
        $found = true;
        break; // Stop the loop once the product is found
    }
}
    if ($found) {
        $orders = $_SESSION['orders'];
        $extract = "SELECT * FROM `$inventory` WHERE productid = '$productid'";
        $result = mysqli_query($conn, $extract);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $capacity = $row['capacity'];
            $quantity = $row['quantity'];
            $price = $orderquantity * $row['price'];
            $final = $quantity + $orderquantity;
            if ($final <= $capacity) {
                $insert = "INSERT INTO `$orders` (productID, quantity, status,ordervalue) VALUES ('$productid','$orderquantity','$status','$price')";
                $check = mysqli_query($conn, $insert);
                if ($check) {
                    header("Location: dashboard.php");
                } else {
                    echo "<script>alert('error in placing order')</script>";
                }
            }
        }


    } else {
        header('location: notfound.php');
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="css/orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <img src="src/logo.png" alt="Logo">
        <a href="dashboard.php">Dashboard</a>
        <a href="stocks.php">Stocks and Inventory</a>
        <a href="products.php">Manage products</a>
        <div class="sidebar-drop">
            <a href="#">Manage users</a>
            <div class="sidebar-dropdown">
                <a href='adduser.php'>Add user</a>
                <a href='removeuser.php'>Remove user</a>
            </div>
        </div>
        <a href="#" class="active">Manage orders</a>
        <a href="billing.php">Billing</a>
        <a href="supplier.php">Supplier</a>
        <a href="./EndStore.php">End Store</a>
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
            <div class="box">
                <h3>Total orders</h3>
                <?php
                include 'dbconnect.php';
                $orders = $_SESSION['orders'];
                $numrows = "SELECT * FROM $orders";
                $exe = mysqli_query($conn, $numrows);

                $totalorders = mysqli_num_rows($exe);
                echo "<p id='inventory'>" . $totalorders . "</p>";
                ?>
            </div>
            <div class="box">
                <div class="mySlides">
                    <h3>Total order value</h3>
                    <p id="profit">
                        <?php
                        include 'dbconnect.php';
                        $orders = $_SESSION['orders'];
                        $totalvalue = "SELECT SUM(ordervalue) AS total_sum FROM $orders";
                        $t = mysqli_query($conn, $totalvalue);
                        $tt = mysqli_fetch_assoc($t);
                        echo "<p id='capacity'>" . $tt['total_sum'] . "</p>";
                        ?>
                    </p>
                </div>
                <div class="mySlides">
                    <h3>Received order value</h3>
                    <p id="profit">
                        <?php
                        include 'dbconnect.php';
                        $orders = $_SESSION['orders'];
                        $totalvalue = "SELECT SUM(ordervalue) AS total_sum FROM $orders WHERE status = 'received'";
                        $t = mysqli_query($conn, $totalvalue);
                        $tt = mysqli_fetch_assoc($t);
                        echo "<p id='capacity'>" . $tt['total_sum'] . "</p>";
                        ?>
                    </p>
                </div>
                <div class="mySlides">
                    <h3>Confirmed order value</h3>
                    <p id="profit">
                        <?php
                        include 'dbconnect.php';
                        $orders = $_SESSION['orders'];
                        $totalvalue = "SELECT SUM(ordervalue) AS total_sum FROM $orders WHERE status = 'confirmed'";
                        $t = mysqli_query($conn, $totalvalue);
                        $tt = mysqli_fetch_assoc($t);
                        echo "<p id='capacity'>" . $tt['total_sum'] . "</p>";
                        ?>
                    </p>
                </div>
                <div class="mySlides">
                    <h3>Pending order value</h3>
                    <p id="profit">
                        <?php
                        include 'dbconnect.php';
                        $orders = $_SESSION['orders'];
                        $totalvalue = "SELECT SUM(ordervalue) AS total_sum FROM $orders WHERE status = 'pending'";
                        $t = mysqli_query($conn, $totalvalue);
                        $tt = mysqli_fetch_assoc($t);
                        echo "<p id='capacity'>" . $tt['total_sum'] . "</p>";
                        ?>
                    </p>
                </div>
                <div class="dot-container">
                    <span class="dot" onclick="currentDiv(0)"></span>
                    <span class="dot" onclick="currentDiv(1)"></span>
                    <span class="dot" onclick="currentDiv(2)"></span>
                    <span class="dot" onclick="currentDiv(2)"></span>
                </div>

            </div>
        </div>
    </div>
    <div class="content">
        <div class="tables">
            <table class="table">
                <div class="boom">
                    <h2>All orders</h2>
                    <h3><a href="exportorder.php" class="export">Export as csv</a></h3>
                </div>

                <thead>
                    <th>Order ID</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Order value</th>
                    <th>Status</th>
                    <th></th>
                </thead>
                <tbody>
                    <?php
                    include 'dbconnect.php';
                    $orders = $_SESSION['orders'];
                    $extract = "SELECT * FROM $orders";
                    $execute = mysqli_query($conn, $extract);
                    while ($rows = mysqli_fetch_assoc($execute)) {
                        if ($rows['status'] !== "Received") {
                            echo "
                    <tr>
                        <td>" . $rows['orderID'] . "</td>
                        <td>" . $rows['productID'] . "</td>
                        <td>" . $rows['quantity'] . "</td>
                        <td>" . $rows['ordervalue'] . "</td>
                        <td>" . $rows['status'] . "</td>
                        <td><a href='receiveorder.php?orderID=" . $rows['orderID'] . "&quantity=" . $rows['quantity'] . "&productid=" . $rows['productID'] . "'>Received</a></td>
                    </tr>
        ";
                        } else {
                            echo "
                    <tr>
                        <td>" . $rows['orderID'] . "</td>
                        <td>" . $rows['productID'] . "</td>
                        <td>" . $rows['quantity'] . "</td>
                        <td>" . $rows['ordervalue'] . "</td>
                        <td>" . $rows['status'] . "</td>
                        <td>Order received and inventory updated</td>
                        </tr>
        ";
                        }

                    }
                    ?>
                </tbody>
            </table>
            <div class="button-div">
                <button class="order-button" id="place">
                    Place order
                </button>
            </div>
        </div>

    </div>
    <div class="form-div" id="add-form">
        <span class="close-btn" id="closeBtn">&times;</span>
        <form action="" method="post" class="add-products">
            <label for="product">Product ID</label>
            <input type="number" id="product" placeholder="product id" name="product" required>
            <label for="quantity">quantity</label>
            <input type="number" id="quantity" placeholder="quantity" name="quantity" required>
            <button type="submit" class="add-button">ADD</button>
        </form>
    </div>
    <footer>
        <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
    </footer>
</body>


<script>
    let form = document.getElementById("add-form");
    let add = document.getElementById("place");
    add.addEventListener("click", () => {
        form.style.display = "flex";
    })
    const closeBtn = document.getElementById("closeBtn");
    closeBtn.addEventListener("click", () => {
        form.style.display = "none";
    });
    var slideIndex = 0;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function currentDiv(n) {
        showDivs(slideIndex = n);
    }

    function showDivs(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        if (n >= slides.length) { slideIndex = 0 }
        if (n < 0) { slideIndex = slides.length - 1 }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex].style.display = "block";
        dots[slideIndex].className += " active";
    }

    // Auto-slide every 10 seconds
    setInterval(function () {
        plusDivs(1);
    }, 5000);
</script>

</html>