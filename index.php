<?php

require 'src/Template.php';
$index = new Template('templates/index.tpl');

//Jeśli POST bez parametrów to rejestracja usera
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    require 'controllers/RegisterController.php';
}else{

    //Sprawdzenie roli zalogowanego użytkownika
    $userRole = '';

    if( $userRole == 'support'){
        require 'controllers/SupportController.php';
    }elseif($userRole == 'client'){
        require 'controllers/ClientController.php';
    }else{
        require 'controllers/LoginController.php';
    }
}

var_dump($_SERVER);
var_dump($_GET);
$index->add('content', $content->parse());

echo $index->parse();
