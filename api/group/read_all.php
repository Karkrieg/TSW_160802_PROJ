<?php
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }

    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../configuration/Database.php';
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate group object
    $group = new Group($db);

    // group query
    $result = $group->read_all();

    // Ilość wierszy
    $rownum = $result->rowCount();

    // Sprawdzenie, czy istnieją jacykolwiek użytkownicy
    if($rownum > 0) {
        // Group array
        $group_arr = array();
        $group_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $group_item = array(
                'id' => $id,
                'name' => $name
            );
            
            // Push do "data"
            array_push($group_arr['data'], $group_item);
        }

        // Zmień na JSON
        echo json_encode($group_arr);

    } else {
        // Nie ma użytkowników
        echo json_encode(
            array('message' => 'Nie znaleziono grup!')
        );
    }
?>