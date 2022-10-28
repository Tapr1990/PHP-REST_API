<?php
    use ReallySimpleJWT\Token;


    class Base
    {
        public $db;
        public $user;

        public function __construct() {
            $this->db = new PDO(
                "mysql:host=" .ENV["DB_HOST"]. ";dbname=" .ENV["DB_NAME"]. ";charset=utf8mb4",
                ENV["DB_USER"], ENV["DB_PASSWORD"]
            );
            
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        }

        /* limpa coisas que possam levar a XSS */
        public function sanitizer($data) {
            foreach($data as $key => $value) {

                if(is_array($value)) {

                    $data[$key] = $this->sanitizer($value);
                }
                else {

                    $data[$key] = htmlspecialchars(strip_tags(trim($value)));
                }
            }

            return $data;
        }

        public function decodeToken() {

            $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJuYW1lIjoiTGVhbm5lIEdyYWhhbSIsInVzZXJuYW1lIjoiQnJldCIsImlhdCI6MTY2Mjc1MzU4MCwiZXhwIjoxNjk0Mjg5NTgwfQ.Kih1AGYu2yfkL09GODqq2VRtzL_IBuTPcjHbZoE2saI";

          if(Token::validate($token, ENV["JWT_SECRET_KEY"])) {
                $this->user = Token::getPayload($token, ENV["JWT_SECRET_KEY"]);
            }

            return $this->user;

                
        }

    
    }
?>