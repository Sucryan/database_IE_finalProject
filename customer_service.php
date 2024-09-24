<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=manager_login.php>';
    exit();
}

$sql = "SELECT r.review_id, r.booking_id, r.user_id, r.vehicle_id, r.review_rating, r.review_comments, u.username, v.vehicle_model,u.phone
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN vehicles v ON r.vehicle_id = v.vehicle_id";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('查詢失敗: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('14.jpg') no-repeat center center fixed;
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
        h1 {
            text-align: center;
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
        tr:hover {
            background-color: #f1f1f1;
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
            margin: 10px;
        }
        .btn-container a:hover {
            background-color: #45a049;
        }
        .logout-btn {
            background-color: #f44336;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Customer Reviews</h1>
        <table>
            <tr>
                <th>Review ID</th>
                <th>Booking ID</th>
                <th>Customer Username</th>
                <th>Vehicle Model</th>
                <th>Rating</th>
                <th>Comments</th>
                <th>Phone</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['review_id']; ?></td>
                <td><?php echo $row['booking_id']; ?></td>
                <td><?php echo $row['username']; ?></td>
                <td><?php echo $row['vehicle_model']; ?></td>
                <td><?php echo $row['review_rating']; ?></td>
                <td><?php echo $row['review_comments']; ?></td>
                <td><?php echo $row['phone']; ?></td>
            </tr>
            <?php } ?>
        </table>
        <div class="btn-container">
            <a href="system_manager.php">Back to Management Home</a>
            <a href="manager_logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>
