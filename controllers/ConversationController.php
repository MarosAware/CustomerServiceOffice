<?php
session_start();

require __DIR__ . '/../src/Database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    parse_str(file_get_contents("php://input"), $postVars);

    if (isset($postVars['supportId']) && isset($postVars['convId'])) {


        if (Conversation::isValidId($postVars['supportId']) && Conversation::isValidId($postVars['convId'])) {

            $loadedConversation = Conversation::loadConversationById($postVars['convId']);
            $loadedConversation->setSupportId($postVars['supportId']);

            if ($loadedConversation->saveToDB()) {
                $response = ['success' => [json_decode(json_encode($loadedConversation), true)]];
                //$msg = '<p class="alert alert-success">Conversation assigned successfully.</p>';
            } else {
                //$msg = '<p class="alert alert-danger">Something goes wrong. Try again later.</p>';
                $response = ['error' => 'DB connection error'];
            }

        } else {
            //$msg = '<p class="alert alert-danger">Invalid input. Support and Conversation id must be numeric value.</p>';
            $response = ['inputErr' => 'Invalid input. Support id and conversation id need to be numeric.'];
        }

    } else if (isset($postVars['subject']) && isset($postVars['senderId'])) {


        $conv = new Conversation();


        if ($conv->setClientId($postVars['senderId']) && $conv->setSubject($postVars['subject'])) {
            if ($conv->saveToDB()) {

                $response = ['success' => [json_decode(json_encode($conv), true)]];
                //$msg = '<p class="alert alert-success">New conversation created.</p>';
            } else {
                //$msg = '<p class="alert alert-danger">Something goes wrong. Try again later.</p>';
                $response = ['error' => 'DB connection error'];

            }
        } else {
            //$msg = '<p class="alert alert-danger">Invalid input. Subject must be 1-99 character.</p>';
            $response = ['inputErr' => 'Invalid input. Subject must be 1-99 character.'];

        }

    }



} else {
    $response = ['error' => 'Invalid request method'];
}



header('Content-Type: application/json');


if (isset($response['error'])) {
    if ($response['error'] === 'DB connection error') {
        header("HTTP/1.0 500 Internal Server Error");
    } else {
        header("HTTP/1.0 400 Bad Request");
    }
} elseif (isset($response['inputErr'])) {
    header('HTTP/1.0 422 UNPROCESSABLE ENTITY');
}



echo json_encode($response);