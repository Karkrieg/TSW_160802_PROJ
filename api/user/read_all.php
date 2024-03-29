<?php

// if (!isset($_SESSION['uid'])) {
//     echo '<h2>NIE JESTEŚ ZALOGOWANY!</h2>';
//     exit();
// }

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }

    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../configuration/Database.php';
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate user object
    $user = new User($db);

    // User query
    $result = $user->read_all();

    // Ilość wierszy
    $rownum = $result->rowCount();

    // Sprawdzenie, czy istnieją jacykolwiek użytkownicy
    if($rownum > 0) {
        // User array
        $user_arr = array();
        $user_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $user_item = array(
                'id' => $id,
                'gid' => $gid,
                'grupa' => $grupa,
                'username' => $username,
                'name' => $name,
                'surname' => $surname,
                'email' => $email
            );
            
            // Push to "data"
            array_push($user_arr['data'], $user_item);
        }

        // Turn to JSON
        echo json_encode($user_arr);

    } else {
        // Nie ma użytkowników
        echo json_encode(
            array('message' => 'Nie znaleziono użytkowników!')
        );
    }
?>