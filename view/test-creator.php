<?php include_once 'header.php'; ?>
<?php if (!isset($_SESSION['uid'])) {
    echo '<h2 class="text-center bg-danger my-5 py-5">NIE JESTEŚ ZALOGOWANY!</h2>';
    exit();
}
if ($_SESSION['uGroup'] > 2) {
    echo '<h2 class="text-center bg-danger my-5 py-5">JESTEŚ STUDENTEM :(</h2>';
    exit();
}
?>

<div class="row p-4">
    <div class="col-lg-8 mx-auto p-5 bg-white shadow">
        <h1 class="text-center">Kreator testu</h1>


        <form action="#" method="POST" id="pytanie-test">
            <div id="znikacz">
                <div class="form-group">
                    <label for="obraz">Nazwa testu</label>
                    <input type="text" name="tytul" class="form-control" id="test-name" required />
                </div>

                <hr class="mb-2">

                <div class="form-group mb-3">
                    <label for="grupa">Grupa</label>
                    <select class="form-select" name="grupa" id="opt-group">
                        <!-- Wnętrze -->
                    </select>
                </div>
                <hr class="mb-2">
            </div>


            <!-- <div class="w-100" id="obr-test">
                <img src="" id="test-image" class="img-fluid mt-3 mx-auto d-block" alt="obraz">
            </div> -->


            <div class="form-group">
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
                    <!-- <option value="8">Wielokrotnego wyboru</option> -->
                </select>
            </div>
            <hr class="mb-2">

            <div class="form-group">
                <label for="obraz">Obraz (opcjonalnie)</label>
                <input type="file" accept="image/*" class="form-control" id="obraz" aria-describedby="Obrazek_do_dodania" aria-label="Upload">
            </div>

            <div class="w-100" id="obr-div">
                <img src="" id="preview" class="img-fluid mt-3 mx-auto d-block" alt="obraz">
            </div>

            <hr class="mb-2">

            <div class="form-group text-center">
                <label for="punkty">Liczba punktów za pytanie</label>
                <input type="number" min="0" max="10" class="form-control w-25 mx-auto" name="punkty" required>
            </div>


            <hr class="mb-2">

            <div class="form-group">
                <label for="obraz">Treść pytania</label>
                <input type="text" class="form-control" name="tresc" id="nazwa" required>
            </div>

            <hr class="mb-2">

            <div id="inside">
                <!-- Zawartość (w zależności od wybranego typu pytania) -->
            </div>

            <div class="form-group d-flex w-100">
                <button type="submit" id="b-left" value="save" class=" btn btn-success mt-2 w-50  py-2 me-auto"> &plus; Zapisz Test &plus;</button>
                <button type="submit" id="b-right" value="next" class=" btn btn-primary mt-2 w-50 py-2  ms-auto">Zatwierdź pytanie &rightarrow;</button>
            </div>

        </form>
        <h3 class="text-center bg-dark text-light rounded-circle mt-4 mx-auto p-1" style="width: 45px;" id="licznik">1</h3>
        <h5 class="text-center  mb-4 mx-auto p-1">Nr pytania</h5>

    </div>
</div>

<?php include_once 'footer.php'; ?>



<script>
    ////////////////////////////////////////////////////////////////////////////////////////////
    //data{ id | question{ type: | image: | } | } |
    ////////////////////////////////////////////////////////////////////////////////////////////



    // Pytania
    const test = {
        tytul: "",
        gid: 0,
        l_elem: 0,
        max_pkt: 0,
        pytania: []
    };
    const pytanie = {};

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

    function imgPreview(image) {
        if (image.files && image.files[0]) {

            let reader = new FileReader();

            reader.onload = function(event) {
                $('#preview').attr('src', event.target.result);
            }
            let obraz = reader.readAsDataURL(image.files[0]);
            reader.onloadend = function() {
                pytanie.image = reader.result;
            }
            return true;
        }
        pytanie.image = "";
        delete pytanie.image;
        return false;
    }

    function imageToBase64(image) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = image.width;
        canvas.height = image.height;

        image.crossOrigin = 'anonymous';

        ctx.drawImage(image, 0, 0);

        return canvas.toDataURL;
    }

    function base64ToImg(base64string) {
        $('#preview').attr('src', base64string);
    }



    $(document).ready(function() {
        $('#obr-div').hide();
        // $('#obr-div').removeClass('invisible');

        // Pokazanie załadowanego obrazu
        $('#obraz').change(function(e) {
            let isImage = imgPreview(this);
            if (isImage) {
                $('#obr-div').show();
            } else {
                $('#obr-div').hide();
            }
        });

        // // Wciśnięcie zielonego przycisku po lewej
        // $('#b-left').click(function() {

        // });

        // // Wciśnięcie niebieskiego przycisku po prawej
        // $('#b-right').click(function() {

        // });


        // SUBMIT - DODANIE TESTU DO BAZY
        $('#pytanie-test div button').click(function(event) {
            event.preventDefault();
            let buttonType = $(this).attr('value');
            //console.log(buttonType);

            // ZATWIERDŹ PYTANIE
            if (buttonType == 'next') {

                //pytanie.id = '2a';
                //pytanie.nazwa = "obogowie ratunku";
                //test.l_elem = test.l_elem + 1;
                //test.dane.push(pytanie);
                //console.log(test);
                //let z = JSON.stringify(pytanie);
                //console.log(z);
                //console.log(JSON.parse(z));
                //console.log("NEXT");


                // Alerty poprawności formularza
                let $this = $('#pytanie-test');
                $this.get(0).reportValidity();

                // Sprawdzenie poprawności formularza, dodanie pytania do listy 
                if (!$('#pytanie-test')[0].checkValidity || $('#pytanie-test')[0].checkValidity()) {
                    let data = $('#pytanie-test').serializeFormJSON();
                    pytanie.typ = data['typ'];
                    pytanie.tresc = data['tresc'];
                    pytanie.punkty = data['punkty'];

                    // 1-krotne dodanie danych ogólnych o teście
                    if (test.l_elem == 0) {
                        test.tytul = data['tytul'];
                        test.gid = data['grupa'];
                        delete data.tytul, data.grupa;
                    }
                    // usunięcie grupy i treści z 'data'
                    delete data.grupa, data.tresc;
                    // Inkrementacja licznika elementów i punktów
                    test.l_elem += 1;
                    test.max_pkt += parseInt(pytanie.punkty);
                    //console.log(data);
                    if (pytanie.hasOwnProperty('image')) {
                        data.obraz = pytanie.image;
                        test.pytania.push({
                            data
                        });
                    } else {
                        test.pytania.push({
                            data
                        });
                    }
                    console.log(test);
                    //let myJSON = JSON.stringify(test);
                    //console.log(myJSON);
                    $('#znikacz').remove();
                    $('#pytanie-test').trigger('reset');
                    $('#opt-group-question').val('').change();
                    $('#licznik').text(test.l_elem + 1);
                    $('#preview').attr('src', "");
                    $('#obraz').val('').change();
                    delete pytanie.image, data.obraz;
                }

                // ZAPISZ TEST
            } else if (buttonType == 'save' && test.l_elem >= 1) {
                //console.log("SAVE");
                //let data = $(this).serializeFormJSON();
                let myJSON = JSON.stringify(test);
                //console.log(myJSON);
                //console.log(JSON.parse(myJSON));

                $.ajax({
                    url: "http://localhost/TSW_160802_PROJ/api/test/create.php",
                    method: "post",
                    dataType: "json",
                    contentType: "application/json; charset=utf-8",
                    data: myJSON,
                    success: function(response) {
                        window.alert(response.message);
                        window.location.replace('tests.php');
                    },
                    error: function(xhr, stat, err) {
                        console.log(xhr, stat, err);
                    }
                });
            } else {
                window.alert("Próbujesz zapisać pusty test!");
            }
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

        // Generowanie dalszej części formularza na podstawie wybranego typu pytania
        $('#opt-group-question').change(function() {
            let typ = $(this).val();
            let content = "";
            //console.log(typ);
            if (typ == 1) {
                content += '<div class="d-flex mb-4"><input type="radio" class="btn-check" value="1" name="odpowiedz" id="success-outlined" required>' +
                    '<label class="btn btn-outline-success me-auto w-25" for="success-outlined">PRAWDA</label>' +
                    '<input type="radio" class="btn-check" value="0" name="odpowiedz" id="danger-outlined" >' +
                    '<label class="btn btn-outline-danger ms-auto w-25" for="danger-outlined">FAŁSZ</label></div><hr class="mb-2">';

            } else if (typ >= 2 && typ <= 6) {
                for (let i = 1; i <= typ; i++) {
                    content += '<div class="input-group">' +
                        '<div class="input-group-text">' +
                        '<input class="form-check-input mt-0" type="radio" name="odpowiedz" value="' + i + '" required>' +
                        '</div>' +
                        '<input type="text" class="form-control" name="pytanie' + i + '" required>' +
                        '</div>';
                }
            }
            $('#inside').html(content);
        });

    });
    // zmiana pf na odpowiedz
</script>

