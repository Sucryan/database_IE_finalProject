<?php
session_start();
include("mysql_connect.inc.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $pw = $_POST['pw'];
    $captcha = $_POST['captcha'];

    if ($captcha == $_SESSION['captcha']) {
        $sql = "SELECT * FROM users WHERE id = '$id' AND password = '$pw' AND is_admin = 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['id'] = $id;
            $_SESSION['admin'] = true; // 設置管理員標記
            echo "<script>
                    alert('登錄成功');
                    window.location.href = 'system_manager.php';
                  </script>";
        } else {
            echo "<script>
                    alert('登錄失敗');
                    window.location.href = 'manager_login.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('驗證碼錯誤');
                window.location.href = 'manager_login.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('19.jpg') no-repeat center center fixed;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .login-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-container img {
            display: block;
            margin: 10px auto;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .login-container .register-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007BFF;
            text-decoration: none;
        }
        .login-container .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Manager Login</h2>
        <form method="post" action="manager_login.php">
            <label for="id">ID：</label>
            <input type="text" name="id" id="id" required>
            <label for="pw">Password：</label>
            <input type="password" name="pw" id="pw" required>
            <label for="captcha">驗證碼：</label>
            <input type="text" name="captcha" id="captcha" required>
            <img src="captcha.php" alt="CAPTCHA">
            <input type="submit" value="Login">
        </form>
        <a class="register-link" href="manager_register.php">register</a>
    </div>
</body>
</html>
