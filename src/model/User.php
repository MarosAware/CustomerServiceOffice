<?php

class User
{
    private $id, $login, $password, $role_id;
    public static $dbConn;

    public function __construct()
    {
        $this->id = -1;
        $this->login = "";
        $this->password = "";
        $this->role_id = 1;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLogin()
    {
        return $this->login;
    }


    public function setLogin($login)
    {
        if ($this->isValidLogin($login)) {
            $this->login = $login;
            return true;
        }
        return false;
    }


    public function getPassword()
    {
        return $this->password;
    }


    public function setPassword($newPassword)
    {
       if ($this->isValidPassword($newPassword)) {
           $options = ['cost' => 10];

           $hashPass = password_hash($newPassword, PASSWORD_BCRYPT, $options);
           $this->password = $hashPass;
           return true;
       }
       return false;
    }


    public function getRoleId()
    {
        return $this->role_id;
    }


    public function setRole($role_id)
    {
        if ($this->isValidRole($role_id)) {
            $this->role_id = $role_id;
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
                "INSERT INTO `user` (`login`, `password`, `role_id`) VALUES (:login, :password, :role_id)"
            );
            $result = $stmt->execute(
                [
                    'login' => $this->login,
                    'password' => $this->password,
                    'role_id' => $this->role_id
                ]
            );

            if ($result !== false) {
                $this->id = self::$dbConn->lastInsertId();
                return true;
            }

        } else {
            //update
            $stmt = self::$dbConn->prepare(
                "UPDATE `user` SET login=:login, password=:password, role_id=:role_id WHERE id=:id"
            );

            $result = $stmt->execute(
                [
                    'login' => $this->login,
                    'password' => $this->password,
                    'role_id' => $this->role_id,
                    'id' => $this->id
                ]
            );

            if ($result === true) {
                return true;
            }
        }
        return false;
    }

    public static function loadUserById($id)
    {
        $stmt = self::$dbConn->prepare(
            "SELECT * FROM `user` WHERE id=:id"
        );

        $result = $stmt->execute(['id' => $id]);

        if ($result === true && $stmt->rowCount() > 0) {
            $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
            $loadedUser = new User();
            $loadedUser->id = $dbObj->id;
            $loadedUser->login = $dbObj->login;
            $loadedUser->password = $dbObj->password;
            $loadedUser->role_id = $dbObj->role_id;
            return $loadedUser;
        }
        return null;
    }

    public static function loadUserByLogin($login)
    {
        $stmt = self::$dbConn->prepare("SELECT * FROM `user` WHERE login=:login");

        //result will contain true or false depend on success
        $result = $stmt->execute(['login' => $login]);

        if ($result === true && $stmt->rowCount() > 0) {
            $dbObj = $stmt->fetch(PDO::FETCH_OBJ);
            $loadedUser = new User();
            $loadedUser->id = $dbObj->id;
            $loadedUser->login = $dbObj->login;
            $loadedUser->password = $dbObj->password;
            $loadedUser->role_id = $dbObj->role_id;
            return $loadedUser;
        }
        return null;
    }

    public function delete()
    {
        if ($this->id != -1) {
            $stmt = self::$dbConn->prepare("DELETE FROM `user` WHERE id=:id");
            $result = $stmt->execute(['id' => $this->id]);
            if ($result === true) {
                $this->id = -1;
                return true;
            }
            return false;
        }
        return true;
    }

    //Static validation method

    public static function isValidLogin($login)
    {
        if (!empty($login) && strlen($login) > 0) {
            return true;
        }
        return false;
    }

    public static function isValidPassword($password)
    {
        if (!empty($password) && strlen($password) > 0) {
            return true;
        }
        return false;
    }

    public static function isValidRole($role)
    {
        if (!empty($role) && strlen($role) > 0 && is_numeric($role) && $role > 0) {
            return true;
        }
        return false;
    }

    //Controller Method

    public static function signInUser($login, $password)
    {
        $user = self::loadUserByLogin($login);

        //User exists
        if ($user) {

            if (password_verify($password, $user->password) === true) {
                var_dump($user->password);
                //If exists and password is correct return this user
                return $user;
            } else {//incorrect password
                return false;
            }

        } else {//user doesn't exists
            return false;
        }

    }
}