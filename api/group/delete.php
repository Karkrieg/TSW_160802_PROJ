<?php
    // if (!isset($_SESSION['uid'])) {
    //     echo '<h2>NIE JESTEŚ ZALOGOWANY!</h2>';
    //     exit();
    // }

    if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
        exit;
    }

    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../configuration/Database.php';
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate group object
    $group = new Group($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

//    if($data->id < 3){

        // Ustawienie id do update
        $group->id = $data->id;

        // Update użytkownika
        if($group->delete()) {
            echo json_encode(
                array('message' => 'Group Deleted')
            );
        } else {
            echo json_encode(
                array('message' => 'Group NOT Deleted')
            );
        }
//    } else {
//        echo json_encode(
//            array('message' => 'Odmowa dostępu - próba usunięcia kluczowych grup')
//        );
//    }


?>