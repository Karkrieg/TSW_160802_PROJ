<?php define('MyConst', TRUE); ?>
<?php include_once 'header.php'; ?>

<div class="row m-5 pb-5">
    <div class="col-md-6 mx-auto bg-light shadow py-2 border rounded">
        <h2 class="text-center">Formularz Rejestracji</h2>
        <form action="#" method="POST" id="rejestracja" class="px-4 pb-4">
            <div class="form-floating mb-3 mt-3">
                <select class="form-select" name="grupa" id="opt-group">

                </select>
                <label for="grupa">Wydział</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="name" placeholder="Imię" required>
                <label for="name">Imię</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="surname" class="form-control" id="surname" placeholder="Nazwisko" required>
                <label for="surname">Nazwisko</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" name="username" class="form-control" id="username" placeholder="Nazwa Użytkownika" required>
                <label for="username">Nazwa Użytkownika</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" name="email" class="form-control" id="email" placeholder="user@mail.com" required>
                <label for="email">Email</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="Hasło" required>
                <label for="password">Hasło</label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success mt-2 w-100">Zarejestruj się</button>
            </div>
        </form>
    </div>
</div>

<script>
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

        // Ładowanie grup do rozwijanej listy
        $(document).ready(function() {

            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/group/read_all.php",
                method: "post",
                success: function(response) {
                    let options = response.data;
                    let content = "";
                    console.log(options);
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

        $('#rejestracja').submit(function(event) {
            event.preventDefault();
            let data = $(this).serializeFormJSON();
            let myJSON = JSON.stringify(data);
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/user/create.php",
                method: "post",
                dataType: "json",
                contentType: "application/json",
                data: myJSON,
                success: function(response) {
                    window.alert("Rejestracja przebiegła pomyślnie!");
                    window.location.replace('index.php');
                }
            });
        });
    });
</script>

<?php include_once 'footer.php'; ?>