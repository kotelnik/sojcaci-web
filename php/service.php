<?php

// headers
header('X-Frame-Options: DENY');
header('Strict-Transport-Security: max-age=10886400; includeSubDomains; preload');
header('Access-Control-Allow-Origin: *'); //TODO DELETE!!
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); //TODO DELETE!!
header("Access-Control-Allow-Headers: Origin, Content-Type"); //TODO DELETE!!

// Handling the Preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

$KNOWN_API_PATHS = ['/user', '/news', '/event', '/team'];

if (!isset($_GET['path'])) {
    http_response_code(400);
    echo 'You need to set a path parameter.';
    exit();
}

$api_path = $_GET['path'];

if (!in_array($api_path, $KNOWN_API_PATHS)) {
    http_response_code(404);
    echo 'Unknown path.';
    exit();
}

// start a session (load one)
ini_set('session.cookie_httponly', 1);
session_start();

// includes
include './connection.php';
include './util.php';

/*
if (!LoginHelper::isLogged()) {
    http_response_code(401);
    echo 'You need to log in.';
    exit();
}

$PERSON_ID = $_SESSION["personId"];
*/
$PERSON_ID = "1";

include "./service$api_path.php";

?>