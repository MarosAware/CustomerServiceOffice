<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';

function decideWhereToRedirectByRole($userRole) {

    if ($userRole === 'client') {
        header('Location: ClientController.php');
    } else if($userRole === 'support') {
        header('Location: SupportController.php');
    } //More Role
}

if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
    decideWhereToRedirectByRole($_SESSION['role']);
}



//Sign in

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (User::isValidLogin($login) && User::isValidPassword($password)) {

        $user = User::signInUser($login, $password);

        if ($user instanceof User) {
            $_SESSION['user'] = serialize($user);
            $userRole = Role::getRoleById($user->getRoleId());
            $_SESSION['role'] = $userRole->getName();

            decideWhereToRedirectByRole($_SESSION['role']);
        } else {
            $msg = '<p class="alert alert-danger">Invalid login or password.</p>';
        }

    } else {
        $msg = '<p class="alert alert-danger">Your login or password empty.</p>';
    }

}


$index = new Template(__DIR__ . '/../view/index.tpl');
$content = new Template(__DIR__ . '/../view/login.tpl');

if (isset($_SESSION['msg'])) {
    $index->add('msg', $_SESSION['msg']);
    unset($_SESSION['msg']);
} else {
    if (isset($msg)) {
        $index->add('msg', $msg);
    } else {
        $index->add('msg', '');
    }

}


$index->add('content', $content->parse());
$index->add('logout', '');

echo $index->parse();