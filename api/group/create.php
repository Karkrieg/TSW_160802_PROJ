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
    include_once '../../models/Group.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate group object
    $group = new Group($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));
    $group->name = $data->name;


    //Sprawdzenie, czy użytkownik już istnieje w bazie danych
    $temp_group_arr = CallAPI('POST','http://localhost/TSW_160802_PROJ/api/group/read_all.php');
    $temp_group_arr = json_decode($temp_group_arr);

    //print_r($temp_group_arr);

    $too_many_groups_with_the_same_groupname = FALSE;

    // Sprawdzenie, czy jest zmieniana nazwa użytkownika (powtarzać się może groupname tylko w przypadku update tego samego użytkownika)
    foreach($temp_group_arr->data as $row){
        if($row->name == $group->name)
        {
            //echo($row->groupname.$row->id.$group->id);
            $too_many_groups_with_the_same_groupname = TRUE;
        }
    } 
    
    if($too_many_groups_with_the_same_groupname == FALSE){

    // Stwórz grupę
    if($group->create()) {
        echo json_encode(
            array('message' => 'Group Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Group NOT Created')
        );
    }
} else{
    echo json_encode(
        array('message' => 'Group with matching groupname already in database!')
    );
}
    

?>