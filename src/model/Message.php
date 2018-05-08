<?php

class Message
{
    private $id, $conversationId, $senderId, $message, $creationDate;
    public static $dbConn;

    public function __construct()
    {
        $this->id = -1;
        $this->conversationId = "";
        $this->senderId = "";
        $this->message = "";
        $this->setCreationDate();
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
        $this->message = $message;
    }

    //DB Method

    public function saveToDB()
    {

        if($this->id === -1) {
            //nowy wpis
            $stmt = self::$dbConn->prepare(
                "INSERT INTO `message` 
                 (`conversationId`, `senderId`, `message`, `creationDate`) VALUES (:conversationId, :senderId, :message, :creationDate)"
            );
            $result = $stmt->execute(
                [
                    'conversationId' => $this->conversationId,
                    'senderId' => $this->senderId,
                    'message' => $this->message,
                    'creationDate' => $this->creationDate
                ]
            );

            if ($result !== false) {
                $this->id = self::$dbConn->lastInsertId();
                return true;
            }

        } else {
            //update
            $stmt = self::$dbConn->prepare(
                "UPDATE `message` SET `conversationId=:conversationId`, `senderId=:senderId`, `message=:message`, `creationDate=:creationDate` WHERE `id=:id`"
            );

            $result = $stmt->execute(
                [
                    'conversationId' => $this->conversationId,
                    'senderId' => $this->senderId,
                    'message' => $this->message,
                    'creationDate' => $this->creationDate,
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
            return $loadedMessage;
        }
        return null;
    }

    public static function loadAllMessagesByConversationId($id)
    {
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `message` WHERE conversationId=:conversationId ORDER BY id DESC"
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


}