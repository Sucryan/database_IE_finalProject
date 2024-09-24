<?php
session_start();
include("mysql_connect.inc.php");

// 確保只有管理員可以訪問此頁面
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '請先登錄管理員賬戶！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=manager_login.php>';
    exit();
}

// 處理更新 price_multiplier 的請求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_price_multiplier'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $price_multiplier = $_POST['price_multiplier'];

    $sql = "UPDATE vehicles SET price_multiplier='$price_multiplier' WHERE vehicle_id='$vehicle_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Price multiplier 更新成功');
                window.location.href = 'financial_management.php';
              </script>";
    } else {
        echo "Price multiplier 更新失敗: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('16.jpg') no-repeat center center fixed;
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
            text-align: center; /* 文字置中 */
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto; /* 表格置中 */
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: center; /* 表格文字置中 */
        }
        th {
            background-color: #f2f2f2;
        }
        h1, h2 {
            text-align: center;
        }
        form {
            margin-top: 20px;
            width: 80%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        form label {
            margin-right: 10px;
            text-align: center; /* 表單文字置中 */
            width: 100%;
        }
        form input[type="date"],
        form input[type="number"],
        form input[type="text"],
        form input[type="submit"] {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            text-align: center; /* 表單輸入框置中 */
        }
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
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
    </style>
</head>
<body>
    <div class="content">
        <h1>Financial Management</h1>
        <h2>Payment List</h2>
        <?php
        $sql = "SELECT p.payment_id, p.booking_id, u.username, p.payment_amount, p.payment_payMethod, p.payment_date
                FROM payments p
                JOIN bookings b ON b.booking_id = p.booking_id
                JOIN users u ON b.user_id = u.id";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die('查詢失敗: ' . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) > 0) {
            echo "<table>
                    <tr>
                        <th>Payment ID</th>
                        <th>Booking ID</th>
                        <th>Username</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['payment_id']}</td>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['username']}</td>
                        <td>{$row['payment_amount']}</td>
                        <td>{$row['payment_payMethod']}</td>
                        <td>{$row['payment_date']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No payments found.</p>";
        }
        ?>

        <h2>Generate Revenue Report</h2>
        <form method="post" action="generate_report.php">
            <label for="start_date">Start Date：</label>
            <input type="date" name="start_date" id="start_date" required>
            <br>
            <label for="end_date">End Date：</label>
            <input type="date" name="end_date" id="end_date" required>
            <br>
            <input type="submit" value="Generate Report">
        </form>

        <h2>Update Price Multiplier</h2>
        <form method="post" action="financial_management.php">
            <label for="vehicle_id">Vehicle ID：</label>
            <input type="number" name="vehicle_id" id="vehicle_id" required>
            <br>
            <label for="price_multiplier">Price Multiplier：</label>
            <input type="number" step="0.01" name="price_multiplier" id="price_multiplier" required>
            <br>
            <input type="submit" name="update_price_multiplier" value="Update Price Multiplier">
        </form>

        <h2>Vehicle Price Multipliers</h2>
        <?php
        $sql = "SELECT vehicle_id, vehicle_type, vehicle_model, price_multiplier FROM vehicles";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Model</th>
                        <th>Price Multiplier</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['vehicle_id']}</td>
                        <td>{$row['vehicle_type']}</td>
                        <td>{$row['vehicle_model']}</td>
                        <td>{$row['price_multiplier']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No vehicles found.</p>";
        }
        ?>

        <div class="btn-container">
            <a href="system_manager.php">Back to Management Home</a>
        </div>
    </div>
</body>
</html>
