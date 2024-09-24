<?php
session_start();
include("mysql_connect.inc.php");

// 確保只有管理員可以訪問此頁面
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '請先登錄管理員賬戶！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=manager_login.php>';
    exit();
}

// 獲取訂單詳情
function getOrderDetails($booking_id) {
    global $conn;
    $sql = "SELECT b.booking_id, u.username, u.email, u.phone, v.vehicle_type, v.vehicle_model, b.pick_up_location, b.pick_up_time, b.return_time
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN vehicles v ON b.vehicle_id = v.vehicle_id
            WHERE b.booking_id = '$booking_id'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}

// 獲取客戶記錄
function getCustomerRecord($user_id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('12.jpg') no-repeat center center fixed;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header a {
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
        .header a:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
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
        .actions a {
            color: #f44336;
            text-decoration: none;
        }
        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Management</h1>
            <a href="system_manager.php">Back to Management Home</a>
        </div>
        <h2>Booking List</h2>
        <?php
        if (isset($_GET['view_order']) && $_GET['view_order']) {
            $orderDetails = getOrderDetails($_GET['booking_id']);
            echo "<h2>Order Details</h2>";
            if ($orderDetails) {
                echo "<table>
                        <tr><th>Booking ID</th><td>{$orderDetails['booking_id']}</td></tr>
                        <tr><th>Username</th><td>{$orderDetails['username']}</td></tr>
                        <tr><th>Email</th><td>{$orderDetails['email']}</td></tr>
                        <tr><th>Phone</th><td>{$orderDetails['phone']}</td></tr>
                        <tr><th>Vehicle Type</th><td>{$orderDetails['vehicle_type']}</td></tr>
                        <tr><th>Vehicle Model</th><td>{$orderDetails['vehicle_model']}</td></tr>
                        <tr><th>Pick-up Location</th><td>{$orderDetails['pick_up_location']}</td></tr>
                        <tr><th>Pick-up Time</th><td>{$orderDetails['pick_up_time']}</td></tr>
                        <tr><th>Return Time</th><td>{$orderDetails['return_time']}</td></tr>
                    </table>";
            } else {
                echo "<p>Order not found.</p>";
            }
            echo "<a href='booking_management.php'>Back to Booking List</a>";
        } elseif (isset($_GET['view_customer']) && $_GET['view_customer']) {
            $customerRecord = getCustomerRecord($_GET['user_id']);
            echo "<h2>Customer Record</h2>";
            if ($customerRecord) {
                echo "<table>
                        <tr><th>User ID</th><td>{$customerRecord['id']}</td></tr>
                        <tr><th>Username</th><td>{$customerRecord['username']}</td></tr>
                        <tr><th>Email</th><td>{$customerRecord['email']}</td></tr>
                        <tr><th>Phone</th><td>{$customerRecord['phone']}</td></tr>
                        <tr><th>Country</th><td>{$customerRecord['country']}</td></tr>
                        <tr><th>Sex</th><td>{$customerRecord['sex']}</td></tr>
                        <tr><th>Birthday</th><td>{$customerRecord['birthday']}</td></tr>
                        <tr><th>Note</th><td>{$customerRecord['note']}</td></tr>
                    </table>";
            } else {
                echo "<p>Customer not found.</p>";
            }
            echo "<a href='booking_management.php'>Back to Booking List</a>";
        } else {
            $sql = "SELECT b.booking_id, u.username, v.vehicle_type, v.vehicle_model, b.pick_up_location, b.pick_up_time, b.return_time, b.vehicle_id, u.id as user_id
                    FROM bookings b
                    JOIN users u ON b.user_id = u.id
                    JOIN vehicles v ON b.vehicle_id = v.vehicle_id";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<table>
                        <tr>
                            <th>Booking ID</th>
                            <th>Username</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle Model</th>
                            <th>Pick-up Location</th>
                            <th>Pick-up Time</th>
                            <th>Return Time</th>
                            <th>Actions</th>
                        </tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['booking_id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['vehicle_type']}</td>
                            <td>{$row['vehicle_model']}</td>
                            <td>{$row['pick_up_location']}</td>
                            <td>{$row['pick_up_time']}</td>
                            <td>{$row['return_time']}</td>
                            <td class='actions'>
                                <a href='booking_management.php?view_order=true&booking_id={$row['booking_id']}'>View Details</a> |
                                <a href='delete_booking.php?booking_id={$row['booking_id']}'>Delete Booking</a> |
                                <a href='booking_management.php?view_customer=true&user_id={$row['user_id']}'>Customer Record</a>
                            </td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No bookings found.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
