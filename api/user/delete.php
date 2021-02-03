<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../configuration/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate user object
    $user = new User($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // Ustawienie id do update
    $user->id = $data->id;

    // Update użytkownika
    if($user->delete()) {
        echo json_encode(
            array('message' => 'User Deleted')
        );
    } else {
        echo json_encode(
            array('message' => 'User NOT Deleted')
        );
    }


?>