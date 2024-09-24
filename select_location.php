<?php
session_start();
include("mysql_connect.inc.php");

if (!isset($_SESSION['id'])) {
    echo '請先登入！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['location'] = $_POST['location'];
    header('Location: browse_vehicles.php');
    exit();
}

// 从数据库中获取取车地点
$sql = "SELECT position_name FROM positions";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('查詢失敗: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>選擇取車地點</title>
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
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        select, button {
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            max-width: 300px;
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>選擇取車地點</h1>
        <form method="post" action="select_location.php">
            <select name="location">
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <option value="<?php echo $row['position_name']; ?>"><?php echo $row['position_name']; ?></option>
                <?php } ?>
            </select>
            <button type="submit">確定</button>
        </form>
    </div>
</body>
</html>
