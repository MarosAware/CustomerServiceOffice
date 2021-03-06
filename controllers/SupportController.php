<?php
session_start();

require __DIR__ . '/../src/Template.php';
require __DIR__ . '/../src/Database.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'support') {
    header('Location: LoginController.php');
}

$user = unserialize($_SESSION['user']);
$loggedSupportId = $user->getId();


$index = new Template(__DIR__ . '/../view/index.tpl');
$logout = new Template(__DIR__ . '/../view/logout.tpl');
$content = new Template(__DIR__ . '/../view/support_content.tpl');

$content->add('messageForm', '');
$content->add('assignButton', '');

function makeTemplateRowsForMsg($allMessage) {
    global $user;

    foreach($allMessage as $message) {
        $row = new Template(__DIR__ . '/../view/message.tpl');

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


//Load all open conversation and make template rows for them
$openConv = Conversation::loadAllOpenConversation();
$templateConversation = '/../view/conversation_row.tpl';

if ($openConv) {

    //Load all conversation rows assigned to user
    foreach ($openConv as $conv) {
        $row = new Template(__DIR__ . '/../view/conversation_row.tpl');


        //Load all not read message and count all message that user is not the author
        $loadedNotRead = Message::loadAllNotReadMsgByConversationId($conv['id']);
        $countNotRead = 0;
        if ($loadedNotRead) {
            foreach ($loadedNotRead as $msg) {
                if ($msg['senderId'] !== $user->getId()) {
                    $countNotRead++;
                }
            }
        }

        $element = "<span class='hide' data-id='{$conv['id']}' data-count='$countNotRead'></span>";

        foreach ($conv as $key => $value) {
            $row->add($key, $value);
            $row->add('controller', 'SupportController');
            $row->add('newMessages', $element);
        }
        $rowsTemplate[] = $row;
    }

    $rowsContentOpenConv = Template::joinTemplates($rowsTemplate);



}


//Load all conversation assigned to logged support user
$allAssignedConv = Conversation::loadAllConversationBySupportId($user->getId());


//Load all message for latest assigned conversation
if ($allAssignedConv) {
    $allMessage = Message::loadAllMessagesByConversationId($allAssignedConv[0]['id']);
    if ($allMessage) {
        $rowsContentAllMsg = makeTemplateRowsForMsg($allMessage);
    }
}


//If request GET with specific conversation id:
//1.Load all messages for this conversation
//2.Check if author of message is not the user - set 'isRead'
//3.If request contains 2 key (convId and supportId) show add message form, if only 'convId'
//provide show conversation assign button
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['convId'])) {
    if (strlen($_GET['convId']) > 0 && is_numeric($_GET['convId'])) {

        $allMessageById = Message::loadAllMessagesByConversationId($_GET['convId']);
        $oneConversation = Conversation::loadConversationById($_GET['convId']);

        //Checking all loaded messages if user is not the author, if so, load each one message and
        //set it property 'isRead' to one, after this operation generate all rows messages.
        if ($allMessageById) {
            foreach ($allMessageById as $message) {
                if ($message['senderId'] !== $user->getId() && $oneConversation->getSupportId() != null) {

                    $loadedMessage = Message::loadMessageById($message['id']);
                    $loadedMessage->setIsRead(1);
                    $loadedMessage->saveToDB();
                }
            }

            $rowsContentAllMsg = makeTemplateRowsForMsg($allMessageById);
        }



        //Catching GET request with key 'supportId', and generate single message add form
        if (!empty($_GET['supportId']) && is_numeric($_GET['supportId'])) {
            $messageForm = new Template(__DIR__ . '/../view/single_message_add.tpl');

            $messageForm->add('convId', $oneConversation->getId());
            $messageForm->add('subject', $oneConversation->getSubject());
            $messageForm->add('senderId', $user->getId());
            $content->add('messageForm', $messageForm->parse());

            //If we don't have key 'supportId' in GET request generate conversation assign button
        } else {

            $assignTemplate = new Template(__DIR__ . '/../view/assign_button.tpl');
            $assignTemplate->add('convId', $oneConversation->getId());
            $assignTemplate->add('subject', $oneConversation->getSubject());
            $assignTemplate->add('supportId', $user->getId());
            $content->add('assignButton', $assignTemplate->parse());

        }


    } else {
        $msg = '<p class="alert alert-danger">Invalid GET parameter.</p>';
    }
}


//Load all assigned conversation rows after $_GET (convId), so we have nice effect of disappear new message count
//and still, we have highlighted new messages
if ($allAssignedConv) {

    //Load all conversation rows assigned to user
    foreach ($allAssignedConv as $conv) {
        $row = new Template(__DIR__ . '/../view/conversation_row.tpl');


        //Load all not read message and count all message that user is not the author
        $loadedNotRead = Message::loadAllNotReadMsgByConversationId($conv['id']);
        $countNotRead = 0;
        if ($loadedNotRead) {
            foreach ($loadedNotRead as $msg) {
                if ($msg['senderId'] !== $user->getId()) {
                    $countNotRead++;
                }
            }
        }

        //Display amount of not read messages or make it empty
        $newMessage = $countNotRead ? "<span class='notRead'> ($countNotRead new)</span>" : '';

        foreach ($conv as $key => $value) {
            $row->add($key, $value);
            $row->add('controller', 'SupportController');
            $row->add('newMessages', $newMessage);
        }
        $rowsTemplate[] = $row;
    }
    $rowsContentAssignedConv = Template::joinTemplates($rowsTemplate);
}

if (isset($_SESSION['msg'])) {
    $index->add('msg', $_SESSION['msg']);
    unset($_SESSION['msg']);
} else {
    if (isset($msg)) {
        $index->add('msg', $msg);
    } else {
        $index->add('msg', '');
    }
    $index->add('msg', '');
}

$script = '<script src="../js/conversationSupport.js"></script>';
$index->add('script', $script);
$content->add('messages', isset($rowsContentAllMsg) ? $rowsContentAllMsg : '');
$content->add('myConversations', isset($rowsContentAssignedConv) ? $rowsContentAssignedConv : '');
$content->add('openConversations', isset($rowsContentOpenConv) ? $rowsContentOpenConv : '');
$index->add('content', $content->parse());
$index->add('logout', $logout->parse());

echo $index->parse();
