<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
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

    $user->username = $data->username;
    $user->name = $data->name;
    $user->surname = $data->surname;
    $user->password = password_hash($data->password, PASSWORD_DEFAULT);

    // Update użytkownika
    if($user->update()) {
        echo json_encode(
            array('message' => 'User Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'User NOT Updated')
        );
    }


?>