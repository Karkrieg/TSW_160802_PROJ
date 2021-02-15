<?php

// if (!isset($_SESSION['uid'])) {
//     echo '<h2>NIE JESTEŚ ZALOGOWANY!</h2>';
//     exit();
// }

    if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
        exit;
    }
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    //header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../configuration/Database.php';
    include_once '../../models/Test.php';

    // Nawiązanie połączenia z bazą danych
    $database = new Database();
    $db = $database->connect();

    // Nowy obiekt test
    $test = new Test($db);

    // Dekodowanie JSON
    $data = json_decode(file_get_contents("php://input"));

    $arr_of_empty_keys = array();

    //Dodatkowe sprawdzenie, które wartości są zmieniane

    if(empty($data->gid)){
        array_push($arr_of_empty_keys,'gid');
    }
    if (empty($data->tytul)){
        array_push($arr_of_empty_keys,'tytul');
    }
    if (empty($data->dane)){
        array_push($arr_of_empty_keys,'dane');
    }
    //echo(count($arr_of_empty_keys));

    if(count($arr_of_empty_keys) != 0){
        $test_temp = new Test($db);
        $test_temp->id = $data->id;
        $test_temp->read_single();
        $test_arr = array(
            'id' => $test_temp->id,
            'gid' => $test_temp->gid,
            'tytul' => $test_temp->tytul,
            'dane' => $test_temp->dane
        );
        foreach($arr_of_empty_keys as $row){
            $data->{$row} = $test_temp->{$row};
        }
    }

    // Ustawienie id do update
    $test->id = $data->id;
    $test->gid = $data->gid;
    $test->tytul = $data->tytul;
    $test->dane = $data->dane;
    
    // //Sprawdzenie, czy test już istnieje w bazie danych
    // $temp_test_arr = CallAPI('POST','http://localhost/TSW_160802_PROJ/api/test/read_all.php');
    // $temp_test_arr = json_decode($temp_test_arr);

    // //print_r($temp_test_arr);

    // $too_many_tests_with_the_same_testname = FALSE;

    // // Sprawdzenie, czy jest zmieniana nazwa użytkownika (powtarzać się może testname tylko w przypadku update tego samego użytkownika)
    // foreach($temp_test_arr->data as $row){
    //     if(($row->testname == $test->testname) && ($row->id != $test->id))
    //     {
    //         //echo($row->testname.$row->id.$test->id);
    //         $too_many_tests_with_the_same_testname = TRUE;
    //     }
    // } 
    
    
    // IF THE test IS FOUND BY testname
    // if($too_many_tests_with_the_same_testname == FALSE){
       
            // Update testu
            if($test->update()) {
                echo json_encode(
                    array('message' => 'Test Updated')
                );
            } else {
                echo json_encode(
                    array('message' => 'Test NOT Updated')
                );
            }
    // } else{
    //     echo json_encode(
    //         array('message' => 'Test name already in use!')
    //     );
    // }
    

?>