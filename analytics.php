<?php
include 'dbconnect.php';

$bill = $_SESSION['bill'];
$sql = "SELECT DATE(billdate) as bill_date, SUM(billvalue) as total_amount FROM $bill GROUP BY bill_date ORDER BY bill_date";
$result = mysqli_query($conn, $sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Sum Graph</title>
    <link rel="stylesheet" href="css/analytics.css">
</head>
<body>
    <div class="container">
        <canvas id="billChart"></canvas>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const data = <?php echo json_encode($data); ?>;
        const dates = data.map(entry => entry.bill_date);
        const amounts = data.map(entry => entry.total_amount);

        const ctx = document.getElementById('billChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Total Amount',
                    data: amounts,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    borderWidth: 1,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: 'rgba(255, 99, 132, 1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            padding: 5,
                            font: {
                                size: 14,
                                family: 'Arial, sans-serif',
                                weight: 'bold',
                            },
                            color: '#333'
                        }
                    },
                    title: {
                        display: true,
                        text: 'Date-wise Sum of Bills',
                        font: {
                            size: 20,
                            family: 'Arial, sans-serif',
                            weight: 'bold',
                        },
                        color: '#333',
                        padding: {
                            top: 10,
                            bottom: 30
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(54, 162, 235, 0.8)',
                        titleFont: {
                            size: 14,
                            weight: 'bold',
                            family: 'Arial, sans-serif',
                        },
                        bodyFont: {
                            size: 12,
                            family: 'Arial, sans-serif',
                        },
                        callbacks: {
                            title: function(tooltipItems) {
                                return 'Date: ' + tooltipItems[0].label;
                            },
                            label: function(context) {
                                return 'Total Amount: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        offset: true, // Adds space at the start and end of the x-axis
                        title: {
                            display: true,
                            text: 'Date',
                            font: {
                                size: 14,
                                family: 'Arial, sans-serif',
                                weight: 'bold',
                            },
                            color: '#333',
                            padding: 10
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 12,
                                family: 'Arial, sans-serif',
                                weight: '500',
                            },
                            color: '#555'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        min: Math.min(...amounts) * 0.9, // Set minimum to 90% of the lowest value to zoom out
                        max: Math.max(...amounts) * 1.1, // Set maximum to 110% of the highest value to zoom out
                        title: {
                            display: true,
                            text: 'Total Amount',
                            font: {
                                size: 14,
                                family: 'Arial, sans-serif',
                                weight: 'bold',
                            },
                            color: '#333',
                            padding: 10
                        },
                        ticks: {
                            stepSize: 500,
                            font: {
                                size: 12,
                                family: 'Arial, sans-serif',
                                weight: '500',
                            },
                            color: '#555'
                        },
                        grid: {
                            color: 'rgba(200, 200, 200, 0.3)' // Subtle grid color
                        }
                    }
                },
                layout: {
                    padding: {}
                }
            }
        });
    });
</script>


</body>
</html>

