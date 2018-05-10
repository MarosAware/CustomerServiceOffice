<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['role']);
    $msg = '<p class="alert alert-success">Log out successful.</p>';
}

if (isset($msg)) {
    $_SESSION['msg'] = $msg;
}

header('Location: LoginController.php');

