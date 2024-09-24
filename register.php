<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('3.jpg') no-repeat center center fixed;
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
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .form-group {
            width: 48%;
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group span {
            font-size: 12px;
            color: #888;
        }
        .form-group-full {
            width: 100%;
            margin-bottom: 10px;
        }
        .form-group-full input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group-full input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function validateForm() {
            var id = document.getElementById("id").value;
            var email = document.getElementById("email").value;
            var sex = document.getElementById("sex").value;
            var phone = document.getElementById("phone").value;
            var birthday = document.getElementById("birthday").value;

            var idPattern = /^[a-zA-Z]\d{9}$/;
            var password = document.getElementById("pw").value;
            var confirmPassword = document.getElementById("pw2").value;
            var emailPattern = /^[^@]+@[^@]+$/;
            var phonePattern = /^\d{10}$/;
            var sexPattern = /^(male|female)$/;

            if (!idPattern.test(id)) {
                alert("請輸入身分證字號");
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
        <h1>Register</h1>
        <form name="form" method="post" action="register_finish.php" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="id">ID：</label>
                <input type="text" name="id" id="id" required />
                <span>（例如：A123456789）</span>
            </div>
            <div class="form-group">
                <label for="username">Username：</label>
                <input type="text" name="username" id="username" required />
                <span>（例如：user123）</span>
            </div>

            <div class="form-group">
                <label for="pw">Password：</label>
                <input type="password" name="pw" id="pw" required />
                <span>（請輸入密碼）</span>
            </div>
            <div class="form-group">
                <label for="pw2">Enter password again：</label>
                <input type="password" name="pw2" id="pw2" required />
                <span>（請再次輸入密碼）</span>
            </div>

            <div class="form-group">
                <label for="email">Email：</label>
                <input type="email" name="email" id="email" required />
                <span>（例如：example@mail.com）</span>
            </div>
            <div class="form-group">
                <label for="country">Country：</label>
                <input type="text" name="country" id="country" />
                <span>（例如：Taiwan）</span>
            </div>

            <div class="form-group">
                <label for="sex">Sex：</label>
                <input type="text" name="sex" id="sex" required />
                <span>（例如：Male/Female）</span>
            </div>
            <div class="form-group">
                <label for="birthday">Birthday：</label>
                <input type="date" name="birthday" id="birthday" required />
                <span>（例如：20020101）</span>
            </div>

            <div class="form-group">
                <label for="phone">Phone：</label>
                <input type="text" name="phone" id="phone" required />
                <span>（例如：0900000000）</span>
            </div>
            <div class="form-group">
                <label for="note">Note：</label>
                <input type="text" name="note" id="note" />
                <span>（例如：任何備註）</span>
            </div>

            <div class="form-group-full">
                <input type="submit" name="button" value="確定" />
            </div>
        </form>
    </div>
</body>
</html>
