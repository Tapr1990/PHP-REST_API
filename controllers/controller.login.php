<?php
    use ReallySimpleJWT\Token;


    require("models/model.users.php");

    $model = new Users();

    function validator($input) {
        return (
            !empty($input) &&
            !empty($input["username"]) &&
            !empty($input["password"]) &&
            mb_strlen($input["password"]) <= 1000 
            
        );
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        
        $input = json_decode( file_get_contents("php://input"), true );

        if(validator($input)) {
            
            
            $data = $model->login( $input );

            if(!empty($data)) {

                $payload = [
                    "user_id" => $data["user_id"],
                    "name" => $data["name"],
                    "username" => $data["username"],
                    "iat" => time(),//* quando foi criado, a data
                    "exp" => time() + (60 * 60 * 24 * 365)//* quando vai expiar, daui um ano 
                ];

                $token = Token::customPayload($payload, ENV["JWT_SECRET_KEY"]);

                $data = ["Auth_Token" => $token];

                http_response_code(200);
            }
            else {
                http_response_code(401);
                $data = ["message" => "Username or password incorrect"];
            }
        }
        else {
            http_response_code(400);
            $data = ["message" => "Bad Request"];
        }

    }
    else {
        http_response_code(405);
        
        $data = ["message" => "Method not allowed"];
    }

    echo json_encode( $data );
?>
