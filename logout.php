<?php
    require_once(__DIR__ . '/initialization.php');
    session_start();

    unset($_SESSION['token']);
    setcookie('token', '', time() - 86400, ROOT_URL);
    session_destroy();
    header('Location: ' . ROOT_URL);
    exit();

