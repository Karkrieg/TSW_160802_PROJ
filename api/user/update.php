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
    include_once '../../models/User.php';

    // Nawiązanie połączenia z bazą danych
    $database = new Database();
    $db = $database->connect();

    // Nowy obiekt User
    $user = new User($db);

    // Dekodowanie JSON
    $data = json_decode(file_get_contents("php://input"));

    $arr_of_empty_keys = array();

    //Dodatkowe sprawdzenie, które wartości są zmieniane
    $PASS_CHANGE = TRUE;

    if(empty($data->grupa)){
        array_push($arr_of_empty_keys,'grupa');
    }
    if (empty($data->username)){
        array_push($arr_of_empty_keys,'username');
    }
    if (empty($data->name)){
        array_push($arr_of_empty_keys,'name');
    }
    if (empty($data->surname)){
        array_push($arr_of_empty_keys,'surname');
    }
    if (empty($data->email)){
        array_push($arr_of_empty_keys,'email');
    }
    if (empty($data->password)){
        array_push($arr_of_empty_keys,'password');
        $PASS_CHANGE = FALSE;
    }
    //echo(count($arr_of_empty_keys));

    if(count($arr_of_empty_keys) != 0){
        $user_temp = new User($db);
        $user_temp->id = $data->id;
        $user_temp->read_single_wp();
        $user_arr = array(
            'id' => $user_temp->id,
            'grupa' => $user_temp->grupa,
            'username' => $user_temp->username,
            'name' => $user_temp->name,
            'surname' => $user_temp->surname,
            'email' => $user_temp->email,
            'password' => $user_temp->password
        );
        foreach($arr_of_empty_keys as $row){
            $data->{$row} = $user_temp->{$row};
        }
    }

    // Ustawienie id do update
    $user->id = $data->id;
    $user->grupa = $data->grupa;
    $user->username = $data->username;
    $user->name = $data->name;
    $user->surname = $data->surname;
    $user->email = $data->email;
    if(!$PASS_CHANGE){
        $user->password = $data->password;
    }
    else{
        $user->password = password_hash($data->password, PASSWORD_DEFAULT);
    
    }
    
    //Sprawdzenie, czy użytkownik już istnieje w bazie danych
    $temp_user_arr = CallAPI('POST','http://localhost/TSW_160802_PROJ/api/user/read_all.php');
    $temp_user_arr = json_decode($temp_user_arr);

    //print_r($temp_user_arr);

    $too_many_users_with_the_same_username = FALSE;

    // Sprawdzenie, czy jest zmieniana nazwa użytkownika (powtarzać się może username tylko w przypadku update tego samego użytkownika)
    foreach($temp_user_arr->data as $row){
        if(($row->username == $user->username) && ($row->id != $user->id))
        {
            //echo($row->username.$row->id.$user->id);
            $too_many_users_with_the_same_username = TRUE;
        }
    } 
    
    
    // IF THE USER IS FOUND BY username
    if($too_many_users_with_the_same_username == FALSE){
       
            // Update użytkownika
            if($user->update()) {
                echo json_encode(
                    array('message' => 'User Updated')
                );
            } else {
                echo json_encode(
                    array('message' => 'User NOT Updated')
                );
            }
    } else{
        echo json_encode(
            array('message' => 'User name already in use!')
        );
    }
    

?>