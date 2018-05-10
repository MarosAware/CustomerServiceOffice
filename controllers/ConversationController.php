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
            $msg = '<p class="alert alert-success">New conversation created.</p>';
        } else {
            $msg = '<p class="alert alert-danger">Something goes wrong. Try again later.</p>';
        }
    } else {
        $msg = '<p class="alert alert-danger">Invalid input. Subject must be 1-99 character.</p>';
    }

    if (isset($msg)) {
        $_SESSION['msg'] = $msg;
    }

    header('Location: ClientController.php');

}

//Assign support user to conversation

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convId'])) {

    $supportId = (int) $_POST['supportId'];
    $convId = $_POST['convId'];

    if (Conversation::isValidId($supportId) && Conversation::isValidId($convId)) {

        $loadedConversation = Conversation::loadConversationById($convId);
        $loadedConversation->setSupportId($supportId);

        if ($loadedConversation->saveToDB()) {

            $msg = '<p class="alert alert-success">Conversation assigned successfully.</p>';
        } else {
            $msg = '<p class="alert alert-danger">Something goes wrong. Try again later.</p>';
        }

    } else {
        $msg = '<p class="alert alert-danger">Invalid input. Support and Conversation id must be numeric value.</p>';
    }


    if (isset($msg)) {
        $_SESSION['msg'] = $msg;
    }

    header('Location: SupportController.php');
}