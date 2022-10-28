<?php

    //echo $_SERVER["REQUEST_URI"];


    session_start();


    header("Content-Type: application/json");
    //converter o tipo de dados para json
    //em rest api os dados tem que ser sempre em json

    require("vendor/autoload.php");

    define("ENV", parse_ini_file(".env"));

    define(
        "ROOT",
        rtrim(
            str_replace(
                "\\", "/", dirname($_SERVER["SCRIPT_NAME"])
            ),
            "/"
        )
    );

    $url_parts = explode("/", $_SERVER["REQUEST_URI"]);

    /*echo "<pre>";//</pre>"
    print_r($url_parts);
    exit;*/

    /* white list de controllers permitidos */
    $controllers = [
        "users",
        "products",
        "orders",
        "login"
    ];

    $controller = $url_parts[2] ?: "home";
    $id = $url_parts[3] ?? "";

    if( !in_array($controller, $controllers) ) {
        http_response_code(404);
        die("Página não encontrada");
    }

    require("controllers/controller." .$controller. ".php");

?>