<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';


if (isset($_SESSION['user'])) {
    header('Location: LoginController.php');
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (User::isValidLogin($login) && User::isValidPassword($password)) {

        $user = User::loadUserByLogin($login);

        if ($user === null) {
            $user = new User();
            $user->setLogin($login);
            $user->setPassword($password);
            $user->saveToDB();


            //TODO: Set some message on success

            $msg = '<p class="alert alert-success">You account was created successful.</p>';
            $_SESSION['msg'] = $msg;
                header('Location: LoginController.php');
        } else {
            $msg = '<p class="alert alert-danger">User already exists. Pick other login.</p>';
        }

    } else {
        $msg = '<p class="alert alert-danger">Your login or password empty.</p>';
    }
}


$index = new Template(__DIR__ . '/../view/index.tpl');
$content = new Template(__DIR__ . '/../view/register.tpl');

$index->add('script', '');
$index->add('msg', isset($msg) ? $msg : '');
$index->add('content', $content->parse());
$index->add('logout', '');

echo $index->parse();
