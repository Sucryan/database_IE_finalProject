<?php session_start(); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
// 資料庫設定
// 資料庫位置
$db_server = "localhost";
// 資料庫名稱
$db_name = "project";
// 資料庫管理者帳號
$db_user = "root";
// 資料庫管理者密碼
$db_passwd = "a25917429";

// 對資料庫連線
$conn = new mysqli($db_server, $db_user, $db_passwd, $db_name);

// 檢查連線
if ($conn->connect_error) {
    die("無法對資料庫連線: " . $conn->connect_error);
}

// 資料庫連線採UTF8
if (!$conn->set_charset("utf8")) {
    die("無法設定資料庫字符集: " . $conn->error);
}

?>
