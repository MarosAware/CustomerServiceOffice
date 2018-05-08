<?php
session_start();

require __DIR__ . '/../src/Database.php';

//create new conversation

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //TODO: valid all
    var_dump($_POST);

    $subject = $_POST['subject'];
    $senderId = $_POST['senderId'];

    $conv = new Conversation();
    $conv->setSubject($subject);
    $conv->setClientId($senderId);

    if ($conv->saveToDB()) {
        header('Location: LoginController.php');
    }


}