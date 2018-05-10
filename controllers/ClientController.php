<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';

$index = new Template(__DIR__ . '/../view/index.tpl');
$logout = new Template(__DIR__ . '/../view/logout.tpl');
$content = new Template(__DIR__ . '/../view/client_content.tpl');
$convForm = new Template(__DIR__ . '/../view/single_conversation.tpl');


if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'client') {
    header('Location: LoginController.php');
}

if (isset($_SESSION['msg'])) {
    $index->add('msg', $_SESSION['msg']);
    unset($_SESSION['msg']);
} else {
    $index->add('msg', '');
}

$user = unserialize($_SESSION['user']);
$loggedUserId = $user->getId();

//Function for make template row for message
function makeTemplateRowsForMsg($allMessage) {
    global $user;

    foreach($allMessage as $message) {
        $row = new Template(__DIR__ . '/../view/message.tpl');

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


//Load all user conversation and make message rows for this conversation
$allConv = Conversation::loadAllConversationByClientId($user->getId());


if ($allConv) {

    $convId = $allConv[0]['id'];
    $oneMessage = Message::loadAllMessagesByConversationId($convId);

    if ($oneMessage) {
        $rowsMessages = makeTemplateRowsForMsg($oneMessage);
    }
}


//If request method GET with provided conversation id:
//1.Load all messages for this conversation
//2.Check if author of message is not the user - set 'isRead'
//3.Load this specific conversation and make message add form
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['convId'])) {
    if (strlen($_GET['convId']) > 0 && is_numeric($_GET['convId'])) {

        $allMessage = Message::loadAllMessagesByConversationId($_GET['convId']);

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

        if ($conv) {
            $messageForm = new Template(__DIR__ . '/../view/single_message_add.tpl');
            $messageForm->add('convId', $_GET['convId']);
            $messageForm->add('subject', $conv->getSubject());
            $messageForm->add('senderId', $loggedUserId);

            $content->add('convForm', '');
            $content->add('messageForm', $messageForm->parse());
        } else {
            $content->add('convForm', '');
            $content->add('messageForm', '');
        }

    } else {
        echo 'Invalid GET parameter';
    }

} else {
    //Show conversation add form and hide message form because no conversation id provide in GET
    $content->add('messageForm', '');
    $convForm->add('senderId', $loggedUserId);
    $content->add('convForm', $convForm->parse());
}

//Load all user conversation rows after $_GET (convId), so we have nice effect of disappear new message count
//and still, we have highlighted new messages
if ($allConv) {

    foreach ($allConv as $conv) {
        $row = new Template(__DIR__ . '/../view/conversation_row.tpl');

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

        $newMessage = $countNotRead ? "<span class='notRead'> ($countNotRead new)</span>" : '';

        foreach ($conv as $key => $value) {
            $row->add($key, $value);
            $row->add('controller', 'ClientController');
            $row->add('newMessages', $newMessage);
        }

        $rowsTemplate[] = $row;
    }

    $rowsContent = Template::joinTemplates($rowsTemplate);
}


$content->add('conversations', isset($rowsContent) ? $rowsContent : '');
$content->add('messages', isset($rowsMessages) ? $rowsMessages : '');
$index->add('logout', $logout->parse());
$index->add('content', $content->parse());

echo $index->parse();
