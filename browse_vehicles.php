<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['id'])) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
    exit();
}

$location = isset($_SESSION['location']) ? $_SESSION['location'] : '';
if (empty($location)) {
    echo '請選擇取車地點！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=select_location.php>';
    exit();
}

$sql = "SELECT * FROM vehicles WHERE position_name = ? ORDER BY vehicle_type";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $location);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die('查詢失敗: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>瀏覽可用車輛</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('4.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a {
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        a:hover {
            color: #45a049;
        }
        .action {
            text-align: center;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>可用車輛</h1>
        <?php if (mysqli_num_rows($result) > 0) { ?>
            <table>
                <tr>
                    <th>車輛ID</th>
                    <th>車輛類型</th>
                    <th>車輛型號</th>
                    <th>剩餘車輛</th>
                    <th class="action">動作</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['vehicle_id']; ?></td>
                    <td><?php echo $row['vehicle_type']; ?></td>
                    <td><?php echo $row['vehicle_model']; ?></td>
                    <td>
                        <?php 
                        if ($row['availability'] == 0) {
                            echo "無現貨車款";
                        } else {
                            echo $row['availability'];
                        }
                        ?>
                    </td>
                    <td class="action">
                        <?php if ($row['availability'] > 0) { ?>
                            <a href="book_vehicle.php?vehicle_id=<?php echo $row['vehicle_id']; ?>&vehicle_type=<?php echo $row['vehicle_type']; ?>&vehicle_model=<?php echo $row['vehicle_model']; ?>&position_name=<?php echo $row['position_name']; ?>">預訂</a>
                        <?php } else { ?>
                            <span style="color: grey;">無法預訂</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>此地目前無可用車輛</p>
            <a href="select_location.php" class="button">返回選擇地點</a>
        <?php } ?>
    </div>
</body>
</html>
