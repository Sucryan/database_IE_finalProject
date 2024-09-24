<?php
session_start();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
include("mysql_connect.inc.php");

$id = $_POST['id'];
$username = $_POST['username'];
$pw = $_POST['pw'];
$pw2 = $_POST['pw2'];
$email = $_POST['email'];
$country = $_POST['country'];
$sex = $_POST['sex'];
$birthday = $_POST['birthday'];
$phone = $_POST['phone'];
$note = $_POST['note'];

// 判斷帳號密碼是否為空值並確認密碼輸入的正確性
if($id != null && $username != null && $pw != null && $pw2 != null && $pw == $pw2)
{
    // 檢查ID是否重複
    $check_sql = "SELECT id FROM Users WHERE id = '$id'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        echo '此ID已使用!';
        echo '<meta http-equiv=REFRESH CONTENT=2;url=register.php>';
    } else {
        // 新增資料進資料庫語法
        $sql = "INSERT INTO Users (id, username, password, email, country, sex, birthday, phone, note) 
                VALUES ('$id', '$username', '$pw', '$email', '$country', '$sex', '$birthday', '$phone', '$note')";
        
        if(mysqli_query($conn, $sql))
        {
            echo '新增成功!';
            echo '<meta http-equiv=REFRESH CONTENT=2;url=login.php>';
        }
        else
        {
            // 获取错误信息
            $error = mysqli_error($conn);
            echo "新增失敗! 錯誤信息: $error";
            echo '<meta http-equiv=REFRESH CONTENT=2;url=register.php>';
        }
    }
}
else
{
    echo '您無權限觀看此頁面!';
    echo '<meta http-equiv=REFRESH CONTENT=2;url=register.php>';
}
?>
