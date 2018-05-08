<?php

class Role
{
    private $id, $name;
    public static $dbConn;

    public function __construct()
    {
        $this->id = -1;
        $this->name = "";
    }
    //DB Method
    public function saveToDB()
    {

        if($this->id === -1) {
            //nowy wpis
            $stmt = self::$dbConn->prepare(
                "INSERT INTO `role` (`name`) VALUES (:`name`)"
            );
            $result = $stmt->execute(
                [
                    'name' => $this->name,
                ]
            );

            if ($result !== false) {
                $this->id = self::$dbConn->lastInsertId();
                return true;
            }

        } else {
            //update
            $stmt = self::$dbConn->prepare(
                "UPDATE `role` SET `name`=:`name` WHERE id=:id"
            );

            $result = $stmt->execute(
                [
                    'name' => $this->name,
                    'id' => $this->id
                ]
            );

            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    public static function getRoleById($id) {
        $stmt = self::$dbConn->prepare("SELECT * FROM `role` WHERE id=:id");
        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
            $loadedRole = new Role();
            $loadedRole->id = $dbObj->id;
            $loadedRole->name = $dbObj->name;
            return $loadedRole;
        }
        return null;
    }

    public function delete()
    {
        if ($this->id != -1) {
            $stmt = self::$dbConn->prepare("DELETE FROM `role` WHERE id=:id");
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    //Getter and Setter

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}