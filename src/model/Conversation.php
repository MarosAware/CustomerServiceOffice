<?php

class Conversation
{
    private $id, $clientId, $supportId, $subject;
    public static $dbConn;

    public function __construct()
    {
        $this->id = -1;
        $this->clientId = "";
        $this->supportId = null;
        $this->subject = "";
    }


    public function getId()
    {
        return $this->id;
    }


    public function getClientId()
    {
        return $this->clientId;
    }


    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }


    public function getSupportId()
    {
        return $this->supportId;
    }


    public function setSupportId($supportId)
    {
        $this->supportId = $supportId;
    }


    public function getSubject()
    {
        return $this->subject;
    }


    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    //DB Method
    
    public function saveToDB()
    {

        if($this->id === -1) {
            //nowy wpis
            $stmt = self::$dbConn->prepare(
                "INSERT INTO `conversation` 
                 (`clientId`, `supportId`, `subject`) VALUES (:clientId, :supportId, :subject)"
            );
            $result = $stmt->execute(
                [
                    'clientId' => $this->clientId,
                    'supportId' => $this->supportId,
                    'subject' => $this->subject
                ]
            );

            if ($result !== false) {
                $this->id = self::$dbConn->lastInsertId();
                return true;
            }

        } else {
            //update
            $stmt = self::$dbConn->prepare(
                "UPDATE `conversation` SET `clientId=:clientId`, `supportId=:supportId`, `subject=:subject` WHERE `id=:id`"
            );

            $result = $stmt->execute(
                [
                    'clientId' => $this->clientId,
                    'supportId' => $this->supportId,
                    'subject' => $this->subject,
                    'id' => $this->id
                ]
            );

            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    public static function loadConversationById($id)
    {
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `conversation` WHERE id=:id"
        );

        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
            $loadedConversation = new Conversation();
            $loadedConversation->id = $dbObj->id;
            $loadedConversation->clientId = $dbObj->clientId;
            $loadedConversation->supportId = $dbObj->supportId;
            $loadedConversation->subject = $dbObj->subject;
            return $loadedConversation;
        }
        return null;
    }

    //Not sure it works (foreach and fetch obj)
    public static function loadAllConversationByClientId($id)
    {
        $tab = [];
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `conversation` WHERE clientId=:clientId"
        );

        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            foreach ($result as $dbObj) {
                $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
                $loadedConversation = new Conversation();
                $loadedConversation->id = $dbObj->id;
                $loadedConversation->clientId = $dbObj->clientId;
                $loadedConversation->supportId = $dbObj->supportId;
                $loadedConversation->subject = $dbObj->subject;
                $tab[] = $loadedConversation;
            }
            return $tab;
        }
        return null;
    }

    public static function loadAllConversationBySupportId($id)
    {
        $tab = [];
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `conversation` WHERE supportId=:supportId"
        );

        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            foreach ($result as $dbObj) {
                $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
                $loadedConversation = new Conversation();
                $loadedConversation->id = $dbObj->id;
                $loadedConversation->clientId = $dbObj->clientId;
                $loadedConversation->supportId = $dbObj->supportId;
                $loadedConversation->subject = $dbObj->subject;
                $tab[] = $loadedConversation;
            }
            return $tab;
        }
        return null;
    }

    public function delete()
    {
        if ($this->id != -1) {
            $stmt = self::$dbConn->prepare("DELETE FROM `conversation` WHERE id=:id");
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