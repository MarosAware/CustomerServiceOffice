<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'support') {
    header('Location: LoginController.php');
}

$user = unserialize($_SESSION['user']);
$loggedSupportId = $user->getId();


$index = new Template(__DIR__ . '/../templates/index.tpl');
$logout = new Template(__DIR__ . '/../templates/logout.tpl');
$content = new Template(__DIR__ . '/../templates/support_content.tpl');

$content->add('messageForm', '');
$content->add('assignButton', '');

function makeTemplateRowsForMsg($allMessage) {
    global $user;

    foreach($allMessage as $message) {
        $row = new Template(__DIR__ . '/../templates/message.tpl');

        $login = $message['senderId'] === $user->getId() ? 'You' : '<b>CLIENT</b>';

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



//show all open conversation, conversation with supportId and all messages to the latest conversation



//Load all open conversation and make template rows for them
$openConv = Conversation::loadAllOpenConversation();

$templateConversation = '/../templates/conversation_row.tpl';
$customOptionConv = [['controller' => 'SupportController'], ['newMessages' => '']];
$rowsContentOpenConv = Template::makeTemplateRows($openConv, $templateConversation, $customOptionConv);


//Load all conversation assigned to logged support user

$allAssignedConv = Conversation::loadAllConversationBySupportId($user->getId());


//Load all message for latest conversation
$allMessage = Message::loadAllMessagesByConversationId($allAssignedConv[0]['id']);

if ($allMessage) {
    $rowsContentAllMsg = makeTemplateRowsForMsg($allMessage);
}






//var_dump($allMessage);


//Load all message for specific conversation id taken from GET
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['convId'])) {
    if (strlen($_GET['convId']) > 0 && is_numeric($_GET['convId'])) {

        $allMessageById = Message::loadAllMessagesByConversationId($_GET['convId']);

        if ($allMessageById) {
            foreach ($allMessageById as $message) {
                if ($message['senderId'] !== $user->getId()) {

                    $loadedMessage = Message::loadMessageById($message['id']);
                    $loadedMessage->setIsRead(1);
                    $loadedMessage->saveToDB();
                }
            }
            $rowsContentAllMsg = makeTemplateRowsForMsg($allMessageById);
        }


        $oneConversation = Conversation::loadConversationById($_GET['convId']);

//        var_dump($oneConversation);




        if (!empty($_GET['supportId']) && is_numeric($_GET['supportId'])) {
            $messageForm = new Template(__DIR__ . '/../templates/single_message.tpl');

            $messageForm->add('convId', $oneConversation->getId());
            $messageForm->add('subject', $oneConversation->getSubject());
            $messageForm->add('senderId', $user->getId());

            $content->add('messageForm', $messageForm->parse());

        } else {

            $assignTemplate = new Template(__DIR__ . '/../templates/assign_button.tpl');

            $assignTemplate->add('convId', $oneConversation->getId());
            $assignTemplate->add('subject', $oneConversation->getSubject());
            $assignTemplate->add('supportId', $user->getId());

            $content->add('assignButton', $assignTemplate->parse());

        }



    } else {
        echo 'Invalid GET parameter.';
    }
}



foreach ($allAssignedConv as $conv) {
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
        $row->add('controller', 'SupportController');
        $row->add('newMessages', $newMessage);
    }

    $rowsTemplate[] = $row;
}

$rowsContentAssignedConv = Template::joinTemplates($rowsTemplate);






$content->add('messages', isset($rowsContentAllMsg) ? $rowsContentAllMsg : '');
$content->add('myConversations', $rowsContentAssignedConv);
$content->add('openConversations', $rowsContentOpenConv);
$index->add('content', $content->parse());
$index->add('logout', $logout->parse());

echo $index->parse();



