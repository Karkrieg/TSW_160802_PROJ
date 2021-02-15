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
    include_once '../../models/User.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate user object
    $user = new User($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    $user->grupa = $data->grupa;
    $user->username = $data->username;
    $user->name = $data->name;
    $user->surname = $data->surname;
    $user->email = $data->email;
    $user->password = password_hash($data->password, PASSWORD_DEFAULT);

    //Sprawdzenie, czy użytkownik już istnieje w bazie danych
    $temp_user_arr = CallAPI('POST','http://localhost/TSW_160802_PROJ/api/user/read_all.php');
    $temp_user_arr = json_decode($temp_user_arr);

    //print_r($temp_user_arr);

    $too_many_users_with_the_same_username = FALSE;

    // Sprawdzenie, czy jest zmieniana nazwa użytkownika (powtarzać się może username tylko w przypadku update tego samego użytkownika)
    foreach($temp_user_arr->data as $row){
        if($row->username == $user->username)
        {
            //echo($row->username.$row->id.$user->id);
            $too_many_users_with_the_same_username = TRUE;
        }
    } 
    
    if($too_many_users_with_the_same_username == FALSE){
        // Stwórz użytkownika
        if($user->create()) {
            echo json_encode(
                array('message' => 'User Created')
            );
        } else {
            echo json_encode(
                array('message' => 'User NOT Created')
            );
        }
    } else{
        echo json_encode(
            array('message' => 'User with matching username already in database!')
        );
    }
    

?>