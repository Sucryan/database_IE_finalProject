<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>更新資料</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('5.jpg') no-repeat center center fixed;
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
            max-width: 800px;
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
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .form-group {
            width: 48%;
            margin-bottom: 20px;
        }
        .form-group-full {
            width: 100%;
            margin-bottom: 20px;
        }
        .form-group label, .form-group-full label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group-full input[type="text"],
        .form-group-full input[type="password"],
        .form-group-full textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group-full textarea {
            resize: vertical;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function validateForm() {
            var email = document.getElementById("email").value;
            var sex = document.getElementById("sex").value;
            var phone = document.getElementById("phone").value;
            var birthday = document.getElementById("birthday").value;
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirm_password").value;

            var emailPattern = /^[^@]+@[^@]+$/;
            var phonePattern = /^\d{10}$/;
            var sexPattern = /^(male|female)$/;

            if (password !== confirmPassword) {
                alert("密碼不一致");
                return false;
            }

            if (!emailPattern.test(email)) {
                alert("Email格式錯誤");
                return false;
            }

            if (!sexPattern.test(sex)) {
                alert("性別格式錯誤\n應為male\\female");
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
    <div class="container">
        <div class="header">
            <h2>更新資料</h2>
            <div>
                <a href="member.php">返回會員資料</a>
                <a href="logout.php">登出</a>
            </div>
        </div>
        <?php
        include("mysql_connect.inc.php");

        if (isset($_SESSION['id']) && $_SESSION['id'] != null) {
            $id = $_SESSION['id'];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // 處理表單提交
                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                $country = $_POST['country'];
                $sex = $_POST['sex'];
                $birthday = $_POST['birthday'];
                $phone = $_POST['phone'];
                $note = $_POST['note'];

                $sql = "UPDATE Users SET 
                        username='$username', 
                        password='$password', 
                        email='$email', 
                        country='$country', 
                        sex='$sex', 
                        birthday='$birthday', 
                        phone='$phone', 
                        note='$note' 
                        WHERE id='$id'";

                if (mysqli_query($conn, $sql)) {
                    echo "<script>
                            alert('修改完成');
                            window.location.href = 'member.php';
                          </script>";
                    exit();
                } else {
                    echo '更新失敗: ' . mysqli_error($conn);
                }
            } else {
                $sql = "SELECT * FROM Users WHERE id = '$id'";
                $result = mysqli_query($conn, $sql);

                if (!$result) {
                    die('Query failed: ' . mysqli_error($conn));
                }

                $row = mysqli_fetch_assoc($result);
            }
        } else {
            echo '您無權觀看此頁面!';
            echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
            exit();
        }
        ?>

        <form method="post" action="update.php" onsubmit="return validateForm()">
            <div class="form-group-full">
                <label for="id">ID：</label>
                <input type="text" id="id" name="id" value="<?php echo $row['id']; ?>" disabled />
            </div>

            <div class="form-group">
                <label for="username">Username：</label>
                <input type="text" id="username" name="username" value="<?php echo $row['username']; ?>" required />
            </div>

            <div class="form-group">
                <label for="password">Password：</label>
                <input type="password" id="password" name="password" value="<?php echo $row['password']; ?>" required />
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password：</label>
                <input type="password" id="confirm_password" name="confirm_password" required />
            </div>

            <div class="form-group">
                <label for="email">Email：</label>
                <input type="text" id="email" name="email" value="<?php echo $row['email']; ?>" required />
            </div>

            <div class="form-group">
                <label for="country">Country：</label>
                <input type="text" id="country" name="country" value="<?php echo $row['country']; ?>" />
            </div>

            <div class="form-group">
                <label for="sex">Sex：</label>
                <input type="text" id="sex" name="sex" value="<?php echo $row['sex']; ?>" />
            </div>

            <div class="form-group">
                <label for="birthday">Birthday：</label>
                <input type="date" id="birthday" name="birthday" value="<?php echo $row['birthday']; ?>" />
            </div>

            <div class="form-group">
                <label for="phone">Phone：</label>
                <input type="text" id="phone" name="phone" value="<?php echo $row['phone']; ?>" />
            </div>

            <div class="form-group-full">
                <label for="note">Note：</label>
                <textarea id="note" name="note"><?php echo $row['note']; ?></textarea>
            </div>

            <input type="submit" value="更新資料" />
        </form>
    </div>
</body>
</html>
