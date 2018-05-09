<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';

$index = new Template(__DIR__ . '/../templates/index.tpl');
$logout = new Template(__DIR__ . '/../templates/logout.tpl');
$content = new Template(__DIR__ . '/../templates/client_content.tpl');


$user = unserialize($_SESSION['user']);
$loggedUserId = $user->getId();

//Function for make template row for message

function makeTemplateRowsForMsg($allMessage) {
    global $user;

    foreach($allMessage as $message) {
        $row = new Template(__DIR__ . '/../templates/message.tpl');

        $login = $message['senderId'] === $user->getId() ? 'You' : '<b>SUPPORT</b>';
        $class = '';

        if ($message['senderId'] !== $user->getId()) {
            $class = $message['isRead'] == 0 ? " class='notRead'" : '';
        }


        foreach ($message as $key => $value) {
            $row->add($key, $value);
            $row->add('login', $login);
            $row->add('class', $class);
        }
        $rowsTemplateMsg[] = $row;
    }
    $rowsMessages = Template::joinTemplates($rowsTemplateMsg);
    return $rowsMessages;
}


if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'client') {
    header('Location: LoginController.php');
}



//Tutaj dodaj kod


//load All user conversation
$allConv = Conversation::loadAllConversationByClientId($user->getId());


////convId GET

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['convId'])) {
    if (strlen($_GET['convId']) > 0 && is_numeric($_GET['convId'])) {

        $allMessage = Message::loadAllMessagesByConversationId($_GET['convId']);

        //var_dump($allMessage);
//
//        $login = User::getLoginById($allMessage[0]['senderId']);
//
//        var_dump($login);

        if ($allMessage) {
            foreach ($allMessage as $message) {
                if ($message['senderId'] !== $user->getId()) {

                    $loadedMessage = Message::loadMessageById($message['id']);
                    $loadedMessage->setIsRead(1);
                    $loadedMessage->saveToDB();
                }
            }
            $rowsMessages = makeTemplateRowsForMsg($allMessage);
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

        $rowsMessages = makeTemplateRowsForMsg($oneMessage);
    }

    $convForm = new Template(__DIR__ . '/../templates/single_conversation.tpl');

    $convForm->add('senderId', $loggedUserId);
    $content->add('messageForm', '');
    $content->add('convForm', $convForm->parse());

}





foreach ($allConv as $conv) {
    $row = new Template(__DIR__ . '/../templates/conversation_row.tpl');

    $loadedNotRead = Message::loadAllNotReadMsgByConversationId($conv['id']);
    //var_dump($loadedNotRead);

    $countNotRead = 0;
    if ($loadedNotRead) {
        foreach ($loadedNotRead as $msg) {
            if ($msg['senderId'] !== $user->getId()) {
                $countNotRead++;
            }
        }
    }

    $newMessage = $countNotRead ? "<td class='notRead'>($countNotRead new)</td>" : '';

    foreach ($conv as $key => $value) {
        $row->add($key, $value);
        $row->add('controller', 'ClientController');
        $row->add('newMessages', $newMessage);
    }

    $rowsTemplate[] = $row;
}

$rowsContent = Template::joinTemplates($rowsTemplate);



$content->add('conversations', $rowsContent);
$content->add('messages', isset($rowsMessages) ? $rowsMessages : '');
$index->add('logout', $logout->parse());
$index->add('content', $content->parse());


echo $index->parse();
