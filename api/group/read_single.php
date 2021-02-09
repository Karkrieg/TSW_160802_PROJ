<?php

    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
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

    // Get id
    $group->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get group
    if($group->read_single()){

    // Stworzenie tablicy
    $group_arr = array(
        'id' => $group->id,
        'name' => $group->name,
    );

    // Stwórz JSON
    print_r(json_encode($group_arr));
    }
    else {
        print_r(json_encode(array("message" => "Nie udało się wczytać użytkownika")));
    }
