<?php

class Message
{
    private $id, $conversationId, $senderId, $message;
    public static $dbConn;

    public function __construct()
    {
        $this->id = -1;
        $this->conversationId = "";
        $this->senderId = "";
        $this->message = "";
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
                 (`conversationId`, `senderId`, `message`) VALUES (:conversationId, :senderId, :message)"
            );
            $result = $stmt->execute(
                [
                    'conversationId' => $this->conversationId,
                    'senderId' => $this->senderId,
                    'message' => $this->message
                ]
            );

            if ($result !== false) {
                $this->id = self::$dbConn->lastInsertId();
                return true;
            }

        } else {
            //update
            $stmt = self::$dbConn->prepare(
                "UPDATE `message` SET `conversationId=:conversationId`, `senderId=:senderId`, `message=:message` WHERE `id=:id`"
            );

            $result = $stmt->execute(
                [
                    'conversationId' => $this->conversationId,
                    'supportId' => $this->senderId,
                    'subject' => $this->message,
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

    public static function loadMessageByConversationId($id)
    {
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `message` WHERE conversationId=:conversationId"
        );

        $result = $stmt->execute(['conversationId' => $id]);

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


}