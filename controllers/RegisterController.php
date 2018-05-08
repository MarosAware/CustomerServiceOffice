<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';
//Tutaj dodaj kod


if (isset($_SESSION['user'])) {
    header('Location: LoginController.php');
}

//odebranie danych, walidacja

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
            header('Location: LoginController.php');
        } else {
            echo 'User already exists. Pick other login.';
        }

    } else {
        echo 'Your login or password empty.';
    }
}

//polaczenie do db



//dodanie wpisu do odpowiedniej tabeli


//zapis




$index = new Template(__DIR__ . '/../templates/index.tpl');

$content = new Template(__DIR__ . '/../templates/register.tpl');

$index->add('content', $content->parse());
$index->add('logout', '');

echo $index->parse();

