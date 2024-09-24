<?php
include("mysql_connect.inc.php");

$payments_data = [];
$dates = [];
$total_amounts = [];
$vehicle_sales = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // 獲取每日總收入
    $sql = "SELECT DATE(p.payment_date) as payment_date, SUM(p.payment_amount) as total_amount
            FROM payments p
            JOIN bookings b ON b.booking_id = p.booking_id
            JOIN users u ON b.user_id = u.id
            WHERE p.payment_date BETWEEN '$start_date' AND '$end_date'
            GROUP BY DATE(p.payment_date)";
    $result = mysqli_query($conn, $sql);

    // 初始化日期範圍
    $current_date = $start_date;
    while (strtotime($current_date) <= strtotime($end_date)) {
        $dates[] = $current_date;
        $total_amounts[$current_date] = 0;
        $current_date = date("Y-m-d", strtotime("+1 day", strtotime($current_date)));
    }

    // 將收入數據添加到日期數組
    while ($row = mysqli_fetch_assoc($result)) {
        $total_amounts[$row['payment_date']] = $row['total_amount'];
    }

    // 計算累積收入
    $cumulative_amounts = [];
    $total_amount = 0;
    foreach ($dates as $date) {
        $total_amount += $total_amounts[$date];
        $cumulative_amounts[] = $total_amount;
    }

    // 獲取所有支付記錄
    $sql_all = "SELECT p.payment_id, p.booking_id, u.username, p.payment_amount, p.payment_payMethod, p.payment_date
                FROM payments p
                JOIN bookings b ON b.booking_id = p.booking_id
                JOIN users u ON b.user_id = u.id
                WHERE p.payment_date BETWEEN '$start_date' AND '$end_date'";
    $result_all = mysqli_query($conn, $sql_all);

    // 獲取相同vehicle_type和vehicle_model的銷售數量和金額
    $sql_sales = "SELECT v.vehicle_type, v.vehicle_model, COUNT(*) as sales_count, SUM(p.payment_amount) as total_amount
                  FROM payments p
                  JOIN bookings b ON b.booking_id = p.booking_id
                  JOIN vehicles v ON b.vehicle_id = v.vehicle_id
                  WHERE p.payment_date BETWEEN '$start_date' AND '$end_date'
                  GROUP BY v.vehicle_type, v.vehicle_model
                  ORDER BY total_amount DESC";
    $result_sales = mysqli_query($conn, $sql_sales);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Revenue Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('17.jpg') no-repeat center center fixed;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            margin: 0;
        }
        .content {
            background-color: rgba(255, 255, 255, 0.9); /* 白色背景，90%不透明度 */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 1200px;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-container a:hover {
            background-color: #45a049;
        }
        .chart-container {
            width: 80%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>Revenue Report from <?php echo $start_date; ?> to <?php echo $end_date; ?></h2>
        
        <?php if (count($dates) > 0): ?>
            <div class="chart-container">
                <canvas id="revenueChart"></canvas>
            </div>
            <script>
                const ctx = document.getElementById('revenueChart').getContext('2d');
                const data = {
                    labels: <?php echo json_encode($dates); ?>,
                    datasets: [{
                        label: 'Cumulative Revenue',
                        data: <?php echo json_encode($cumulative_amounts); ?>,
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1
                    }]
                };

                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Revenue ($)'
                                }
                            }
                        }
                    }
                };

                const revenueChart = new Chart(ctx, config);
            </script>


            <!-- 顯示相同vehicle_type和vehicle_model的銷售數量和金額 -->
            <h3>Sales by Vehicle Type and Model</h3>
            <table>
                <tr>
                    <th>Vehicle Type</th>
                    <th>Vehicle Model</th>
                    <th>Sales Count</th>
                    <th>Total Amount</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result_sales)) { ?>
                    <tr>
                        <td><?php echo $row['vehicle_type']; ?></td>
                        <td><?php echo $row['vehicle_model']; ?></td>
                        <td><?php echo $row['sales_count']; ?></td>
                        <td><?php echo $row['total_amount']; ?></td>
                    </tr>
                <?php } ?>
            </table>

            <!-- 顯示摘要 -->
            <h3>Summary</h3>
            <p>Total Payments: <?php echo mysqli_num_rows($result_all); ?></p>
            <p>Total Revenue: $<?php echo number_format(end($cumulative_amounts), 2); ?></p>


            <!-- 顯示所有支付記錄 -->
            <h3>Payment Records</h3>
            <table>
                <tr>
                    <th>Payment ID</th>
                    <th>Booking ID</th>
                    <th>Username</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Date</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result_all)) { ?>
                    <tr>
                        <td><?php echo $row['payment_id']; ?></td>
                        <td><?php echo $row['booking_id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['payment_amount']; ?></td>
                        <td><?php echo $row['payment_payMethod']; ?></td>
                        <td><?php echo $row['payment_date']; ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php else: ?>
            <p>No payments found for the selected date range.</p>
        <?php endif; ?>

        <div class="btn-container">
            <a href="financial_management.php">Back to Financial Management</a>
        </div>
    </div>
</body>
</html>

