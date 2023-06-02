<?php
require "../bootstrap.php";
use Src\Controller\BookController;
use Src\Controller\UserController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode( '/', $uri );

$requestMethod = $_SERVER["REQUEST_METHOD"];

$id = null;
if (isset($uri[2])) {
    $id = (int) $uri[2];
}

switch($uri[1]){
    case "books":
        $controller = new BookController($dbConnection, $requestMethod, $id);
        break;
    case "user":
        $controller = new UserController($dbConnection, $requestMethod, $id);
    default:
        header("HTTP/1.1 404 Not Found");
    exit();
}

$controller->processRequest();