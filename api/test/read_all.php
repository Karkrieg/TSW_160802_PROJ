<?php
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
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
    $test = new Test($db);

    // test query
    $result = $test->read_all();

    // Ilość wierszy
    $rownum = $result->rowCount();

    // Sprawdzenie, czy istnieją jakiekolwiek testy
    if($rownum > 0) {
        // test array
        $test_arr = array();
        $test_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $test_item = array(
                'id' => $id,
                'gid' => $gid,
                'tytul' => $tytul,
                'pytania' => json_decode($pytania)
            );
            
            // Push do "data"
            array_push($test_arr['data'], $test_item);
        }

        // Zmień na JSON
        echo json_encode($test_arr);

    } else {
        // Nie ma użytkowników
        echo json_encode(
            array('message' => 'Nie znaleziono testów!')
        );
    }
?>