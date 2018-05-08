<?php
session_start();

require __DIR__ . '/../src/Database.php';

//if conversation id not set then create new conversation and after that create new message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST);

    //TODO: valid all
    $message = $_POST['message'];
    $convId = $_POST['convId'];
    $senderId = $_POST['senderId'];

    $msg = new Message();
    $msg->setSenderId($senderId);
    $msg->setConversationId($convId);
    $msg->setMessage($message);

    if ($msg->saveToDB()) {
        header('Location: LoginController.php');
    }

}