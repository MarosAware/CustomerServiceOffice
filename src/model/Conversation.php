<?php

class Conversation implements JsonSerializable
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
        if ($this->isValidId($clientId)) {
            $this->clientId = $clientId;
            return true;
        }
        return false;
    }


    public function getSupportId()
    {
        return $this->supportId;
    }


    public function setSupportId($supportId)
    {
        if ($this->isValidId($supportId)) {
            $this->supportId = $supportId;
            return true;
        }
        return false;
    }


    public function getSubject()
    {
        return $this->subject;
    }


    public function setSubject($subject)
    {
        if ($this->isValidSubject($subject)) {
            $subject = htmlspecialchars($subject);
            $this->subject = $subject;
            return true;
        }
        return false;
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
                "UPDATE `conversation` SET `clientId`=:clientId, `supportId`=:supportId, `subject`=:subject WHERE `id`=:id"
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

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'clientId' => $this->clientId,
            'supportId' => $this->supportId,
            'subject' => $this->subject
        ];
    }

    //Load all method load all rows (assoc) and return it
    public static function loadAllConversationByClientId($id)
    {
        //$tab = [];
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `conversation` WHERE clientId=:id ORDER BY id DESC"
        );

        $result = $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result === true && $stmt->rowCount() > 0) {
            return $rows;
//            foreach ($rows as $dbObj) {
//                $loadedConversation = new Conversation();
//                $loadedConversation->id = $dbObj->id;
//                $loadedConversation->clientId = $dbObj->clientId;
//                $loadedConversation->supportId = $dbObj->supportId;
//                $loadedConversation->subject = $dbObj->subject;
//                $tab[] = $loadedConversation;
//            }
//            return $tab;
        }
        return null;
    }

    public static function loadAllConversationBySupportId($id)
    {
        //$tab = [];
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `conversation` WHERE supportId=:id ORDER BY id DESC"
        );

        $result = $stmt->execute(['id' => $id]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result === true && $stmt->rowCount() > 0) {
            return $rows;
//            foreach ($rows as $dbObj) {
//                $loadedConversation = new Conversation();
//                $loadedConversation->id = $dbObj->id;
//                $loadedConversation->clientId = $dbObj->clientId;
//                $loadedConversation->supportId = $dbObj->supportId;
//                $loadedConversation->subject = $dbObj->subject;
//                $tab[] = $loadedConversation;
//            }
//            return $tab;
        }
        return null;
    }

    public static function loadAllOpenConversation()
    {
        //$tab = [];
        $result = self::$dbConn->query(
            "SELECT * FROM `conversation` WHERE supportId IS NULL ORDER BY id DESC"
        );

        $rows = $result->fetchAll(PDO::FETCH_ASSOC);

        if ($result->rowCount() > 0) {
            return $rows;
//            foreach ($rows as $dbObj) {
//                $loadedConversation = new Conversation();
//                $loadedConversation->id = $dbObj->id;
//                $loadedConversation->clientId = $dbObj->clientId;
//                $loadedConversation->supportId = $dbObj->supportId;
//                $loadedConversation->subject = $dbObj->subject;
//                $tab[] = $loadedConversation;
//            }
//            return $tab;
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

    //Validation Method

    public static function isValidId($id)
    {
        if (isset($id) && !empty($id) && $id > 0 && is_numeric($id)) {
            return true;
        }
        return false;
    }

    public static function isValidSubject($subject)
    {
        if (isset($subject) && strlen($subject) > 0 && strlen($subject) < 100) {
            return true;
        }
        return false;
    }


}