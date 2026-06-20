<?php
require_once __DIR__ . '/../../config/auth.php';
auth_logout();
header("Location: /src/pages/auth/login.php");
exit();
?>
