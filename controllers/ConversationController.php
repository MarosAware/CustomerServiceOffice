<?php
session_start();

require __DIR__ . '/../src/Database.php';

//create new conversation

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['convId'])) {

    $subject = $_POST['subject'];
    $senderId = $_POST['senderId'];
    $conv = new Conversation();

    if ($conv->setClientId($senderId) && $conv->setSubject($subject)) {
        if ($conv->saveToDB()) {
            header('Location: LoginController.php');
        } else {
            echo 'Something goes wrong. Try again later.';
        }
    } else {
        echo 'Invalid input. Subject must be 1-99 character.';
    }


    if ($conv->saveToDB()) {
        header('Location: LoginController.php');
    }


}

//Assign support user to conversation

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convId'])) {



    $supportId = (int) $_POST['supportId'];
    $convId = $_POST['convId'];

//    var_dump($supportId);
//    var_dump($convId);
//
//    var_dump($_POST);

    if (Conversation::isValidId($supportId) && Conversation::isValidId($convId)) {

        $loadedConversation = Conversation::loadConversationById($convId);
        $loadedConversation->setSupportId($supportId);

//        var_dump($loadedConversation);
        if ($loadedConversation->saveToDB()) {

            header('Location: LoginController.php');
        } else {
            echo 'Something goes wrong. Try again later.';
        }

    } else {
        echo 'Invalid input. Support and Conversation id must be numeric value.';
    }

}