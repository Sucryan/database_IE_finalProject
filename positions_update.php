<?php
session_start();
include("mysql_connect.inc.php");

// 确保只有管理员可以访问此页面
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '請先登錄管理員賬戶！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=manager_login.php>';
    exit();
}

// 处理添加位置的请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_position'])) {
    $position_id = $_POST['position_id'];
    $position_name = $_POST['position_name'];

    $sql = "INSERT INTO positions (position_id, position_name) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $position_id, $position_name);

    if ($stmt->execute()) {
        echo "<script>
                alert('位置添加成功');
                window.location.href = 'positions_update.php';
              </script>";
    } else {
        echo "位置添加失敗: " . $stmt->error;
    }
    $stmt->close();
}

// 处理删除位置的请求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_position'])) {
    $position_id = $_POST['position_id'];

    $sql = "DELETE FROM positions WHERE position_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $position_id);

    if ($stmt->execute()) {
        echo "<script>
                alert('位置刪除成功');
                window.location.href = 'positions_update.php';
              </script>";
    } else {
        echo "位置刪除失敗: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Position Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('23.jpg') no-repeat center center fixed;
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
        h1 {
            text-align: center;
        }
        h2 {
            margin-top: 40px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        form label {
            margin: 10px 0 5px;
        }
        form input[type="text"], form input[type="number"], form input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        form input[type="submit"]:hover {
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
    <div class="container">
        <h1>Position Management</h1>
        <h2>Add Position</h2>
        <form method="post" action="positions_update.php">
            <label for="position_id">Position ID：</label>
            <input type="number" name="position_id" id="position_id" required>
            <label for="position_name">Position Name：</label>
            <input type="text" name="position_name" id="position_name" required>
            <input type="submit" name="add_position" value="Add Position">
        </form>

        <h2>Delete Position</h2>
        <form method="post" action="positions_update.php">
            <label for="position_id">Position ID：</label>
            <input type="number" name="position_id" id="position_id" required>
            <input type="submit" name="delete_position" value="Delete Position">
        </form>

        <h2>Position List</h2>
        <?php
        $sql = "SELECT * FROM positions";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>
                    <tr>
                        <th>Position ID</th>
                        <th>Position Name</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['position_id']}</td>
                        <td>{$row['position_name']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No positions found.</p>";
        }
        ?>
        <div class="btn-container">
            <a href="system_manager.php">Back to Management Home</a>
        </div>
    </div>
</body>
</html>
