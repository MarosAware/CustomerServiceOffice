<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['role']);
}

header('Location: LoginController.php');

