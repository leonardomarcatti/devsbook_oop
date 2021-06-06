<?php
    require_once 'config.php';
    $_SESSION['token'] = '';
    header('location: ' . $base);
    exit;
?>