<?php
session_start();
?>
<!-- 上方語法為啟用session，此語法要放在網頁最前方 -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
// 連接資料庫
include("mysql_connect.inc.php");

$id = $_POST['id'];
$pw = $_POST['pw'];
$captcha = $_POST['captcha'];

// 搜尋資料庫資料
$sql = "SELECT * FROM Users WHERE id = '$id'";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Invalid query: ' . mysqli_error($conn));
}

$row = mysqli_fetch_assoc($result);

// 判斷帳號與密碼是否為空白以及 MySQL 資料庫裡是否有這個會員
if ($id != null && $pw != null && $row) {
    if ($captcha == $_SESSION['captcha']) {
        if ($pw == $row['password']) {
            // 將帳號寫入 session，方便驗證使用者身份
            $_SESSION['id'] = $row['id'];
            echo "<script>alert('登入成功!'); window.location.href='member.php';</script>";
        } else {
            echo "<script>alert('登入失敗! 錯誤的帳號或密碼。'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('驗證碼錯誤!'); window.location.href='login.php';</script>";
    }
} else {
    echo "<script>alert('登入失敗! 請確保所有字段已填寫。'); window.location.href='login.php';</script>";
}
?>
