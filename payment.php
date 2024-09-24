<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['id'])) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
    exit();
}

$user_id = $_SESSION['id'];
$booking_id = $_GET['booking_id'];
$amount = $_GET['amount'];
$vehicle_id = $_GET['vehicle_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_method = $_POST['payment_method'];

    $sql = "INSERT INTO payments (booking_id, payment_amount, payment_payMethod)
            VALUES ('$booking_id', '$amount', '$payment_method')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('支付成功!');
                window.location.href = 'invoice.php?payment_id=" . mysqli_insert_id($conn) . "&vehicle_id=$vehicle_id';
              </script>";
    } else {
        echo '支付失敗: ' . mysqli_error($conn);
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Payment</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: url('22.jpg') no-repeat center center fixed;
                background-color: #f7f7f7;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .form-container {
                background: #fff;
                padding: 20px 30px;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .form-container h2 {
                text-align: center;
                margin-bottom: 20px;
            }
            .form-container input[type="text"],
            .form-container select {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }
            .form-container input[type="submit"] {
                width: 100%;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
            }
            .form-container input[type="submit"]:hover {
                background-color: #45a049;
            }
        </style>
    </head>
    <body>
        <div class="form-container">
            <h2>Payment</h2>
            <form method="post" action="payment.php?booking_id=<?php echo $booking_id; ?>&amount=<?php echo $amount; ?>&vehicle_id=<?php echo $vehicle_id; ?>">
                <label for="payment_method">選擇支付方式：</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
                <p>總金額：<?php echo $amount; ?>元</p>
                <input type="submit" value="支付" />
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
