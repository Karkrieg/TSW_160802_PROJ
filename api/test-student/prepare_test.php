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

    include_once "../../configuration/Database.php";


    // Get id
    $test_id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get test
    $test_arr = CallAPI('GET','http://localhost/TSW_160802_PROJ/api/test/read_single.php?id='.$test_id);
    //print_r($test_arr);
    $test_arr = json_decode($test_arr,true);
    $index = 0;
    foreach($test_arr as $key => $row){
        if(isset($test_arr['dane']['pytania'][$index]['data']['pf'])){
            unset($test_arr['dane']['pytania'][$index]['data']['pf']);
        } else if(isset($test_arr['dane']['pytania'][$index]['data']['odpowiedz'])){
            unset($test_arr['dane']['pytania'][$index]['data']['odpowiedz']);
        }
        $index++;
    }
    //print_r($test_arr);
    
    //var_dump($test_arr);

    //print_r(json_decode($test_arr));

    // Stwórz JSON
    print_r(json_encode($test_arr));

