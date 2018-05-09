<?php

class Message
{
    private $id, $conversationId, $senderId, $message, $creationDate, $isRead;
    public static $dbConn;

    public function __construct()
    {
        $this->id = -1;
        $this->conversationId = "";
        $this->senderId = "";
        $this->message = "";
        $this->setCreationDate();
        $this->isRead = 0;
    }

    public function getId()
    {
        return $this->id;
    }


    public function getConversationId()
    {
        return $this->conversationId;
    }


    public function setConversationId($conversationId)
    {
        $this->conversationId = $conversationId;
    }


    public function getSenderId()
    {
        return $this->senderId;
    }


    public function setSenderId($senderId)
    {
        $this->senderId = $senderId;
    }


    public function getMessage()
    {
        return $this->message;
    }


    public function setMessage($message)
    {
        if ($this->isValidMessage($message)) {
            $message = htmlspecialchars($message);
            $this->message = $message;
            return true;
        }
        return false;
    }

    //DB Method

    public function saveToDB()
    {

        if ($this->id === -1) {
            //nowy wpis
            $stmt = self::$dbConn->prepare(
                "INSERT INTO `message` 
                 (`conversationId`, `senderId`, `message`, `creationDate`, `isRead`) 
                 VALUES (:conversationId, :senderId, :message, :creationDate, :isRead)"
            );
            $result = $stmt->execute(
                [
                    'conversationId' => $this->conversationId,
                    'senderId' => $this->senderId,
                    'message' => $this->message,
                    'creationDate' => $this->creationDate,
                    'isRead' => $this->isRead
                ]
            );

            if ($result !== false) {
                $this->id = self::$dbConn->lastInsertId();
                return true;
            }

        } else {
            //update
            $stmt = self::$dbConn->prepare(
                "UPDATE `message` SET `conversationId`=:conversationId, `senderId`=:senderId, `message`=:message, 
              `creationDate`=:creationDate, `isRead`=:isRead WHERE `id`=:id"
            );

            $result = $stmt->execute(
                [
                    'conversationId' => $this->conversationId,
                    'senderId' => $this->senderId,
                    'message' => $this->message,
                    'creationDate' => $this->creationDate,
                    'isRead' => $this->isRead,
                    'id' => $this->id
                ]
            );

            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    public static function loadMessageById($id)
    {
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `message` WHERE id=:id"
        );

        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
            $loadedMessage = new Message();
            $loadedMessage->id = $dbObj->id;
            $loadedMessage->conversationId = $dbObj->conversationId;
            $loadedMessage->senderId = $dbObj->senderId;
            $loadedMessage->message = $dbObj->message;
            $loadedMessage->creationDate = $dbObj->creationDate;
            $loadedMessage->isRead = $dbObj->isRead;
            return $loadedMessage;
        }
        return null;
    }

    public static function loadAllMessagesByConversationId($id)
    {
        //$tab = [];
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `message` WHERE conversationId=:conversationId ORDER BY id DESC"
        );

        $result = $stmt->execute(['conversationId' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result === true && $stmt->rowCount() > 0) {
            return $rows;
//            foreach ($rows as $dbObj) {
//                $loadedMessage = new Message();
//                $loadedMessage->id = $dbObj->id;
//                $loadedMessage->conversationId = $dbObj->conversationId;
//                $loadedMessage->senderId = $dbObj->senderId;
//                $loadedMessage->message = $dbObj->message;
//                $loadedMessage->creationDate = $dbObj->creationDate;
//                $tab[] = $loadedMessage;
//            }
//            return $tab;
        }
        return null;
    }

    public static function loadAllMessagesBySenderId($id)
    {
        //$tab = [];
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `message` WHERE senderId=:senderId ORDER BY id DESC"
        );

        $result = $stmt->execute(['senderId' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result === true && $stmt->rowCount() > 0) {
            return $rows;
//            foreach ($rows as $dbObj) {
//                $loadedMessage = new Message();
//                $loadedMessage->id = $dbObj->id;
//                $loadedMessage->conversationId = $dbObj->conversationId;
//                $loadedMessage->senderId = $dbObj->senderId;
//                $loadedMessage->message = $dbObj->message;
//                $loadedMessage->creationDate = $dbObj->creationDate;
//                $tab[] = $loadedMessage;
//            }
//            return $tab;
        }
        return null;
    }

    public static function loadAllNotReadMsgByConversationId($id)
    {
        //$tab = [];
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `message` WHERE conversationId=:conversationId AND isRead = 0"
        );

        $result = $stmt->execute(['conversationId' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result === true && $stmt->rowCount() > 0) {
            return $rows;
//            foreach ($rows as $row) {
//                $loadedMessage = new Message();
//                $loadedMessage->id = $row['id'];
//                $loadedMessage->conversationId = $row['conversationId'];
//                $loadedMessage->senderId = $row['senderId'];
//                $loadedMessage->message = $row['message'];
//                $loadedMessage->creationDate = $row['creationDate'];
//                $tab[] = $loadedMessage;
//            }
//            return $tab;
        }
        return null;
    }


    /*
    public static function loadLastMessageByConversationId($id)
    {
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `message` WHERE conversationId=:conversationId 
             ORDER BY creationDate DESC LIMIT 1"
        );

        $result = $stmt->execute(['conversationId' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result === true && $stmt->rowCount() > 0) {

            return $rows;

//            $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
//            $loadedMessage = new Message();
//            $loadedMessage->id = $dbObj->id;
//            $loadedMessage->conversationId = $dbObj->conversationId;
//            $loadedMessage->senderId = $dbObj->senderId;
//            $loadedMessage->message = $dbObj->message;
//            return $loadedMessage;
        }
        return null;
    }
    */


    public function delete()
    {
        if ($this->id != -1) {
            $stmt = self::$dbConn->prepare("DELETE FROM `message` WHERE id=:id");
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function setCreationDate($creationDate = 'now')
    {
        $date = new DateTime($creationDate);
        $date = $date->format('Y-m-d H:i:s');
        $this->creationDate = $date;
    }

    //Validation Method

    public static function isValidMessage($message)
    {
        if (!empty($message) && mb_strlen($message) > 0 && mb_strlen($message) < 255) {
            return true;
        }
        return false;
    }

    public function getisRead()
    {
        return $this->isRead;
    }

    public function setisRead($isRead)
    {
        $this->isRead = $isRead;
    }

    public function getIsReadSupport()
    {
        return $this->isReadSupport;
    }

    public function setIsReadSupport($isReadSupport)
    {
        $this->isReadSupport = $isReadSupport;
    }


}