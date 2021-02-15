<?php

// if (!isset($_SESSION['uid'])) {
//     echo '<h2>NIE JESTEŚ ZALOGOWANY!</h2>';
//     exit();
// }


    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        exit;
    }

    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../configuration/Database.php';
    include_once '../../models/Test.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate test object
    $test = new test($db);

    // Get id
    $test->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get test
    if($test->read_single()){

    // Stworzenie tablicy
    $test_arr = array(
        'id' => $test->id,
        'gid' => $test->gid,
        'tytul' => $test->tytul,
        'dane' => json_decode($test->dane)
    );

    // Stwórz JSON
    print_r(json_encode($test_arr));
    }
    else {
        print_r(json_encode(array("message" => "Nie udało się wczytać testu")));
    }
