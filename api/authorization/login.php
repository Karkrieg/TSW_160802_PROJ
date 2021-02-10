<?php 
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: POST");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
    
    function msg($success,$status,$message,$extra = []){
        return array_merge([
            'success' => $success,
            'status' => $status,
            'message' => $message
        ],$extra);
    }
    
    require __DIR__.'/../../configuration/Database.php';

    $db = new Database();
    $conn = $db->connect();
    
    $data = json_decode(file_get_contents("php://input"));
    $returnData = [];
    
    // Jeśli nie użyto metody POST
    if($_SERVER["REQUEST_METHOD"] != "POST"){
        $returnData = msg(0,404,'Strona nie została znaleziona!');
    }
    // Sprawdzanie, czy wystąpiły puste pola (ustawione, bądź spacje)
    elseif(!isset($data->username) 
        || !isset($data->password)
        || empty(trim($data->username))
        || empty(trim($data->password))
        ){
    // Zdefiniowanie pól potrzebnych do logowania
        $fields = ['Pola' => ['nazwa użytkownika','hasło']];
        $returnData = msg(0,422,'Proszę uzupełnić wszystkie pola!',$fields);
        }
    else{
        // Usunięcie spacji
        $username = trim($data->username);
        $password = trim($data->password);    
        
        // Logowanie
            try{
                
                $fetch_user_by_username = "SELECT * FROM `users` WHERE `username`=:username";
                $query_stmt = $conn->prepare($fetch_user_by_username);
                $query_stmt->bindValue(':username', $username,PDO::PARAM_STR);
                $query_stmt->execute();
    
    
                // IF THE USER IS FOUND BY username
                if($query_stmt->rowCount()){
                    $row = $query_stmt->fetch(PDO::FETCH_ASSOC);
                    $check_password = password_verify($password, $row['password']);
    
                    // Weryfikacja hasła
                    if($check_password){
                        
                        $returnData = [
                            'success' => 1,
                            'message' => 'Zalogowałes się!',
                            'grupa' => $row['gid']
                        ];
                        session_start();
                        $_SESSION['uid'] = $row['id'];
                        $_SESSION['uName'] = $row['username'];
                        $_SESSION['uGroup'] = $row['gid'];
                    }
                    // Jeśli podane zostało błędne hasło
                    else{
                        $returnData = msg(0,422,'Nieprawidłowe hasło!');
                    }
                }
                // Jeżeli nie znaleziono użytkownika
                else{
                    $returnData = msg(0,422,'Nie znaleziono użytkownika!');
                }
            }
            catch(PDOException $e){
                $returnData = msg(0,500,$e->getMessage());
            }
    }

    echo json_encode($returnData);
?>