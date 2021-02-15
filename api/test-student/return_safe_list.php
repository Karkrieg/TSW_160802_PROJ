<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit;
}

// Header
header('Access-Control-Allow-Origin: *');

include_once "../../configuration/Database.php";
include_once '../../models/Test.php';

$data = json_decode(file_get_contents("php://input"));

$top = "";
$middle = "";
$bottom = "";

if (isset($_SESSION['uGroup'])) {


    $test_arr = CallAPI('POST', 'http://localhost/TSW_160802_PROJ/api/test/read_all.php');
    $test_arr = json_decode($test_arr, true);
    $index = 0;

    //     foreach ($test_arr as $key => $row) {
    //         if (isset($test_arr['data']['0']['dane'])) {
    //             unset($test_arr['data']['0']['dane']);
    //         }
    //         if (isset($test_arr) && $test_arr['data']['0']['gid'] != $_SESSION['uGroup'] && $_SESSION['uGroup'] > 2) {
    //             unset($test_arr['data'][$index]);
    //            // print_r($test_arr['data'][])
    //         }
    //         $index++;
    //     }
    //     //print_r(json_encode($test_arr));
    // } //else print_r(json_encode(array('message' => '')))

    //$('#inside').html("");
    $i = 0;
    $index = 0;

        $top =
            '<table class="table text-center table-bordered table-striped table-dark mt-3">' .
            '<thead><tr>' .
            '<th scope="col" span=2>N</th>' .
            '<th scope="col">Grupa</th>' .
            '<th scope="col">Tytuł</th>' .
            '<th scope="col">Max. l. punktów</th>' .
            '<th scope="col">Akcja</th>' .
            '</tr></thead>' .
            '<tbody>';

        foreach ($test_arr['data'] as $key => $row) {
            if($_SESSION['uGroup'] == $row['gid'] || $_SESSION['uGroup'] < 3){
            $middle .=
                '<tr class="align-middle">';
            $middle .=
                '<th scope="row">' . ++$i . '</th>' .
                '<td>' . $row['grupa'] . '</td>' .
                '<td>' . $row['tytul'] . '</td>' .
                '<td>' . $row['dane']['max_pkt'] . '</td>' .
                '<td>';
            $middle .= '<button type="button" name="napisz" class="btn me-2 btn-primary btn-xs edit" id="' . $row['id'] . '">Napisz test' .
                '</button>';

            $middle .= '</td></tr>';
            //echo $content.print_r(($key[$index]->gid));
            }
            //print_r($row);
            $index++;
        }
        $bottom = '</tbody></table>';
       // echo ($content);
    }


echo $top.$middle.$bottom;

?>
