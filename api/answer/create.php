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

    $dane_json = array(
        'l_elem' => $data->l_elem,
        'max_pkt' => $data->max_pkt,
        'dane' => $data->dane
    );

    $test->dane = json_encode($dane_json);

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