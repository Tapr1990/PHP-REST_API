<?php

    require_once("model.base.php");//* só para importar uma vez

    class Users extends Base {
       

        public function get() {
            $query = $this->db->prepare("
                SELECT user_id, name, username, email, city
                FROM users
            ");

            $query->execute();

            return $query->fetchAll();
        }

        public function getId($id) {

            $query = $this->db->prepare("
                SELECT user_id, name, username, email, str_address, city, zipcode, phone
                FROM users
                WHERE user_id = ?

            ");

            $query->execute([ $id ]);

            return $query->fetch();
        }

        public function post($data) {
            $query = $this->db->prepare("
                INSERT INTO users
                (name, username, email, str_address, city, zipcode, phone, password)
                VALUES(?,?,?,?,?,?,?,?)
            ");

            $query->execute([
                $data["name"],
                $data["username"],
                $data["email"],
                $data["str_address"],
                $data["city"],
                $data["zipcode"],
                $data["phone"],
                password_hash($data["password"], PASSWORD_DEFAULT)
            ]);

            return true;



        }

        public function put($data) {
            $query = $this->db->prepare("
                UPDATE 
                    users
                SET 
                    name = ?,
                    username = ?,
                    email = ?,
                    str_address = ?,
                    city = ?,
                    zipcode = ?,
                    phone = ?,
                    password = ?

                WHERE
                    user_id = ?
            ");

            $query->execute([
                $data["name"],
                $data["username"],
                $data["email"],
                $data["str_address"],
                $data["city"],
                $data["zipcode"],
                $data["phone"],
                password_hash($data["password"], PASSWORD_DEFAULT),
                $data["user_id"]
            ]);

            return true;



        }

        public function login($data) {
            $query = $this->db->prepare("
                SELECT user_id, name, username, email, password
                FROM users
                WHERE username = ?
            ");

            $query->execute([
                
                $data["username"]
            ]);

            $user = $query->fetch();

            if(
                !empty($user) &&
                password_verify($data["password"], $user["password"])
            ) {
                return $user;
            }

            return [];

        }   
    }
?>