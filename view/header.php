<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Quiz</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script> -->


</head>

<body>
    <!--Nawigacja-->
    <nav class="px-4 sticky-top navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">QUIZ</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <div class="dropdown d-flex">
                    <?php
                    if (isset($_SESSION['uid']) && $_SESSION['uGroup'] <= 2) {
                        echo '<a class="btn btn-outline-light dropdown-toggle my-2 " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Tabele</a>';
                        echo '<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">';
                        echo "<li><a href='users.php' id='user-crud' class='dropdown-item  my-2 '>Użytkownicy</a></li>";
                        echo "<li><a href='groups.php' id='group-crud' class='dropdown-item my-2 '>Grupy</a></li>";
                        echo "<li><a href='tests.php' id='test-crud' class='dropdown-item my-2 '>Testy</a></li>";
                        echo '</ul>';
                    }
                    ?>
                </div>
                <div class="dropdown d-flex">
                    <?php
                    if (isset($_SESSION['uid'])) {
                        echo '<a class="btn btn-outline-light dropdown-toggle me-auto mt-2 my-sm-0" href="#" id="navbarDropdown2" role="button" data-bs-toggle="dropdown" aria-expanded="false">Panel testów</a>';
                        echo '<ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown2">';
                        if($_SESSION['uGroup'] < 3)
                            echo "<li><a href='test-creator.php' id='test-question-nav' class='dropdown-item my-2'>Stwórz nowy test</a></li>";
                        echo "<li><a href='available-tests.php' id='test-question-student' class='dropdown-item my-2'>Dostępne testy</a></li>";
                        echo '</ul>';
                    }
                    ?>
                </div>
                <div class="d-flex ms-auto">
                    <?php
                    if (isset($_SESSION['uid'])) {
                        echo '<span class="my-2 text-light me-5">Zalogowano jako:   <b>' . $_SESSION['uName'] . '</b></span>';
                        echo '<a href="../api/authorization/logout.php" class="btn btn-outline-light me-2 my-2 my-sm-0">Wyloguj</a>';
                    } else {
                        echo '<a href="user-login.php" class="btn btn-outline-light me-2 my-2 my-sm-0">Logowanie</a>';
                        echo '<a href="user-register.php"  class="btn btn-outline-light my-2 my-sm-0">Rejestracja</a>';
                    };
                    ?>
                </div>
            </div>
        </div>
    </nav>
    <div id="wrap">
        <div id="main" class="container clear-top">