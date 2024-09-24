<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['id'])) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
    exit();
}

$payment_id = $_GET['payment_id'];
$vehicle_id = $_GET['vehicle_id'];

// 查詢支付詳情
$sql = "SELECT p.*, b.pick_up_location, b.pick_up_time, b.return_time, v.vehicle_model
        FROM payments p
        JOIN bookings b ON p.booking_id = b.booking_id
        JOIN vehicles v ON b.vehicle_id = v.vehicle_id
        WHERE p.payment_id = '$payment_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $payment = mysqli_fetch_assoc($result);
} else {
    echo '支付詳情查詢失敗: ' . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('18.jpg') no-repeat center center fixed;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .invoice-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 600px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn-container {
            text-align: center;
        }
        .btn-container a {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-container a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <h2>Invoice</h2>
        <table>
            <tr>
                <th>Payment ID</th>
                <td><?php echo $payment['payment_id']; ?></td>
            </tr>
            <tr>
                <th>Booking ID</th>
                <td><?php echo $payment['booking_id']; ?></td>
            </tr>
            <tr>
                <th>Vehicle Model</th>
                <td><?php echo $payment['vehicle_model']; ?></td>
            </tr>
            <tr>
                <th>Pick Up Location</th>
                <td><?php echo $payment['pick_up_location']; ?></td>
            </tr>
            <tr>
                <th>Pick Up Time</th>
                <td><?php echo $payment['pick_up_time']; ?></td>
            </tr>
            <tr>
                <th>Return Time</th>
                <td><?php echo $payment['return_time']; ?></td>
            </tr>
            <tr>
                <th>Amount</th>
                <td><?php echo $payment['payment_amount']; ?>元</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td><?php echo $payment['payment_payMethod']; ?></td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td><?php echo $payment['payment_date']; ?></td>
            </tr>
        </table>
        <p style="text-align: center;">Thank you for your payment!</p>
        <div class="btn-container">
            <a href="member.php?vehicle_id=<?php echo $vehicle_id; ?>">返回會員資料</a>
        </div>
    </div>
</body>
</html>
