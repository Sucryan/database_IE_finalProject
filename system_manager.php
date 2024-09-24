<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=manager_login.php>';
    exit();
}

$current_username = isset($_SESSION['id']) ? $_SESSION['id'] : 'Unknown User';

$sql = "SELECT * FROM users WHERE id = '$current_username' AND is_admin = 1";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('查詢失敗: ' . mysqli_error($conn));
}

$admin_info = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Management Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('32.jpg') no-repeat center center fixed;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
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
        .user-info {
            text-align: left;
            margin-bottom: 20px;
        }
        .user-info a {
            color: #007BFF;
            text-decoration: none;
        }
        .user-info a:hover {
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
        <div class="user-info">
            <p> <a href="manager_update.php?id=<?php echo $_SESSION['id']; ?>">修改個人資料</a></p>
        </div>
        <h1>Management Home</h1>
        <div class="btn-container">
            <a href="vehicle_management.php">Vehicle Management</a>
            <a href="booking_management.php">Booking Management</a>
            <a href="positions_update.php">Positions Management</a>
            <a href="customer_service.php">Customer Service</a>
            <a href="financial_management.php">Financial Management</a>
            <a href="manager_logout.php" class="logout-btn">Logout</a>
        </div>

        <h2>管理員資訊</h2>
        <?php if ($admin_info): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <td><?php echo $admin_info['id']; ?></td>
                </tr>
                <tr>
                    <th>使用者名稱</th>
                    <td><?php echo $admin_info['username']; ?></td>
                </tr>
                <tr>
                    <th>密碼</th>
                    <td><?php echo $admin_info['password']; ?></td>
                </tr>
                <tr>
                    <th>email</th>
                    <td><?php echo $admin_info['email']; ?></td>
                </tr>
                <tr>
                    <th>國家</th>
                    <td><?php echo $admin_info['country']; ?></td>
                </tr>
                <tr>
                    <th>性別</th>
                    <td><?php echo $admin_info['sex']; ?></td>
                </tr>
                <tr>
                    <th>生日</th>
                    <td><?php echo $admin_info['birthday']; ?></td>
                </tr>
                <tr>
                    <th>電話</th>
                    <td><?php echo $admin_info['phone']; ?></td>
                </tr>
                <tr>
                    <th>備註</th>
                    <td><?php echo $admin_info['note']; ?></td>
                </tr>
            </table>
        <?php else: ?>
            <p>未找到管理員資訊。</p>
        <?php endif; ?>
    </div>
</body>
</html>
