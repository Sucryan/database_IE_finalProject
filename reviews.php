<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['id'])) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = $_POST['booking_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $return_location = $_POST['return_location'];
    $review_rating = $_POST['review_rating'];
    $review_comments = $_POST['review_comments'];
    $user_id = $_SESSION['id'];
    $review_date = date('Y-m-d H:i:s');

    // 新增評分
    $sql_review = "INSERT INTO reviews (booking_id, user_id, vehicle_id, review_rating, review_comments, review_date) 
                   VALUES ('$booking_id', '$user_id', '$vehicle_id', '$review_rating', '$review_comments', '$review_date')";
    $result_review = mysqli_query($conn, $sql_review);

    if ($result_review) {
        // 使用 vehicle_id 獲取車輛的詳細信息
        $sql_get_vehicle = "SELECT vehicle_type, vehicle_model FROM vehicles WHERE vehicle_id = '$vehicle_id'";
        $result_get_vehicle = mysqli_query($conn, $sql_get_vehicle);

        if ($result_get_vehicle && mysqli_num_rows($result_get_vehicle) > 0) {
            $vehicle_info = mysqli_fetch_assoc($result_get_vehicle);
            $vehicle_type = $vehicle_info['vehicle_type'];
            $vehicle_model = $vehicle_info['vehicle_model'];

            // 更新還車地點對應的車輛的可用性
            $sql_update_vehicle = "UPDATE vehicles SET availability = availability + 1 
                                   WHERE vehicle_type = '$vehicle_type' 
                                   AND vehicle_model = '$vehicle_model' 
                                   AND position_name = '$return_location'";
            $result_update_vehicle = mysqli_query($conn, $sql_update_vehicle);

            if ($result_update_vehicle) {
                echo "<script>
                        alert('評分成功，車輛已成功歸還');
                        window.location.href = 'member.php';
                      </script>";
            } else {
                echo "更新車輛可用性失敗：" . mysqli_error($conn);
            }
        } else {
            echo "獲取車輛詳細信息失敗：" . mysqli_error($conn);
        }
    } else {
        echo "新增評分失敗：" . mysqli_error($conn);
    }
} else {
    $booking_id = $_GET['booking_id'];
    $vehicle_id = $_GET['vehicle_id'];

    // 獲取還車地點選項
    $sql_positions = "SELECT position_name FROM positions";
    $result_positions = mysqli_query($conn, $sql_positions);

    if (!$result_positions) {
        die('查詢還車地點失敗: ' . mysqli_error($conn));
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>提交評分</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: url('24.jpg') no-repeat center center fixed;
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
                width: 400px;
                box-sizing: border-box;
            }
            .form-container h2 {
                text-align: center;
                margin-bottom: 20px;
            }
            .form-container label {
                display: block;
                margin-top: 10px;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .form-container select,
            .form-container input[type="number"],
            .form-container textarea {
                width: 100%;
                padding: 10px;
                margin-top: 5px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }
            .form-container input[type="submit"] {
                width: 100%;
                padding: 10px;
                margin-top: 20px;
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
            <h2>提交評分</h2>
            <form method="post" action="reviews.php">
                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                <label for="return_location">選擇還車地點：</label>
                <select name="return_location" id="return_location" required>
                    <?php while ($row = mysqli_fetch_assoc($result_positions)) { ?>
                        <option value="<?php echo $row['position_name']; ?>"><?php echo $row['position_name']; ?></option>
                    <?php } ?>
                </select>
                <label for="review_rating">評分（1-5）：</label>
                <input type="number" name="review_rating" id="review_rating" min="1" max="5" required>
                <label for="review_comments">評價：</label>
                <textarea name="review_comments" id="review_comments" required></textarea>
                <input type="submit" value="提交評分">
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
