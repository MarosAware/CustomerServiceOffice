<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';

$index = new Template(__DIR__ . '/../templates/index.tpl');
$logout = new Template(__DIR__ . '/../templates/logout.tpl');
$content = new Template(__DIR__ . '/../templates/client_content.tpl');

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'client') {
    header('Location: LoginController.php');
}

//Tutaj dodaj kod

//load All user conversation

$user = unserialize($_SESSION['user']);
$loggedUserId = $user->getId();
$allConv = Conversation::loadAllConversationByClientId($user->getId());


foreach ($allConv as $conv) {
    $row = new Template(__DIR__ . '/../templates/conversation_row.tpl');
    foreach ($conv as $key => $value) {
        $row->add($key, $value);
    }

    $rowsTemplate[] = $row;
}

$rowsContent = Template::joinTemplates($rowsTemplate);



////convId GET

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['convId'])) {
    if (strlen($_GET['convId']) > 0 && is_numeric($_GET['convId'])) {

        $oneMessage = Message::loadAllMessagesByConversationId($_GET['convId']);

//        var_dump($oneMessage);

        if ($oneMessage) {
            foreach($oneMessage as $message) {
                $row = new Template(__DIR__ . '/../templates/message.tpl');
                foreach ($message as $key => $value) {
                    $row->add($key, $value);
                }
                $rowsTemplateMsg[] = $row;
            }

            $rowsMessages = Template::joinTemplates($rowsTemplateMsg);
        }

        $conv = Conversation::loadConversationById($_GET['convId']);


        $messageForm = new Template(__DIR__ . '/../templates/single_message.tpl');
        $messageForm->add('convId', $_GET['convId']);
        $messageForm->add('subject', $conv->getSubject());
        $messageForm->add('senderId', $loggedUserId);

        //var_dump($messageForm);
        $content->add('convForm', '');
        $content->add('messageForm', $messageForm->parse());


    } else {
        echo 'Invalid GET parameter';
    }
} else { //No convId in get
    $convId = $allConv[0]['id'];

    $oneMessage = Message::loadAllMessagesByConversationId($convId);

    if ($oneMessage) {
        foreach($oneMessage as $message) {
            $row = new Template(__DIR__ . '/../templates/message.tpl');
            foreach ($message as $key => $value) {
                $row->add($key, $value);
            }
            $rowsTemplateMsg[] = $row;
        }

        $rowsMessages = Template::joinTemplates($rowsTemplateMsg);
    }

    $convForm = new Template(__DIR__ . '/../templates/single_conversation.tpl');

    $convForm->add('senderId', $loggedUserId);
    $content->add('messageForm', '');
    $content->add('convForm', $convForm->parse());
}


$content->add('conversations', $rowsContent);
$content->add('messages', isset($rowsMessages) ? $rowsMessages : '');
$index->add('logout', $logout->parse());
$index->add('content', $content->parse());


echo $index->parse();
