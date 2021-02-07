<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../configuration/Database.php';
    include_once '../../models/Question.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Instatniate question object
    $question = new Question($db);

    // question query
    $result = $question->read_all();

    // Ilość wierszy
    $rownum = $result->rowCount();

    // Sprawdzenie, czy istnieją jakiekolwiek pytania
    if($rownum > 0) {
        // question array
        $question_arr = array();
        $question_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $question_item = array(
                'id' => $id,
                'question' => json_decode($question)
            );
            
            // Push to "data"

            array_push($question_arr['data'], $question_item);
        }

        // Turn to JSON
        echo json_encode($question_arr);

    } else {
        // Nie ma użytkowników
        echo json_encode(
            array('message' => 'No questions Found')
        );
    }
?>