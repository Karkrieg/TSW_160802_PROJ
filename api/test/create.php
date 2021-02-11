<?php
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        exit;
    }
   

    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../configuration/Database.php';
    include_once '../../models/Test.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate test object
    $test = new Test($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    
    $test->tytul = $data->tytul;
    $test->gid = $data->gid;
    $test->pytania = json_encode($data->pytania);

    


    // //Sprawdzenie, czy użytkownik już istnieje w bazie danych
    // $temp_test_arr = CallAPI('POST','http://localhost/TSW_160802_PROJ/api/test/read_all.php');
    // $temp_test_arr = json_decode($temp_test_arr);

    // //print_r($temp_test_arr);

    // $too_many_tests_with_the_same_testname = FALSE;

    // // Sprawdzenie, czy jest zmieniana nazwa użytkownika (powtarzać się może testname tylko w przypadku update tego samego użytkownika)
    // foreach($temp_test_arr->data as $row){
    //     if($row->name == $test->name)
    //     {
    //         //echo($row->testname.$row->id.$test->id);
    //         $too_many_tests_with_the_same_testname = TRUE;
    //     }
    // } 
    
    // if($too_many_tests_with_the_same_testname == FALSE){

    // Stwórz grupę
    if($test->create()) {
        echo json_encode(
            array('message' => 'Test Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Test NOT Created')
        );
    }
// } else{
//     echo json_encode(
//         array('message' => 'test with matching testname already in database!')
//     );
// }
    

?>