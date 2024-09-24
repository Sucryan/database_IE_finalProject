<?php
session_start();
include("mysql_connect.inc.php");

// 確保只有管理員可以訪問此頁面
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    echo '請先登錄管理員賬戶！';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=manager_login.php>';
    exit();
}

// 處理添加車輛的請求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_model = $_POST['vehicle_model'];
    $availability = $_POST['availability'];
    $position_name = $_POST['position_name'];

    // 檢查 vehicle_id 是否已經存在
    $check_sql = "SELECT * FROM vehicles WHERE vehicle_id = '$vehicle_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('車輛ID已存在');
                window.history.back();
              </script>";
    } else {
        $sql = "INSERT INTO vehicles (vehicle_id, vehicle_type, vehicle_model, availability, position_name)
                VALUES ('$vehicle_id', '$vehicle_type', '$vehicle_model', '$availability', '$position_name')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('車輛添加成功');
                    window.location.href = 'vehicle_management.php';
                  </script>";
        } else {
            echo "車輛添加失敗: " . mysqli_error($conn);
        }
    }
}


// 處理更新車輛的請求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $vehicle_type = $_POST['vehicle_type'];
    $vehicle_model = $_POST['vehicle_model'];
    $availability = $_POST['availability'];
    $position_name = $_POST['position_name'];

    // 檢查 vehicle_id 是否存在
    $check_sql = "SELECT * FROM vehicles WHERE vehicle_id = '$vehicle_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) == 0) {
        echo "<script>
                alert('車輛ID不存在');
                window.history.back();
              </script>";
    } else {
        $sql = "UPDATE vehicles SET vehicle_type='$vehicle_type', vehicle_model='$vehicle_model', availability='$availability', position_name='$position_name'
                WHERE vehicle_id='$vehicle_id'";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('車輛更新成功');
                    window.location.href = 'vehicle_management.php';
                  </script>";
        } else {
            echo "車輛更新失敗: " . mysqli_error($conn);
        }
    }
}


// 處理刪除車輛的請求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_vehicle'])) {
    $vehicle_id = $_POST['vehicle_id'];

    // 檢查 vehicle_id 是否存在
    $check_sql = "SELECT * FROM vehicles WHERE vehicle_id = '$vehicle_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) == 0) {
        echo "<script>
                alert('車輛ID不存在');
                window.history.back();
              </script>";
    } else {
        $sql = "DELETE FROM vehicles WHERE vehicle_id='$vehicle_id'";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('車輛刪除成功');
                    window.location.href = 'vehicle_management.php';
                  </script>";
        } else {
            echo "車輛刪除失敗: " . mysqli_error($conn);
        }
    }
}


// 處理增加車輛可用性的請求
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_availability'])) {
    $vehicle_id = $_POST['vehicle_id'];
    $availability = $_POST['availability'];

    // 檢查 vehicle_id 是否存在
    $check_sql = "SELECT * FROM vehicles WHERE vehicle_id = '$vehicle_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) == 0) {
        echo "<script>
                alert('車輛ID不存在');
                window.history.back();
              </script>";
    } else {
        $sql = "UPDATE vehicles SET availability = availability + $availability WHERE vehicle_id = '$vehicle_id'";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('車輛可用性增加成功');
                    window.location.href = 'vehicle_management.php';
                  </script>";
        } else {
            echo "車輛可用性增加失敗: " . mysqli_error($conn);
        }
    }
}


// 獲取 position_name 選項
$sql_positions = "SELECT position_name FROM positions";
$result_positions = mysqli_query($conn, $sql_positions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vehicle Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('26.jpg') no-repeat center center fixed;
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
        h1, h2 {
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
        form input[type="text"], form input[type="number"], form select, form input[type="submit"] {
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
            text-decoration:none;
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
        <h1>Vehicle Management</h1>
        <h2>Add Vehicle</h2>
        <form method="post" action="vehicle_management.php">
            <label for="vehicle_id">Vehicle ID：</label>
            <input type="text" name="vehicle_id" id="vehicle_id" required>
            <label for="vehicle_type">Vehicle Type：</label>
            <input type="text" name="vehicle_type" id="vehicle_type" required>
            <label for="vehicle_model">Vehicle Model：</label>
            <input type="text" name="vehicle_model" id="vehicle_model" required>
            <label for="availability">Availability：</label>
            <input type="number" name="availability" id="availability" required>
            <label for="position_name">Position Name：</label>
            <select name="position_name" id="position_name" required>
                <?php
                if (mysqli_num_rows($result_positions) > 0) {
                    while ($row = mysqli_fetch_assoc($result_positions)) {
                        echo "<option value='{$row['position_name']}'>{$row['position_name']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="add_vehicle" value="Add Vehicle">
        </form>

        <h2>Update Vehicle</h2>
        <form method="post" action="vehicle_management.php">
            <label for="vehicle_id">Vehicle ID：</label>
            <input type="number" name="vehicle_id" id="vehicle_id" required>
            <label for="vehicle_type">Vehicle Type：</label>
            <input type="text" name="vehicle_type" id="vehicle_type" required>
            <label for="vehicle_model">Vehicle Model：</label>
            <input type="text" name="vehicle_model" id="vehicle_model" required>
            <label for="availability">Availability：</label>
            <input type="number" name="availability" id="availability" required>
            <label for="position_name">Position Name：</label>
            <select name="position_name" id="position_name" required>
                <?php
                // 重新查詢position_name選項
                $result_positions = mysqli_query($conn, $sql_positions);
                if (mysqli_num_rows($result_positions) > 0) {
                    while ($row = mysqli_fetch_assoc($result_positions)) {
                        echo "<option value='{$row['position_name']}'>{$row['position_name']}</option>";
                    }
                }
                ?>
            </select>
            <input type="submit" name="update_vehicle" value="Update Vehicle">
        </form>

        <h2>Delete Vehicle</h2>
        <form method="post" action="vehicle_management.php">
            <label for="vehicle_id">Vehicle ID：</label>
            <input type="number" name="vehicle_id" id="vehicle_id" required>
            <input type="submit" name="delete_vehicle" value="Delete Vehicle">
        </form>

        <h2>Add Availability</h2>
        <form method="post" action="vehicle_management.php">
            <label for="vehicle_id">Vehicle ID：</label>
            <input type="text" name="vehicle_id" id="vehicle_id" required>
            <label for="availability">Increase Availability by：</label>
            <input type="number" name="availability" id="availability" required>
            <input type="submit" name="add_availability" value="Increase Availability">
        </form>

        <h2>Vehicle List</h2>
        <?php
        $sql = "SELECT * FROM vehicles";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>Vehicle Type</th>
                        <th>Vehicle Model</th>
                        <th>Availability</th>
                        <th>Position Name</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['vehicle_id']}</td>
                        <td>{$row['vehicle_type']}</td>
                        <td>{$row['vehicle_model']}</td>
                        <td>{$row['availability']}</td>
                        <td>{$row['position_name']}</td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No vehicles found.</p>";
        }
        ?>
        <div class="btn-container">
            <a href="system_manager.php">Back to Management Home</a>
        </div>
    </div>
</body>
</html>
