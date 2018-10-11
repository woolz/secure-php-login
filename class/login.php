<?php

namespace SecurePHPLogin;


class Login extends db_connect {


    public function checkLogin(string $user, string $password, bool $cookie = false) {

        $ip_address = $_SERVER['REMOTE_ADDR'];
        $login_timeout = (int) $this->login_timeout;
        $max_attempts = (int) $this->max_attempts;
        $attcheck = $this->checkAttempts($username);
        $curr_attempts = $attcheck['attempts'];

        $datetimeNow = date("Y-m-d H:i:s");
        $oldTime = strtotime($attcheck['lastLogin']);
        $newTime = strtotime($datetimeNow);
        $timeDiff = $newTime - $oldTime;

        $timeout_minutes = round($login_timeout / 60, 1);

        try {
            $err = '';

            $stmt = $this->conn->prepare("SELECT id, username, email, password, verified, banned FROM ".$this->tbl_members." WHERE username = :myusername");
            $stmt->bindParam(':myusername', $username);
            $stmt->execute();


            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            $login = array();

            if ($curr_attempts >= $max_attempts && $timeDiff < $login_timeout) {

                //Excedeu o limite de tentativas
                $login['status'] = false;
                $login['message'] = "<div class=\"alert alert-danger alert-dismissable\">\n&times;\n</button>\nYou are exceed the limit of login attempts por favor espere {$timeout_minutes} para tentar novamente\n</div>";
            
            } else {

                if (Password::check_password($password, $result['password'])) {
                  $login['status'] = true;

                  $this->int_session($result, $cookie);

                }  else {
                    $login['status'] = false;
                    $login['message'] = "<div class=\"alert alert-danger alert-dismissable\">
                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">
                      &times;
                    </button>
                    Username or Password Wrong
                  </div>";
                }
            }
         return $login;
        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();
            return $err;
        }

    }


    public function checkAttempts($username) {

        try {

           $ip_address = $_SERVER['REMOTE_ADDR'];
           $err = '';

           $sql = "SELECT Attempts as attempts, lastlogin FROM ".$this->tbl_attempts." WHERE IP = :ip and Username = :username";

           $stmt = $this->conn->prepare($sql);

           $stmt->bindParam(':ip', $ip_address);
           $stmt->bindParam(':username', $username);
           $stmt->execute();
           $result = $stmt->fetch(\PDO::FETCH_ASSOC);
           return $result;

           $oldTime = strtotime($result['lastlogin']);
           $newTime = strtotime($datetimeNow);
           $timeDiff = $newTime - $oldTime;



        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();

        }



        $resp = ($err == '') ? $result : $err;
    }




    public function insertAttept($username) {
        try {
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $login_timeout = (int) $this->login_timeout;
            $max_attempts = (int) $this->max_attempts;

            $datetimeNow = date("Y-m-d H:i:s");
            $attcheck = $this->checkAttempts($username);
            $curr_attempts = $attcheck['attempts'];

            $stmt = $this->conn->prepare("INSERT INTO ".$this->tbl_attempts." (ip, attempts, lastlogin, username) values (:ip, 1, :lastlogin, :username)");
            $stmt->bindParam(':ip', $ip_address);
            $stmt->bindParam(':lastlogin', $datetimeNow);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $curr_attempts++;
            $err = '';

        } catch (\PDOException $e) {
            $err = "Error: " . $e->getMessage();
        }

        $resp = ($err == '') ? 'true' : $err;

        return $resp;
    }



    public function updateAttempts($username) {
        try {
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $login_timeout = (int)$this->login_timeout;
            $max_attempts = (int)$this->max_attempts;

            $attcheck = $this->checkAttempts($username);

            $curr_attempts = $attcheck['attempts'];

            $datetimeNow = date("Y-m-d H:i:s");
            $oldTime = strtotime($attcheck['lastlogin']);
            $newTime = strtotime($datetimeNow);
            $timeDiff = $newTime - $oldTime;

            $err = '';
            $sql = '';

            if ($curr_attemps >= $max_attempts && $TimeDiff < $login_timeout) {
                if ($timeDiff >= $login_timeout) {
                    $sql = "UPDATE ".$this->tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin WHERE ip = :ip and username = :username";
                    $curr_attempts = 1;

                } else {
                    if ($timeDiff < $login_timeout) {
                        $sql = "UPDATE ".$this->tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                        $curr_attempts++;
                    } elseif ($timeDiff >= $login_timeout) {
                        $sql = "UPDATE ".$this->tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                        $curr_attempts = 1;
                    }
                    $stmt2 = $this->conn->prepare($sql);
                    $stmt2->bindParam(':attempts', $curr_attempts);
                    $stmt2->bindParam(':ip', $ip_address);
                    $stmt2->bindParam(':lastlogin', $datetimeNow);
                    $stmt2->bindParam(':username', $username);
                    $stmt2->execute();
                }
            }
            } catch (\PDOException $e) {
                $err = "Error: " . $e->getMessage();
            }


            $resp = ($err == '') ? 'true' : $err;
            return $resp;

    }
        public function init_session($result, bool $cookie)
        {
            session_start();
            $_SESSION['uid'] = $result['id'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['ip_address'] = getenv('REMOTE_ADDR');
            $_SESSION['verified'] = $result['verified'];
            if ($cookie == true) {

                Cookies::startCookies();
            }
        }
        public static function logout()
        {
            if (isset($_COOKIE["usertoken"])) {
                setcookie("usertoken", "", time() - 10000, "/");
            }
            session_start();
            session_destroy();
        }
    }
