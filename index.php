<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    exit(0);
}


        require_once 'BlogController.php';   
        require_once 'BlogModel.php';    
        require_once 'config.php';



$database = new Database();
$db = $database->getConnection();


$blogController = new BlogController($db);


$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_method = $_SERVER['REQUEST_METHOD'];

$uri_segments = explode('/', trim($request_uri, '/'));



if (count($uri_segments) < 2 || $uri_segments[0] !== 'api' || $uri_segments[1] !== 'posts') {
    http_response_code(404);
    echo json_encode(['message' => 'Invalid API endpoint.']);
    exit();
}

 $action = $uri_segments[1];
$id = isset($uri_segments[2]) ? (int)$uri_segments[2] : null;



switch ($request_method) {
    case 'POST':
        
            $blogController->addNew();
        
        break;

    case 'GET':          
       
        if ( $id !== null) {
            $blogController->get($id);
        } else {
            $blogController->getAll();
        }
        break;

    case 'PUT':
        if ( $id !== null) {
            $blogController->update($id);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid request.']);
        }
        break;

    case 'DELETE':
        if ( $id !== null) {
            $blogController->delete($id);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid request.']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Action not found.']);
        break;
}
?>
