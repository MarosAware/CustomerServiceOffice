<?php

require __DIR__ . '/model/User.php';
require __DIR__ . '/model/Role.php';
require __DIR__ . '/model/Conversation.php';
require __DIR__ . '/model/Message.php';

class Database
{
    private
        $host = 'localhost',
        $database = 'BOK',
        $username = 'root',
        $password = 'coderslab';

    protected static $instance;
    protected $pdo;

    private function __construct()
    {
        $this->connect();
        $this->linkModel();
    }

    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function __clone() {}

    private function connect() {

        try {

            $this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->database, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //echo 'Connected!';

        } catch(PDOException $e) {
            echo 'Database connection failed. Error msg: '. $e->getMessage();
        }
    }


    private function linkModel() {
        User::$dbConn = $this->pdo;
        Conversation::$dbConn = $this->pdo;
        Message::$dbConn = $this->pdo;
        Role::$dbConn = $this->pdo;
    }

//OR

//    public function linkModel($model) {
//        $model::$dbConn = $this->pdo;
//    }

}

$conn = Database::getInstance();

//$conn = Database::getInstance();
//
//var_dump($conn);