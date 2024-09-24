<?php
session_start(); // 啟用session

// 清除所有的 session 變量
$_SESSION = array();

// 刪除用戶的 cookie 包括 session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 最後，銷毀 session
session_destroy();

// 重定向到登入頁面
header('Location: login.php');
exit;
?>