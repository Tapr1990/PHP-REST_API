<?php

    
    require("models/model.products.php");

    $model = new Products();

    //echo json_encode( $model->get() );
    //echo json_encode( $model->getId($id) );



    function validator($input) {
        return (
            !empty($input) &&
            !empty($input["category_id"]) &&
            !empty($input["name"]) &&
            !empty($input["description"]) &&
            !empty($input["price"]) &&
            !empty($input["stock"]) &&
            !empty($input["image"]) &&
            mb_strlen($input["name"]) <= 64 &&
            is_numeric($input["price"]) &&
            $input["price"] > 0 &&
            is_numeric($input["stock"]) &&
            $input["stock"] > 0
        );        
      

        
    }




    if($_SERVER["REQUEST_METHOD"] === "GET") {

        if(!empty($id)) {
            $data = $model->getId($id);

            if(empty($data)) {
                http_response_code(404);
                $data = ["message" => "Not Found"];
            }
        }
        else{
            $data = $model->get();
        }
    }
    elseif($_SERVER["REQUEST_METHOD"] === "POST") {

        $input = json_decode(file_get_contents("php://input"), true);//* este comando do php permite o php
        //*ir ler o body do request por post e buscar os adsos para guardar

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

        if(validator($input)) {
            http_response_code(202);

            $input["product_id"] = $id;
      
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
        






















