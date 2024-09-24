<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['id'])) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
    exit();
}

$user_id = $_SESSION['id'];
$location = isset($_SESSION['location']) ? $_SESSION['location'] : '';
if (empty($location)) {
    echo '請選擇取車地點！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=select_location.php>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_model = $_POST['vehicle_model'];
    $position_name = $_POST['position_name'];
    $pick_up_time = $_POST['pick_up_time'];
    $return_time = $_POST['return_time'];

    // 確認 vehicle_id 是否存在並且可用
    $sql_check = "SELECT vehicle_id, price_multiplier FROM vehicles 
                  WHERE vehicle_id = '$vehicle_id' AND vehicle_type = '$vehicle_type' AND vehicle_model = '$vehicle_model' AND position_name = '$position_name' AND availability > 0";
    $result_check = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        $vehicle = mysqli_fetch_assoc($result_check);
        $vehicle_id = $vehicle['vehicle_id'];
        $price_multiplier = $vehicle['price_multiplier'];
        $base_price = 100; // 基礎價格可以根據需求調整

        // 檢查還車時間是否早於取車時間
        if (strtotime($return_time) <= strtotime($pick_up_time)) {
            echo "<script>
                    alert('還車時間不能早於或等於取車時間。');
                    window.history.back();
                  </script>";
            exit();
        }

        // 計算租賃的天數
        $pick_up_date = new DateTime($pick_up_time);
        $return_date = new DateTime($return_time);
        $interval = $pick_up_date->diff($return_date);
        $days = $interval->days;

        // 根據取車時間調整價格倍數
        $pick_up_month = (int)$pick_up_date->format('m');
        if ($pick_up_month < 6) {
            $price_multiplier *= 0.8; // 6月以前
        } else {
            $price_multiplier *= 1.2; // 6月以後
        }

        // 計算金額
        $amount = $base_price * $price_multiplier * $days;

        $sql = "INSERT INTO bookings (user_id, vehicle_id, pick_up_location, pick_up_time, return_time)
                VALUES ('$user_id', '$vehicle_id', '$location', '$pick_up_time', '$return_time')";

        if (mysqli_query($conn, $sql)) {
            $booking_id = mysqli_insert_id($conn);
            $sql_update = "UPDATE vehicles SET availability = availability - 1 WHERE vehicle_id = '$vehicle_id'";
            mysqli_query($conn, $sql_update);
            echo "<script>
                    alert('預訂成功!');
                    window.location.href = 'payment.php?booking_id=$booking_id&amount=$amount&vehicle_id=$vehicle_id';
                  </script>";
        } else {
            echo '預訂失敗: ' . mysqli_error($conn);
        }
    } else {
        echo '預訂失敗: 車輛不存在或不可用。';
        echo '<meta http-equiv=REFRESH CONTENT=2;url=browse_vehicles.php>';
    }
} else {
    $vehicle_id = $_GET['vehicle_id'];
    $vehicle_type = $_GET['vehicle_type'];
    $vehicle_model = $_GET['vehicle_model'];
    $position_name = $_GET['position_name'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>預訂車輛</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: url('11.jpg') no-repeat center center fixed;
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
            .form-container input[type="datetime-local"],
            .form-container input[type="text"] {
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
            <h2>預訂車輛</h2>
            <form method="post" action="book_vehicle.php">
                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                <input type="hidden" name="vehicle_type" value="<?php echo $vehicle_type; ?>">
                <input type="hidden" name="vehicle_model" value="<?php echo $vehicle_model; ?>">
                <input type="hidden" name="position_name" value="<?php echo $position_name; ?>">
                <label for="pick_up_time">取車時間：</label>
                <input type="datetime-local" name="pick_up_time" id="pick_up_time" required>
                <label for="return_time">還車時間：</label>
                <input type="datetime-local" name="return_time" id="return_time" required>
                <input type="submit" value="預訂">
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
