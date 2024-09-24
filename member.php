<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('9.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 1200px;
            overflow: auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header a {
            color: #007BFF;
            text-decoration: none;
            margin-left: 20px;
        }
        .header a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
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
        h2 {
            text-align: center;
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
    <div class="container">
        <div class="header">
            <h2>會員資料</h2>
            <div>
                <a href="update.php">修改資料</a>
                <a href="select_location.php">瀏覽可用車輛</a>
                <a href="logout.php">登出</a>
            </div>
        </div>
        <?php
        include("mysql_connect.inc.php");

        if (isset($_SESSION['id']) && $_SESSION['id'] != null) {
            $id = $_SESSION['id'];
            $sql = "SELECT * FROM Users WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die('查詢失敗: ' . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                echo "<table>
                        <tr>
                            <th>ID</th>
                            <td>{$row['id']}</td>
                        </tr>
                        <tr>
                            <th>使用者名稱</th>
                            <td>{$row['username']}</td>
                        </tr>
                        <tr>
                            <th>密碼</th>
                            <td>{$row['password']}</td>
                        </tr>
                        <tr>
                            <th>電子郵件</th>
                            <td>{$row['email']}</td>
                        </tr>
                        <tr>
                            <th>國家</th>
                            <td>{$row['country']}</td>
                        </tr>
                        <tr>
                            <th>性別</th>
                            <td>{$row['sex']}</td>
                        </tr>
                        <tr>
                            <th>生日</th>
                            <td>{$row['birthday']}</td>
                        </tr>
                        <tr>
                            <th>電話</th>
                            <td>{$row['phone']}</td>
                        </tr>
                        <tr>
                            <th>備註</th>
                            <td>{$row['note']}</td>
                        </tr>
                    </table>";

                // 顯示用戶的預訂記錄
                $sql_bookings = "SELECT b.booking_id, v.vehicle_id, v.vehicle_type, v.vehicle_model, b.pick_up_location, b.pick_up_time, b.return_time, r.review_id 
                                 FROM bookings b 
                                 JOIN vehicles v ON b.vehicle_id = v.vehicle_id 
                                 LEFT JOIN reviews r ON b.booking_id = r.booking_id
                                 WHERE b.user_id = '$id'";
                $result_bookings = mysqli_query($conn, $sql_bookings);

                if (mysqli_num_rows($result_bookings) > 0) {
                    echo "<h2>預訂記錄</h2>";
                    echo "<table>
                            <tr>
                                <th>訂單 ID</th>
                                <th>車輛 ID</th>
                                <th>車輛類型</th>
                                <th>車輛型號</th>
                                <th>取車地點</th>
                                <th>取車時間</th>
                                <th>還車時間</th>
                                <th>狀態</th>
                                <th>動作</th>
                            </tr>";
                    while ($booking = mysqli_fetch_assoc($result_bookings)) {
                        $status = $booking['review_id'] ? "已完成" : "進行中";
                        echo "<tr>
                                <td>{$booking['booking_id']}</td>
                                <td>{$booking['vehicle_id']}</td>
                                <td>{$booking['vehicle_type']}</td>
                                <td>{$booking['vehicle_model']}</td>
                                <td>{$booking['pick_up_location']}</td>
                                <td>{$booking['pick_up_time']}</td>
                                <td>{$booking['return_time']}</td>
                                <td>{$status}</td>
                                <td>";
                            if ($status == "已完成") {
                                echo "已評分";
                            } else {
                                echo "<a href='reviews.php?booking_id={$booking['booking_id']}&vehicle_id={$booking['vehicle_id']}'>完成訂單並評分</a> | <a href='cancel_booking.php?booking_id={$booking['booking_id']}'>取消訂單</a>";
                            }
                                
                        echo "</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>目前沒有預訂記錄。</p>";
                }
            } else {
                echo "查詢失敗或沒有資料!";
            }
        } else {
            echo '您無權限觀看此頁面!';
            echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
        }
        ?>
    </div>
</body>
</html>
