<?php
session_start();
session_destroy();
header("Location: /src/pages/auth/login.php");
exit();
?>
