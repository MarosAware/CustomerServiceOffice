<?php

session_start();
require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';

//$user = User::loadUserById(2);
//$user->setPassword('test');
//$user->saveToDB();
//var_dump($user->getPassword());
//die();

//unset($_SESSION['role']);
//unset($_SESSION['user']);
//die();

//Tutaj dodaj kod
//Sprawdzic czy uzytkownik jest zalogowany - odpowiedni kontroler lub strona logowania

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


//Odebrać logowanie POST

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
            echo 'Invalid login or password';
        }

    } else {
        echo 'Your login or password empty.';
    }

}



$index = new Template(__DIR__ . '/../templates/index.tpl');

$content = new Template(__DIR__ . '/../templates/login.tpl');

$index->add('content', $content->parse());
$index->add('logout', '');

echo $index->parse();