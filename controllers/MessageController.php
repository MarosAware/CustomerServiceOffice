<?php
session_start();

require __DIR__ . '/../src/Database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    var_dump($_POST);

    $message = $_POST['message'];
    $convId = $_POST['convId'];
    $senderId = $_POST['senderId'];

    //More Validation
    if (Conversation::isValidId($senderId) && Conversation::isValidId($convId)) {
        if (Message::isValidMessage($message)) {

            $msg = new Message();
            $msg->setSenderId($senderId);
            $msg->setConversationId($convId);
            $msg->setMessage($message);

            if ($msg->saveToDB()) {
                header('Location: LoginController.php');
            } else {
                echo 'Some problem occurred. Try again later.';
            }
        } else {
            echo 'Invalid message. Valid message is 1-254 characters.';
        }

    } else {
        echo 'Some input empty or invalid.';
    }

}