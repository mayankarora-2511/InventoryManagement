<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'dbconnect.php';
    $inventory = $_SESSION['inventory'];
    $bill = $_SESSION['bill'];
    $count = $_POST['labels'];
    $id = [];
    $quant = [];
    $p = "product_name";
    $q = "quantity";
    $billvalue = 0;
    for ($i = 0; $i < $count; $i++) {
        $pc = $p . $i;  // Corrected: removed backticks
        $ii = $_POST[$pc];  // Corrected: removed backticks
        $qc = $q . $i;  // Corrected: removed backticks
        $qq = $_POST[$qc];  // Corrected: removed backticks
        array_push($id, $ii);
        array_push($quant, $qq);
    }
    for ($j = 0; $j < $count; $j++) {
        $current = $id[$j];
        $qqq = $quant[$j];
        $extract = "SELECT * FROM `$inventory` WHERE productID = '$current'";
        $res = mysqli_fetch_assoc(mysqli_query($conn, $extract));
        $val = $qqq * $res['price'];
        error_log("Product: $current, Price: {$res['price']}, Val: $val");
        $billvalue += $val;
    }
    $bool = true;
        for ($j = 0; $j < $count; $j++) {
            $current = $id[$j];
            $qqq = $quant[$j];
            $c = "SELECT * FROM `$inventory` WHERE productID = '$current'";
            $res = mysqli_fetch_assoc(mysqli_query($conn, $c));
            $newq = $res['quantity'] - $qqq;
            if ($newq < 0){
                $bool = false;
            }
        }
        if ($bool){
            $ins = "INSERT INTO `$bill` (billvalue) VALUES ('$billvalue')";
            $insexe = mysqli_query($conn, $ins);
            for ($j = 0; $j < $count; $j++) {
                $current = $id[$j];
                $qqq = $quant[$j];
                $c = "SELECT * FROM `$inventory` WHERE productID = '$current'";
                $res = mysqli_fetch_assoc(mysqli_query($conn, $c));
                $newq = $res['quantity'] - $qqq;
                if ($insexe) {
                    $up = "UPDATE `$inventory` SET quantity = '$newq' WHERE productID = '$current'";
                    $upexe = mysqli_query($conn, $up);

                }
            }
        }
        else{
            echo "<script>
                 alert('NOT ENOUGH STOCK LEFT IN INVENTORY, Bill not added');
                 window.location = 'billing.php';
                </script>";
        }
        
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate bill</title>
    <link rel="stylesheet" href="css/billing.css">
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
        <a href="#" class="active">Billing</a>
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
        <div class="metrics">
            <div class="box">
                <h3>Total sales</h3>
                <?php
                    include 'dbconnect.php';
                    $bill = $_SESSION['bill'];
                    $s = "SELECT SUM(billvalue) AS total FROM $bill";
                    $my = mysqli_fetch_assoc(mysqli_query($conn,$s));
                    echo "<p id='inventory'>". $my['total'] ."</p>";
                ?>
            </div>
        </div>
        <div class="tables">
            <table class="table">
                <div class = "boom">
                <h2>All Invoices</h2>
                <h3><a href="exportbill.php" class = "export">Export as CSV</a></h3>
                </div>
                
                <thead>
                    <th>Bill ID</th>
                    <th>Bill value</th>
                    <th>Bill date</th>
                </thead>
                <tbody>
                    <?php
                    include 'dbconnect.php';
                    $bill = $_SESSION['bill'];
                    $extract = "SELECT * FROM $bill";
                    $execute = mysqli_query($conn, $extract);

                    while ($row = mysqli_fetch_assoc($execute)) {
                        $row['billdate'] = date("Y-m-d", strtotime($row['billdate']));
                        echo "
                    <tr>
                <td>" . $row['billID'] . "</td>
                <td>" . $row['billvalue'] . "</td>
                <td>" . $row['billdate'] . "</td>
            </tr>
                ";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="button-div">
            <button class="add-button" id="update">
                ADD BILL
            </button>
        </div>
        <div class = "graph">
        <?php
        require 'analytics.php';   
    ?>
        </div>
        
    </div>
    
    <footer>
        <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
    </footer>
    <div class="form-div" id="add-form">
        <span class="close-btn" id="closeBtn">&times;</span>
        <form action="" method="post" class="add-products">
            <input type="hidden" value="" name="labels" id="L">
            <label for="product">Product</label>
            <input type="text" id="product" placeholder="product ID" name="product_name0" required>
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" placeholder="quantity" name="quantity0" required>
            <div id="inner"></div>
            <a class="add-field" id="add-field">+ Add More</a>
            <button type="submit" class="add-button">ADD</button>
        </form>
    </div>

</body>
<script>
    let form = document.getElementById("add-form");
    let add = document.getElementById("update");
    add.addEventListener("click", () => {
        console.log("clicked");
        form.style.display = "flex";
    })
    const closeBtn = document.getElementById("closeBtn");
    closeBtn.addEventListener("click", () => {
        form.style.display = "none";
    });
    let more = document.getElementById("add-field");
    let addit = document.getElementById("inner");
    let L = document.getElementById("L");
    let count = 1;
    L.value = count;
    more.addEventListener("click", () => {
        const newFields = `
            <label for="product">Product</label>
            <input type="text" id="product" placeholder="product ID" name="product_name${count}" required>
            <label for="quantity">Quantity</label>
            <input type="number" id="quantity" placeholder="quantity" name="quantity${count}" required>
        `;
        addit.insertAdjacentHTML('beforeend', newFields);
        count += 1;
        // console.log(count);
        L.value = count;
    });
</script>

</html>