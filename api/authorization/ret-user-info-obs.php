<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require __DIR__.'/../../configuration/Database.php';
require __DIR__.'/../../middleware/Auth.php';

$allHeaders = getallheaders();
$db = new Database();
$conn = $db->connect();
$auth = new Auth($conn,$allHeaders);

$returnData = [
    "success" => 0,
    "status" => 401,
    "message" => "Unauthorized"
];

if($auth->isAuthorised()){
    $returnData = $auth->isAuthorised();
}

echo json_encode($returnData);