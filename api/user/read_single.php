<?php
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

    // Get id
    $user->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get user
    if($user->read_single()){

    // Stworzenie tablicy
    $user_arr = array(
        'id' => $user->id,
        'grupa' => $user->grupa,
        'username' => $user->username,
        'name' => $user->name,
        'surname' => $user->surname,
        'email' => $user->email
    );

    // Stwórz JSON
    print_r(json_encode($user_arr));
    }
    else {
        print_r(json_encode(array("message" => "Nie udało się wczytać użytkownika")));
    }
?>