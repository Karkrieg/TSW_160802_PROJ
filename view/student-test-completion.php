<?php include_once 'header.php'; ?>
<?php if (!isset($_SESSION['uid'])) {
    echo '<h2 class="text-center bg-danger my-5 py-5">NIE JESTEŚ ZALOGOWANY!</h2>';
    exit();
}

$tid = isset($_GET['tid']) ? $_GET['tid'] : header('Location: available-tests.php');

?>

<div class="row p-4">
    <div class="col-lg-8 mx-auto p-5 bg-white shadow">
        <div>
            <h1 class="text-center bg-dark text-light p-2 rounded" id="tytul-testu">
                <!-- Tytuł testu -->
            </h1>
            <hr class="mb-2">

            <h5 class="text-end pt-2" id="ilosc-punktow-za-pytanie">
                <!-- Liczba punktów za pytanie -->
            </h5>
        </div>

        <hr class="mb-2">

        <form action="#" method="POST" id="pytanie-test">
            <div id="test"></div>


            <div class="w-100" id="obr-test">
                <img src="" data-bs-toggle="modal" style="cursor: pointer;" data-bs-target="#modal-image" id="preview" class="img-fluid mx-auto d-block" alt="obraz">
                <hr class="mb-2">
            </div>


            <!-- <div class="form-group">
                <label for="grupa">Typ pytania</label>
                <select class="form-select" name="typ" id="opt-group-question" required>
                    <option value="" selected>Typ pytania</option>
                    <option value="1">Prawda/Fałsz</option>
                    <option value="2">1-krotnego wyboru (2)</option>
                    <option value="3">1-krotnego wyboru (3)</option>
                    <option value="4">1-krotnego wyboru (4)</option>
                    <option value="5">1-krotnego wyboru (5)</option>
                    <option value="6">1-krotnego wyboru (6)</option>
                    <option value="7">Opisowe</option>
                </select>
            </div> -->

            <!-- <div class="form-group">
                <label for="obraz">Treść pytania</label>
                <input type="text" class="form-control" name="tresc" id="nazwa" required>
            </div> -->

            <p id="tresc-pytania" class="fw-bold fs-3">
                <!-- Tresc pytania -->
            </p>

            <hr class="mb-2">

            <div id="inside" class="form">
                <!-- Zawartość (w zależności od wybranego typu pytania) -->
            </div>

            <div class="form-group d-flex w-100">
                <button type="button" id="b-left" value="prev" class=" btn btn-warning mt-2 w-50 py-2  me-auto">&leftarrow; Poprzednie pytanie</button>
                <button type="button" id="b-right" value="next" class=" btn btn-primary mt-2 w-50 py-2  ms-auto">Następne pytanie &rightarrow;</button>
            </div>
            <div class="form-group d-flex mx-auto">
                <button type="submit" id="b-sub" value="send" class=" btn btn-success mt-5 py-4 mx-auto"> &plus; Wyślij test &plus;</button>
            </div>

        </form>
        <div class="text-center bg-dark text-light mt-4 col-4 mx-auto rounded p-1">
            <h3 class=" bg-dark text-light rounded mx-auto " id="licznik">1</h3>
        </div>
        <h5 class="text-center mb-4 mx-auto p-1">Nr pytania</h5>


    </div>
</div>

<?php include_once 'footer.php'; ?>


<!-- Powiększanie obrazu z pytania - MODAL -->
<div class="modal fade" id="modal-image" tabindex="-1" aria-labelledby="modal-image" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="" id="preview2" class="mx-auto d-block" alt="obraz">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>



<script>
    ////////////////////////////////////////////////////////////////////////////////////////////
    //data{ id | question{ type: | image: | } | } |
    ////////////////////////////////////////////////////////////////////////////////////////////



    // Pytania
    const test_template = {
        tytul: "",
        tid: 0,
        gid: 0,
        l_elem: 0,
        max_pkt: 0,
    };
    //const pytanie = {};
    let test_student = {};

    // Dane z formularza do JSON
    (function($) {
        $.fn.serializeFormJSON = function() {

            var object = {};
            var array = this.serializeArray();
            $.each(array, function() {
                if (object[this.name]) {
                    if (!object[this.name].push) {
                        object[this.name] = [object[this.name]];
                    }
                    object[this.name].push(this.value || '');
                } else {
                    object[this.name] = this.value || '';
                }
            });
            return object;
        };
    })(jQuery);


    function base64ToImg(base64string) {
        $('#preview').attr('src', base64string);
    }



    $(document).ready(function() {
        let curr_question = 0;

        $('#tytul-testu').html(" ");

        function get_test_template() {
            i = 0;

            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/test-student/prepare_test.php?id=<?php echo $tid;  ?>",
                method: "get",
                success: function(response) {
                    curr_question = 0;
                    //$('#inside').html('');
                    //$('#test').html(response); 
                    let today = Date.now() / 1000 - (Date.now() % 1000) / 1000;
                    //let date = today.getDate();
                    test_student.start = today;
                    //console.log(test_student);
                    //let check = new Date(test_student.start * 1000);
                    test_template.tytul = response.tytul;
                    test_template.gid = response.gid;
                    test_template.tid = response.id;
                    test_template.l_elem = response.dane.l_elem;
                    test_template.max_pkt = response.dane.max_pkt;
                    test_template.dane = response.dane;
                    test_student = test_template;
                    console.log(test_template);

                    if (test_template.dane.pytania[curr_question].data.hasOwnProperty('obraz')) {
                        base64ToImg(test_template.dane.pytania[curr_question].data.obraz);
                        $('#obr-test').show();
                    } else {
                        $('#preview').attr('src', '');
                        $('#obr-test').hide();
                    }
                    // TYTUŁ TESTU
                    $('#tytul-testu').html(test_template.tytul);

                    //ILOŚĆ PUNKTÓW ZA PYTANIE
                    $('#ilosc-punktow-za-pytanie').html('Pytanie za ' + test_template.dane.pytania[curr_question].data.punkty + ' pkt.');

                    //LICZNIK PYTAŃ
                    $('#licznik').html((curr_question + 1) + '/' + test_template.l_elem);

                    //TREŚĆ PYTANIA
                    $('#tresc-pytania').html(test_template.dane.pytania[curr_question].data.tresc);

                    //PRZYCISKI <- oraz ->
                    if (curr_question == 0) {
                        $('#b-left').hide();
                    }
                    if ((curr_question + 1) == test_template.l_elem) {
                        $('#b-right').hide();
                    }
                    if ((curr_question + 1) != test_template.l_elem) {
                        $('#b-right').show();
                    }
                    if (curr_question != 0) {
                        $('#b-left').show();
                    }

                    // Wczytanie odpowiedzi dla 1-go pytania:
                    let content = "";
                    //console.log(typ);
                    if (test_template.dane.pytania[curr_question].data.typ == 1) {
                        content += '<div class="d-flex mb-4"><input type="radio" class="btn-check" value="1" name="odpowiedz" id="success-outlined" required>' +
                            '<label class="btn btn-outline-success me-auto w-25" for="success-outlined">PRAWDA</label>' +
                            '<input type="radio" class="btn-check" value="0" name="odpowiedz" id="danger-outlined" >' +
                            '<label class="btn btn-outline-danger ms-auto w-25" for="danger-outlined">FAŁSZ</label></div><hr class="mb-2">';

                    } else if (test_template.dane.pytania[curr_question].data.typ >= 2 && test_template.dane.pytania[curr_question].data.typ <= 6) {
                        for (let i = 1; i <= test_template.dane.pytania[curr_question].data.typ; i++) {
                            content += '<div class="form-check">' +
                                '<input class="form-check-input" type="radio" id="pytanie' + i + '" name="odpowiedz" value="' + i + '">' +

                                '<label class="form-check-label" for="pytanie' + i + '">' + test_template.dane.pytania[curr_question].data['pytanie' + i] + '</label>' +
                                '</div><hr class="mb-2">';
                        }
                    } else if (test_template.dane.pytania[curr_question].data.typ == 7) {
                        content += '<div class="mb-3">' +
                            '<label for="tekstowe" class="form-label">Odpowiedź pisemna</label>' +
                            '<textarea class="form-control" id="tekstowe" name="odpowiedz" rows="3"></textarea>' +
                            '</div>';
                    }
                    $('#inside').html(content);

                },
                error: function(xhr, status, err) {
                    console.log(xhr, status, err);
                }
            });
        }
        get_test_template();



        // // Wciśnięcie zielonego przycisku po lewej
        $('#b-left').click(function() {
            // Uzupełnianie odpowiedzi w test_template
            let formData = $('#pytanie-test').serializeFormJSON();
            test_template.dane.pytania[curr_question].data.odpowiedz = formData.odpowiedz;
            console.log(test_template);

            if (curr_question > 0)
                curr_question--;
            console.log(curr_question);

            // OBRAZ
            if (test_template.dane.pytania[curr_question].data.hasOwnProperty('obraz')) {
                base64ToImg(test_template.dane.pytania[curr_question].data.obraz);
                $('#obr-test').show();
            } else {
                $('#preview').attr('src', '');
                $('#obr-test').hide();
            }

            //ILOŚĆ PUNKTÓW ZA PYTANIE
            $('#ilosc-punktow-za-pytanie').html('Pytanie za ' + test_template.dane.pytania[curr_question].data.punkty + ' pkt.');

            //LICZNIK PYTAŃ
            $('#licznik').html((curr_question + 1) + '/' + test_template.l_elem);

            //TREŚĆ PYTANIA
            $('#tresc-pytania').html(test_template.dane.pytania[curr_question].data.tresc);

            //PRZYCISKI <- oraz ->
            //PRZYCISKI <- oraz ->
            if (curr_question == 0) {
                $('#b-left').hide();
            }
            if ((curr_question + 1) == test_template.l_elem) {
                $('#b-right').hide();
            }
            if ((curr_question + 1) != test_template.l_elem) {
                $('#b-right').show();
            }
            if (curr_question != 0) {
                $('#b-left').show();
            }


        });

        // // Wciśnięcie niebieskiego przycisku po prawej
        $('#b-right').click(function() {
            // Uzupełnianie odpowiedzi w test_template
            let formData = $('#pytanie-test').serializeFormJSON();
            test_template.dane.pytania[curr_question].data.odpowiedz = formData.odpowiedz;
            console.log(test_template);

            if ((curr_question + 1) < test_template.l_elem)
                curr_question++;
            console.log(curr_question);

            // OBRAZ
            if (test_template.dane.pytania[curr_question].data.hasOwnProperty('obraz')) {
                base64ToImg(test_template.dane.pytania[curr_question].data.obraz);
                $('#obr-test').show();
            } else {
                $('#preview').attr('src', '');
                $('#obr-test').hide();
            }

            //ILOŚĆ PUNKTÓW ZA PYTANIE
            $('#ilosc-punktow-za-pytanie').html('Pytanie za ' + test_template.dane.pytania[curr_question].data.punkty + ' pkt.');

            //LICZNIK PYTAŃ
            $('#licznik').html((curr_question + 1) + '/' + test_template.l_elem);

            //TREŚĆ PYTANIA
            $('#tresc-pytania').html(test_template.dane.pytania[curr_question].data.tresc);

            //PRZYCISKI <- oraz ->
            //PRZYCISKI <- oraz ->
            if (curr_question == 0) {
                $('#b-left').hide();
            }
            if ((curr_question + 1) == test_template.l_elem) {
                $('#b-right').hide();
            }
            if ((curr_question + 1) != test_template.l_elem) {
                $('#b-right').show();
            }
            if (curr_question != 0) {
                $('#b-left').show();
            }
        });


        // SUBMIT - DODANIE TESTU DO BAZY
        $('#pytanie-test div #b-sub').click(function(event) {
            event.preventDefault();
            let buttonType = $(this).attr('value');
            //console.log(buttonType);

            // ZATWIERDŹ PYTANIE
            //if (buttonType == 'next') {

            let data = $('#pytanie-test').serializeFormJSON();
            console.log(data);
            // Alerty poprawności formularza
            //let $this = $('#pytanie-test');
            //$this.get(0).reportValidity();


            // // Sprawdzenie poprawności formularza, dodanie pytania do listy 
            // if (!$('#pytanie-test')[0].checkValidity || $('#pytanie-test')[0].checkValidity()) {
            //     let data = $('#pytanie-test').serializeFormJSON();
            //     pytanie.typ = data['typ'];
            //     pytanie.tresc = data['tresc'];
            //     pytanie.punkty = data['punkty'];

            //     // 1-krotne dodanie danych ogólnych o teście
            //     if (test.l_elem == 0) {
            //         test.tytul = data['tytul'];
            //         test.gid = data['grupa'];
            //         delete data.tytul, data.grupa;
            //     }
            //     // usunięcie grupy i treści z 'data'
            //     delete data.grupa, data.tresc;
            //     // Inkrementacja licznika elementów i punktów
            //     test.l_elem += 1;
            //     test.max_pkt += parseInt(pytanie.punkty);
            //     //console.log(data);
            //     if (pytanie.hasOwnProperty('image')) {
            //         data.obraz = pytanie.image;
            //         test.dane.push({
            //             data
            //         });
            //     } else {
            //         test.dane.push({
            //             data
            //         });
            //     }
            //     console.log(test);
            //     //let myJSON = JSON.stringify(test);
            //     //console.log(myJSON);
            //     delete pytanie.image, data.obraz;
            // }

            // ZAPISZ TEST
            // } else if (buttonType == 'save' && test.l_elem >= 1) {
            //     //console.log("SAVE");
            //     //let data = $(this).serializeFormJSON();
            //     let myJSON = JSON.stringify(test);
            //     //console.log(myJSON);
            //     //console.log(JSON.parse(myJSON));

            //     $.ajax({
            //         url: "http://localhost/TSW_160802_PROJ/api/answers/create.php",
            //         method: "post",
            //         dataType: "json",
            //         contentType: "application/json; charset=utf-8",
            //         data: myJSON,
            //         success: function(response) {
            //             window.alert(response.message);
            //             window.location.replace('tests.php');
            //         },
            //         error: function(xhr, stat, err) {
            //             console.log(xhr, stat, err);
            //         }
            //     });
            // } else {
            //     window.alert("Próbujesz zapisać pusty test!");
            // }
        });


        // Ładowanie grup do rozwijanej listy
        $(document).ready(function() {

            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/group/read_all.php",
                method: "post",
                success: function(response) {
                    let options = response.data;
                    let content = "";
                    //console.log(options);
                    options.forEach(function(row, index, array) {
                        if (row.id > 2) {
                            content += '<option value="' + row.id + '">' + row.name + '</option>';
                        }
                    });
                    $('#opt-group').html(content);
                },
                error: function(xhr, status, err) {
                    console.log(err);
                }
            });
        });

        // Generowanie dalszej części pytań na podstawie typu pytania
        $(document).on('click', '#b-left, #b-right', function() {
            let typ = $(this).val();
            let content = "";
            //console.log(typ);
            if (test_template.dane.pytania[curr_question].data.typ == 1) {
                content += '<div class="d-flex mb-4"><input type="radio" class="btn-check" value="1" name="odpowiedz" id="success-outlined">' +
                    '<label class="btn btn-outline-success me-auto w-25" for="success-outlined">PRAWDA</label>' +
                    '<input type="radio" class="btn-check" value="0" name="odpowiedz" id="danger-outlined" >' +
                    '<label class="btn btn-outline-danger ms-auto w-25" for="danger-outlined">FAŁSZ</label></div><hr class="mb-2">';

            } else if (test_template.dane.pytania[curr_question].data.typ >= 2 && test_template.dane.pytania[curr_question].data.typ <= 6) {
                for (let i = 1; i <= test_template.dane.pytania[curr_question].data.typ; i++) {
                    content += '<div class="form-check">' +
                        '<input class="form-check-input" type="radio" id="pytanie' + i + '" name="odpowiedz" value="' + i + '">' +

                        '<label class="form-check-label" for="pytanie' + i + '">' + test_template.dane.pytania[curr_question].data['pytanie' + i] + '</label>' +
                        '</div><hr class="mb-2">';
                }
            } else if (test_template.dane.pytania[curr_question].data.typ == 7) {
                content += '<div class="mb-3">' +
                    '<label for="tekstowe" class="form-label">Odpowiedź pisemna</label>' +
                    '<textarea class="form-control" id="tekstowe" name="odpowiedz" rows="3"></textarea>' +
                    '</div>';
            }
            $('#inside').html(content);


            // Uzupełnianie treści pytań, na które odpowiedzi zostały już udzielone
            if (test_template.dane.pytania[curr_question].data.hasOwnProperty('odpowiedz')) {
                if (test_template.dane.pytania[curr_question].data.typ == 1) {
                    let $radiobtns = $('input:radio[name=odpowiedz]');

                    if ($radiobtns.is(':checked') === false) {
                        $radiobtns.filter('[value=' +
                            test_template.dane.pytania[curr_question].data.odpowiedz + ']').prop('checked', true);
                    }

                } else if (test_template.dane.pytania[curr_question].data.typ >= 2 && test_template.dane.pytania[curr_question].data.typ <= 6) {
                    let $radiobtns = $('input:radio[name=odpowiedz]');

                    if ($radiobtns.is(':checked') === false) {
                        $radiobtns.filter('[value=' +
                            test_template.dane.pytania[curr_question].data.odpowiedz + ']').prop('checked', true);
                    }

                } else if (test_template.dane.pytania[curr_question].data.typ == 7) {
                    let $tekstowe = $('#tekstowe');
                    if (!$tekstowe.val()) {
                        $tekstowe.text(test_template.dane.pytania[curr_question].data.odpowiedz);

                    }

                }
            }

        });

        // Wyświetlenie obrazu w otworzonym oknie modal
        $(document).on('shown.bs.modal', '#modal-image', function() {
            $('#preview2').attr('src', test_template.dane.pytania[curr_question].data.obraz);
        });

        // Zamknięcie obrazu w otworzonym oknie modal
        $(document).on('hidden.bs.modal', '#modal-image', function() {
            $('#preview2').attr('src', "");
        });

    });
</script>