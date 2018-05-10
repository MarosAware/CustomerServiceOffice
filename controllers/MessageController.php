<?php
session_start();

require __DIR__ . '/../src/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
                $msg = '<p class="alert alert-success">Message created and sent.</p>';
            } else {
                $msg = '<p class="alert alert-danger">Some problem occurred. Try again later.</p>';
            }
        } else {
            $msg = '<p class="alert alert-danger">Invalid message. Valid message is 1-254 characters.</p>';
        }

    } else {
        $msg = '<p class="alert alert-danger">Some input empty or invalid.</p>';
    }

}

if (isset($msg)) {
    $_SESSION['msg'] = $msg;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);