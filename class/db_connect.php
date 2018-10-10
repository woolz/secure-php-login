<?php

namespace SecurePHPLogin;


class db_connect {

    private $db_name;

    private $host;

    private $username;

    private $password;

    public $conn;

    public $tbl_prefix;

    public $tbl_users;

    public $tbl_tokens
    
    public $tbl_attempts;

    public $tbl_cookies;




    public function __construct() {




        require './config/db.php';

        // Passing vars for object
        $this->tbl_prefix = $tbl_prefix;
        $this->tbl_users = $tbl_users;
        $this->tbl_tokens = $tbl_tokens;
        $this->tbl_attempts = $tbl_attempts;
        $this->tbl_cookies = $tbl_cookies;

        try {
            $this->conn = new \PDO('mysql:host='.$host.';dbname='.$db_name.';charset=utf8', $username, $password);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
        } catch (\PDOException $e) {
            die($e->getMessage());
        }
    }
    // Destruct connection after query
    public function __destruct() {
        $this->conn = null;
    }


    //Prevent clones
    private function __clone() {  
    }

    //Prevent wakeups
    private function __wakeup(){
     }
}
