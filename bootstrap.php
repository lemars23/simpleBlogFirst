<?php
use Dotenv\Dotenv;
use Models\Model as Model;
use Controllers\Controller as Controller;


require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . "/models/Model.php");
require_once("controllers/Controller.php");

spl_autoload_register(function ($class_name) {
    if(strripos($class_name, "Model")) {
        require_once($class_name . '.php');
    }
});

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();