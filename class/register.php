<?php

namespace SecurePHPLogin;


class Register extends db_connect{


    public function check_if_username_exists(string $username) {
        try {
            $db = new db_connect;
            $tbl_users = $db->tbl_users; 
            $stmt = $db->conn->prepare("SELECT COUNT(*) from $tbl_users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $result = ($stmt->fetchColumn() != 0) ? true : false;
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();

        }
        return $result;

    }


    public function check_if_email_exists(string $email) {
        try {
            $db = new db_connect;
            $tbl_users = $db->tbl_users; 
            $stmt = $db->conn->prepare("SELECT COUNT(*) from $tbl_users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $result = ($stmt->fetchColumn() != 0) ? true : false;
        } catch (\PDOException $e) {
            $result = "Error: " . $e->getMessage();

        }
        return $result;

    }

    public function create_account(string $username, string $email, string $password, string $re_password): array {
        try {
            $db = new db_connect;
            $tbl_users = $db->tbl_users;

            if (isset($username) && isset($email) && isset($password) & isset($re_password)) {

                if (check_if_username_exists($username) == false) {
                    
                    if (check_if_email_exists($email) == false) {
                        $check_pw = Password::password_is_valid($password, $re_password);
                        if ($check_pw['status'] == true) {
                            $id = uniqid();
                            $stmt = $db->conn->prepare("INSERT INTO $tbl_users (id, username, email, password) VALUES (:id, :username, :email, :password)");
                            $stmt->bindParam(':id', $id);
                            $stmt->bindParam(':username', $username);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':password', Password::encrypt_password($password));
                            $stmt->execute();
                            $result['status'] = true;
                            $result['message'] = "Sua conta foi criada com sucesso.";

                        } else {
                            $result['status'] = false;
                            $result['message'] = "As senhas não são iguais";
                        }


                    } else {
                        $result['status'] = false;
                        $result['message'] = "O email já está sendo utilizado por outro usuário";
                    }




                } else {
                    $result['status'] = false;
                    $result['message'] = "O nome de usuário já está em uso.";
                }

            } else {
                $result['status'] = false;
                $result['message'] = "Por favor, não deixe campos em branco.";
            }

        } catch (\PDOException $e) {
            $result['status'] = false;
            $result['message'] = $e->getMessage();
        }

    }


   

}

