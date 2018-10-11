<?php


namespace SecurePHPLogin;
 
class Cookies extends sys_info
{

    public static function generateCookie($cookieid, $userid, $tokenid, $token, $exptime)
    {
        try {
            setcookie("usertoken", $token, time() + $exptime, "/", "", false, true);
            $db = new db_connect;
            $stmt = $db->conn->prepare("REPLACE INTO $db->tbl_cookies (cookieid, userid, tokenid, expired)
                                        VALUES (:cookieid, :userid, :tokenid, 0)");
            $stmt->bindParam(':cookieid', $cookieid);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':tokenid', $tokenid);
            $stmt->execute();
            return true;
        } catch (\Exception $e) {
            http_response_code(500);
            return $e->getMessage();
        }
    }


    public static function decodeCookie($usertoken): array
    {
        $cookie = array();
        try {
            if (!isset($_COOKIE[$usertoken])) {
                $cookie['status'] = false;
                return $cookie;
            } else {
                $cookie['status'] = true;
                $cookie['value'] = $_COOKIE[$usertoken];
                return $cookie;
            }
        } catch (\Exception $e) {
            $cookie['status'] = false;
            $cookie['message'] = $e->getMessage();
            return $result;
        }
    }


    public static function validateCookie($userid, $cookieid, $tokenid): array
    {
        try {
            $db = new db_connect;
            $stmt = $db->conn->prepare("SELECT cookieid, userid, tokenid, expired FROM $db->tbl_cookies
                                        WHERE userid = :userid AND tokenid = :tokenid AND cookieid = :cookieid
                                        AND expired = 0");
            $stmt->bindParam(':cookieid', $cookieid);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':tokenid', $tokenid);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            return $result;
        } catch (\Exception $e) {
            $result['status'] = false;
            $result['message'] = "Error: " . $e->getMessage();
            return $result;
        }
    }

    public static function startCookies()
    {
        $conf = new sys_info;
        require $conf->url."/vendor/autoload.php";
        $uid = $_SESSION['uid'];
        try {
            $user = User::user_info($uid);
            if (!$user) {
                throw new \Exception("Error, user notfound!"); // nenhum usuário encontrado meu amigo :/
            }
            $secret =  bin2hex(random_bytes(21));
            $tokenid = uniqid('tc_', true);
            $cookieid = uniqid();
            $userid = $user['id'];
            $payload = array(
              "iss" => $conf->url,
              "tokenid"=>$tokenid,
              "cookieid"=>$cookieid,
              "userid" => $userid,
            );
            $token = \Firebase\JWT\JWT::encode($payload, $secret);
            //Firebase pra gerar aquele token responsa
            $cookie = self::generateCookie($cookieid, $userid, $tokenid, $token, (int)$conf->cookie_expire_seconds);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
