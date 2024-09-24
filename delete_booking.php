<?php
session_start();
include("mysql_connect.inc.php");

// 確保使用者已登入
if (!isset($_SESSION['id'])) {
    echo 'Please log in first!';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=manager_login.php>';
    exit();
}

$cancellation_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];

    // 從資料庫中查詢出正確的 vehicle_id
    $sql_check = "SELECT vehicle_id FROM bookings WHERE booking_id = '$booking_id'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $row = mysqli_fetch_assoc($result_check);
        $vehicle_id = $row['vehicle_id'];

        // 刪除訂單和付款記錄
        $sql_delete_booking = "DELETE FROM bookings WHERE booking_id = '$booking_id'";
        $sql_delete_payment = "DELETE FROM payments WHERE booking_id = '$booking_id'";

        if (mysqli_query($conn, $sql_delete_booking) && mysqli_query($conn, $sql_delete_payment)) {
            // 更新車輛可用性
            $sql_update_vehicle = "UPDATE vehicles SET availability = availability + 1 WHERE vehicle_id = '$vehicle_id'";
            mysqli_query($conn, $sql_update_vehicle);
            $cancellation_message = 'Deletion successful!';
        } else {
            $cancellation_message = 'Deletion failed: ' . mysqli_error($conn);
        }
    } else {
        $cancellation_message = 'Invalid booking ID.';
    }
} else {
    $booking_id = $_GET['booking_id'];

    // 從資料庫中查詢出正確的 vehicle_id
    $sql_check = "SELECT vehicle_id FROM bookings WHERE booking_id = '$booking_id'";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) == 0) {
        echo 'Invalid booking ID.';
        echo '<meta http-equiv=REFRESH CONTENT=2;url=booking_management.php>';
        exit();
    } else {
        $row = mysqli_fetch_assoc($result_check);
        $vehicle_id = $row['vehicle_id'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cancel Booking</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('15.jpg') no-repeat center center fixed;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .modal {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .modal h1 {
            margin-bottom: 20px;
        }
        .modal form {
            margin: 0;
        }
        .modal input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .modal input[type="submit"]:hover {
            background-color: #45a049;
        }
        .modal .close {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .modal .close:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="modal">
        <h1>Cancel Booking</h1>
        <?php if ($cancellation_message): ?>
            <p><?php echo $cancellation_message; ?></p>
            <button class="close" onclick="closeModal()">Close</button>
            <script>
                alert("<?php echo $cancellation_message; ?>");
                window.location.href = "booking_management.php";
            </script>
        <?php else: ?>
            <form method="post" action="delete_booking.php">
                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                <input type="submit" value="刪除預訂">
                <button type="button" class="close" onclick="closeModal()">取消</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        function closeModal() {
            window.history.back();
        }
    </script>
</body>
</html>
