<?php
session_start();

// 生成隨機驗證碼
$code = rand(1000, 9999);
$_SESSION['captcha'] = $code;

// 創建圖片
$image = imagecreatetruecolor(100, 30);

// 設置顏色
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);

// 填充背景
imagefilledrectangle($image, 0, 0, 100, 30, $bg_color);

// 添加文本
imagestring($image, 5, 30, 8, $code, $text_color);

// 設置header類型
header('Content-Type: image/png');

// 輸出圖片
imagepng($image);

// 釋放內存
imagedestroy($image);
?>
