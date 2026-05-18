<?php
session_start();

function isAdmin() {
    return isset($_SESSION['admin_id']);
}

function requireAdmin() {
    if (!isAdmin()) {
        header('Location: /Instant_Football/php/admin/login.php?error=acceso');
        exit;
    }
}
?>