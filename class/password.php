<?php

namespace SecurePHPLogin;


class Password extends db_connect{


    public static function encrypt_password(string $password): bool {
        $encrypted = password_hash($password, PASSWORD_DEFAULT);
        return $encrypted;
    }

    public static function check_password($sent_password, $database_password): bool {
        $result = password_verify($sent_password, $database_password);
        return $result;
    }


    public static function password_is_valid(string $password, $re_password) {
        try {

            if ($password == $re_password) {

                if (strlen($password) >= 6 && preg_match('/[A-Z]/', $password) > 0 && preg_match('/[a-z]/', $password) > 0) {
                    $result['status'] = true;
                    $result['message'] = '';

                } else {
                    $result['status'] = false;
                    $result['message'] = "Password must include at least one upper and lower case letter and be at least 6 characters long";
                }


            } else {
                $result['status'] = false;
                $result['message'] = "Passwords are not equal!";
            }

        } catch (\Exception $e) {
            $result['status'] = false;
            $result['message'] = "Error: " . $e->getMessage();
        }


    }





}
