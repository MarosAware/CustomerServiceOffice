<?php

$content = new Template('templates/client_content.tpl');

//Jeśli $_GET nie posiada paramterów:
    //Wczytanie najnowszej konwersacji, wszystkich wiadomości i plików do niej przypisanych
//Jeśli $_GET posiada parametrs convId:
    //Wczytanie konwersacji o id = convId, wszystkich wiadomości i plików do niej przypisanych
//Jeśli $_POST
