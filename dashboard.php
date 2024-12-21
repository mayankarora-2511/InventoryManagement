<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('location: sign_in.php');
    exit;
}
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="sidebar">
        <img src="src/logo.png" alt="Logo">
        <a href="#" class="active">Dashboard</a>
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
                <h3>Inventory % Left</h3>
                <?php
                include 'dbconnect.php';
                $invent = $_SESSION['inventory'];
                $value1 = "SELECT SUM(quantity) AS quant FROM $invent";
                $value2 = "SELECT SUM(capacity) AS capa FROM $invent";
                $extr = mysqli_query($conn, $value1);
                $sum1 = mysqli_fetch_assoc($extr);
                $extr = mysqli_query($conn, $value2);
                $sum2 = mysqli_fetch_assoc($extr);
                if ($sum2['capa'] != 0) {
                    $inleft = ($sum1['quant'] / $sum2['capa']) * 100;
                    echo "<p id='inventory'>" . (int) $inleft . "</p>";
                } else {
                    echo "<p id='inventory'>No items in inventory</p>";
                }

                ?>
            </div>
            <div class="box">
                <h3>Capacity % Left</h3><?php
                include 'dbconnect.php';
                $invent = $_SESSION['inventory'];
                $value1 = "SELECT SUM(quantity) AS quant FROM $invent";
                $value2 = "SELECT SUM(capacity) AS capa FROM $invent";
                $extr = mysqli_query($conn, $value1);
                $sum1 = mysqli_fetch_assoc($extr);
                $extr = mysqli_query($conn, $value2);
                $sum2 = mysqli_fetch_assoc($extr);
                if ($sum2['capa'] != 0) {
                    $inleft = ($sum1['quant'] / $sum2['capa']) * 100;
                    $caleft = 100 - $inleft;
                    echo "<p id='inventory'>" . (int) $caleft . "</p>";
                } else {
                    echo "<p id='inventory'>No items in inventory</p>";
                }

                ?>
            </div>
            <div class="box">
                <?php
                $invent = $_SESSION['inventory'];
                $p = "SELECT * FROM $invent";
                $po = mysqli_query($conn, $p);
                $i = 0;
                while ($r = mysqli_fetch_assoc($po)) {
                    $prod = $r['product'];
                    $pri = $r['profit'];
                    echo "
                        <div class='mySlides'><h3>% Profit</h3>
                            <p id='best-selling'>" . ucfirst($prod) . "</p>
                            <p id='profit'>" . $pri . "</p>
                            </div>
                        ";

                }
                ?>

                <div class="dot-container">
                    <?php
                    include "dbconnect.php";
                    $in = $_SESSION['inventory'];
                    $execu = "SELECT * FROM $in";
                    $ex1 = mysqli_query($conn, $execu);
                    $num = mysqli_num_rows($ex1);
                    for ($i = 0; $i < $num; $i++) {
                        echo "<span class='dot' onclick='currentDiv(" . $i . ")'></span>";
                    }
                    ?>
                </div>

            </div>

        </div>

        <div class="tables">
            <div class="table">
                <h3>Orders</h3>
                <table>
                    <thead>
                        <tr>
                            <th>order id</th>
                            <th>Product ordered</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        include 'dbconnect.php';
                        $or = $_SESSION['orders'];
                        $exet = "SELECT * FROM $or";
                        $exetexecute = mysqli_query($conn, $exet);
                        while ($row = mysqli_fetch_assoc($exetexecute)) {
                            echo "<tr>
                                        <td>" . $row['orderID'] . "</td>
                                        <td>" . $row['productID'] . "</td>";
                            if ($row['status'] == 'pending') {
                                echo "<td class='pending'>" . $row['status'] . "</td>";
                            } elseif ($row['status'] == 'confirmed') {
                                echo "<td class='approved'>" . $row['status'] . "</td>";
                            } elseif ($row['status'] == 'Received') {
                                echo "<td class = 'received'>" . $row['status'] . "</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table">
                <h3>Staff List</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Staff ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'dbconnect.php';
                        $st = $_SESSION['staff'];
                        $exet = "SELECT * FROM $st";
                        $exetexecute = mysqli_query($conn, $exet);
                        while ($row = mysqli_fetch_assoc($exetexecute)) {
                            echo "<tr>
                                        <td>" . $row['staffID'] . "</td>
                                        <td>" . $row['sname'] . "</td> 
                                        <td>" . $row['designation'] . "</td>    
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table">
                <h3>products</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'dbconnect.php';
                        $int = $_SESSION['inventory'];
                        $exet = "SELECT * FROM $int";
                        $exetexecute = mysqli_query($conn, $exet);
                        while ($row = mysqli_fetch_assoc($exetexecute)) {
                            echo "<tr>
                                        <td>" . $row['productID'] . "</td>
                                        <td>" . $row['product'] . "</td> 
                                        <td>" . $row['price'] . "</td>    
                                </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="table-chart pie-chart">
                <h3>Order Status</h3>
                <?php
                include "dbconnect.php";
                $orders = $_SESSION['orders'];
                $sql = "SELECT 
                                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
                                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) AS confirmed,
                                SUM(CASE WHEN status = 'Received' THEN 1 ELSE 0 END) AS received
                            FROM $orders";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $pending = $row['pending'];
                    $delivered = $row['confirmed'];
                    $outForDelivery = $row['received'];
                } else {
                    $pending = $delivered = $outForDelivery = 0;
                }

                $conn->close();
                ?>
                <canvas id="orderStatusChart"></canvas>
            </div>

        </div>

    </div>

    <footer>
        <p>Copyright &copy; 2024 GenZ Stylers. All Rights Reserved</p>
    </footer>

    <!-- JavaScript to create the pie chart -->
    <script>
        let ctx = document.getElementById('orderStatusChart').getContext('2d');
        let orderStatusChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Pending', 'Confirmed', 'Received'],
                datasets: [{
                    data: [<?php echo $pending; ?>, <?php echo $delivered; ?>, <?php echo $outForDelivery; ?>],
                    backgroundColor: ['#f4a261', '#2a9d8f', '#e76f51']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        position: 'left',
                        align: 'start',
                        labels: {
                            boxWidth: 20,
                            padding: 20
                        }
                    }
                }
            }
        });
        console.log([<?php echo $pending; ?>, <?php echo $delivered; ?>, <?php echo $outForDelivery; ?>]);

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

</body>

</html>