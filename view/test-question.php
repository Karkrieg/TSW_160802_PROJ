<?php include_once 'header.php'; ?>

<div class="row">
    <div class="col-lg-8 mx-auto p-5">
        <h1 class="text-center">Kreator testu</h1>
        <div id="inside">

            <form action="#" method="POST" id="pytanie-test">

                <div class="form-group">
                    <label for="obraz">Obraz (opcjonalnie)</label>
                    <input type="file" accept="image/*" class="form-control" id="obraz" aria-describedby="Obrazek_do_dodania" aria-label="Upload">
                </div>

                <div class="w-100 invisible" id="obr-div">
                    <img src="" id="preview" class="img-fluid mt-3 mx-auto d-block" alt="obraz">
                </div>
                <hr class="mb-2">

                <div class="form-group">
                    <label for="grupa">Typ pytania</label>
                    <select class="form-select" name="typ-pytania" id="opt-group">
                        <option value="1">Prawda/Fałsz</option>
                        <option value="2">1-krotnego wyboru</option>
                        <option value="3">Opisowe</option>
                        <option value="4">Wielokrotnego wyboru</option>
                    </select>
                </div>

                <hr class="mb-2">

                <div id="inside">
                    <!-- Zawartość (w zależności od wybranego typu pytania) -->
                </div>

                <div class="form-group d-flex w-100">
                    <button type="button" id="b-left" class=" btn btn-danger mt-2 ms-0 w-auto">&leftarrow; Poprzednie pytanie</button>
                    <button type="button" id="b-center" class=" btn btn-success mt-2 mx-auto w-auto">Zapisz test</button>
                    <button type="button" id="b-right" class=" btn btn-primary mt-2 me-0 w-auto">Następne pytanie &rightarrow;</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>



<script>
    ////////////////////////////////////////////////////////////////////////////////////////////
    //data{ id | question{ type: | image: | } | } |
    ////////////////////////////////////////////////////////////////////////////////////////////



    // Pytania
    const pytania = {l_elem:0,dane:[]};
    const temp_obj = {};

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



    $(document).ready(function() {
        $('#obr-div').hide();
        $('#obr-div').removeClass('invisible');

        function img2base64(image) {
            if (image.files && image.files[0]) {

                let reader = new FileReader();

                reader.onload = function(event) {
                    $('#preview').attr('src', event.target.result);
                }
                reader.readAsDataURL(image.files[0]);
                return true;
            }
            return false;
        }

        $('#obraz').change(function(e) {
            let isImage = img2base64(this);
            if (isImage)
                $('#obr-div').show();
            else
                $('#obr-div').hide();
        });

        $('#b-left').click(function() {
            
        });
        $('#b-center').click(function() {

        });
        $('#b-right').click(function() {
            temp_obj.id = '2a';
            temp_obj.nazwa = "obogowie ratunku";
            pytania.l_elem = pytania.l_elem + 1;
            pytania.dane.push(temp_obj);
            console.log(temp_obj);
            let z = JSON.stringify(pytania);
            console.log(z);
            console.log(JSON.parse(z));
            //console.log(pytania.dane[0]);
        });



    });


</script>