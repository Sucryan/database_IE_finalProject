<?php
session_start();
session_destroy();
echo "<script>
        alert('登出成功');
        window.location.href = 'manager_login.php';
      </script>";
?>
