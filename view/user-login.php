<?php include_once 'header.php'; ?>

<div class="row m-5 py-5">
    <div class="col-md-6 mx-auto bg-light shadow py-2 border rounded">
    <h2 class="text-center">Formularz Logowania</h2>
        <form action="#" method="post" id="logowanie" class="px-4 pb-4">
            <div class="form-floating mb-3 mt-3">
                <input type="text" name="username" class="form-control" id="username" placeholder="Nazwa Użytkownika" required>
                <label for="username">Nazwa użytkownika</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" name="password" class="form-control" id="password" placeholder="Hasło" required>
                <label for="password">Hasło</label>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success mt-2 w-100">Zaloguj się</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Stworzenie obiektu javascript
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
        $('#logowanie').submit(function(event) {
            event.preventDefault();
            var data = $(this).serializeFormJSON();
            var myJSON = JSON.stringify(data);
            //console.log(myJSON);
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/authorization/login.php",
                method: "POST",
                dataType: "json",
                contentType: "application/json; charset=UTF-8",
                data: myJSON,
                success: function(response) {
                    if (response.success == 1) {
                        window.location.replace('index.php');
                    } else window.alert(response.message)
                }
            });
        });
    });
</script>

<?php include_once 'footer.php'; ?>