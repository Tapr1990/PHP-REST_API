<?php

    
    require("models/model.orders.php");

    $model = new Orders();

    //echo json_encode( $model->get() );
    //echo json_encode( $model->getId($id) );



    function validator($input) {
        return (
            !empty($input)
   
        );        
      

        
    }




    if($_SERVER["REQUEST_METHOD"] === "GET") {

        if(!empty($id)) {
            $data = $model->getId($id);
            $model->decodeToken();

            if(empty($data)) {
                http_response_code(404);
                $data = ["message" => "Not Found"];
            }

            if(empty($model->user || $data["user_id"] !== $model->user["user_id"])) {
                http_response_code(403);
                $data = ["message" => "Forbidden"];
            }
        }
        else{
            $data = $model->get();
        }
    }
    elseif($_SERVER["REQUEST_METHOD"] === "POST") {

        $model->decodeToken();

        if(empty($model->user)) {
            http_response_code(401);
            die('{"message":"Unauthorizsd, please, login"}');
        }

        $input = json_decode(file_get_contents("php://input"), true);//* este comando do php permite o php
        //*ir ler o body do request por post e buscar os adsos para guardar

        $input["user_id"] = $model->user["user_id"];

        if(validator($input)) {
            http_response_code(202);
            $data = $model->post($input);
        }
        else {
            http_response_code(400);
            $data = ["message" => "Bad Request"];
        }

        /*echo $input;
        exit;*/


        
    }

    elseif($_SERVER["REQUEST_METHOD"] === "PUT" && !empty($id)) {

        $input = json_decode(file_get_contents("php://input"), true);

        $existingOrder = $model->getItem( $id );

        if(validator($input) && empty($existingOrder["paymnet_date"])) {
            http_response_code(202);

            $input["order_id"] = $id;
      
            $data = $model->put($input);
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

    echo json_encode($data);
        


    
        /*$users = [
                ["user_id" => 1, "name"=> "Example1"],
                ["user_id" => 2, "name"=> "Example2"]
            ];
    
            echo json_encode($users);*/
    
    
?>