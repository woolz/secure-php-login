<?php

namespace SecurePHPLogin;


class User extends db_connect{


    public static function user_info(string $uid) {
        try {
            $db = new db_connect;
            $tbl_users = $db->tbl_users;
            $stmt->conn->prepare("SELECT id, username, email, verified, banned, type, mod_timestamp as timestamp FROM $tbl_users WHERE id = :id");
            $stmt->bindParam(':id', $uid);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $result['status'] = true;
        } catch (\PDOException $e) {
            $result['status'] = false;
            $result['message'] = "Error: " . $e->getMessage();
        }

        return $result;
    }

    public static function get_user_password(string $uid) {
        try {
            $db = new db_connect;
            $tbl_users = $db->tbl_users;
            $stmt->conn->prepare("SELECT password FROM $tbl_users WHERE id = :id");
            $stmt->bindParam(':id', $uid);
            $stmt->execute();
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);
            $password = $result['password'];
            return $password;
            
        } catch (\PDOException $e) {
            return $e->getMessage();
        }

    }
    
    public static function change_password(string $uid, string $new_password, string $re_new_password): array {
        try {
            $db = new db_connect;
            $tbl_users = $db->tbl_users;

            $check_pw =  Password::password_is_valid($new_password, $re_new_password);

            if ($check_pw['status'] == true) {

                $stmt->conn->prepare("UPDATE FROM $tbl_users SET password = :new_password WHERE id = :id");
                $stmt->bindParam(':id', $uid);
                $stmt->bindParam(':new_password', Password::encrypt_password($new_password));
                $stmt->execute();
                $result['status'] = true;
                $result['message'] = "Password successful changed!";



            } else {
                $result['status'] = $check_pw['status'];
                $result['message'] = $check_pw['message'];
            }

        } catch (\PDOException $e) {
            $result['status'] = false;
            $result['message'] = "Error: " . $e->getMessage();
        }

        return $result;
    }

    public static function change_user_email(string $uid, string $new_email) {
        try {
            $db = new db_connect;
            $tbl_users = $db->tblUsers;
            $stmt->conn->prepare("UPDATE $tbl_users SET email = :new_email WHERE id = :id");
            $stmt->bindParam(':new_email', $new_email);
            $stmt->bindParam(':id', $uid);
            $stmt->execute();
            return True;

        } catch (\PDOException $e) {
            return $e->getMessage();

        }
    }

    public static function delete_user(string $uid) {

        try {

            $db = new db_connect;
            $tbl_users = $db->tbl_users;

            $stmt = $db->conn->prepare("DELETE FROM $tbl_users WHERE id = :id");
            $stmt->bindParam(':id', $uid);
            $stmt->execute();
            $result = true;

        } catch (\PDOException $e) {
            $result = $e->getMessage(); 
        }
        return $result;
    }



}
