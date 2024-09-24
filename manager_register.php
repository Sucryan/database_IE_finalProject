<?php
include("mysql_connect.inc.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $country = $_POST['country'];
    $sex = $_POST['sex'];
    $birthday = $_POST['birthday'];
    $phone = $_POST['phone'];
    $note = $_POST['note'];

    $sql = "INSERT INTO users (id, username, password, email, country, sex, birthday, phone, note, is_admin)
            VALUES ('$id', '$username', '$password', '$email', '$country', '$sex', '$birthday', '$phone', '$note', 1)";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('註冊成功');
                window.location.href = 'manager_login.php';
              </script>";
    } else {
        echo "註冊失敗: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('20.jpg') no-repeat center center fixed;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container input[type="password"],
        .form-container input[type="email"],
        .form-container input[type="date"],
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 14px;
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
        .form-container a {
            display: block;
            margin-top: 15px;
            color: #007BFF;
            text-decoration: none;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function validateForm() {
            var id = document.getElementById("id").value;
            var email = document.getElementById("email").value;
            var sex = document.getElementById("sex").value;
            var phone = document.getElementById("phone").value;
            var birthday = document.getElementById("birthday").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("password_confirm").value;
            var idPattern = /^[a-zA-Z]\d{9}$/;
            var emailPattern = /^[^@]+@[^@]+$/;
            var phonePattern = /^\d{10}$/;
            var sexPattern = /^(male|female)$/;

            if (!idPattern.test(id)) {
                alert("請輸入正確的身分證字號");
                return false;
            }
            
            if (password !== confirmPassword) {
                alert("密碼不一致");
                return false;
            }

            if (!emailPattern.test(email)) {
                alert("Email格式錯誤");
                return false;
            }

            if (!sexPattern.test(sex)) {
                alert("性別格式錯誤，應為 male 或 female");
                return false;
            }

            if (!phonePattern.test(phone)) {
                alert("電話號碼格式錯誤");
                return false;
            }

            if (!Date.parse(birthday)) {
                alert("生日格式錯誤");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Manager Register</h2>
        <form method="post" action="manager_register.php" onsubmit="return validateForm()">
            <label for="id">ID：</label>
            <input type="text" name="id" id="id" required>
            <label for="username">Username：</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password：</label>
            <input type="password" name="password" id="password" required>
            <label for="password_confirm">Confirm Password：</label>
            <input type="password" name="password_confirm" id="password_confirm" required>
            <label for="email">Email：</label>
            <input type="email" name="email" id="email" required>
            <label for="country">Country：</label>
            <input type="text" name="country" id="country">
            <label for="sex">Sex：</label>
            <input type="text" name="sex" id="sex" required>
            <label for="birthday">Birthday：</label>
            <input type="date" name="birthday" id="birthday" required>
            <label for="phone">Phone：</label>
            <input type="text" name="phone" id="phone" required>
            <label for="note">Note：</label>
            <textarea name="note" id="note"></textarea>
            <input type="submit" value="Register">
        </form>
        <a href="manager_login.php">Back to Login</a>
    </div>
</body>
</html>
