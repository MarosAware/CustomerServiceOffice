<?php
session_start();

require __DIR__ . '/../src/Template.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'client') {
    header('Location: LoginController.php');
}

//Tutaj dodaj kod

$index = new Template(__DIR__ . '/../templates/index.tpl');
$logout = new Template(__DIR__ . '/../templates/logout.tpl');

$content = new Template(__DIR__ . '/../templates/client_content.tpl');

$index->add('content', $content->parse());
$index->add('logout', $logout->parse());

echo $index->parse();
