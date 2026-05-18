<?php
require_once '../../config/auth.php';
session_destroy();
header('Location: /Instant_Football/index.php');
exit;
?>