<?php define('MyConst', TRUE); ?>
<?php include_once 'header.php'; ?>

<div class="row m-5 py-5 bg-light shadow rounded">
    <div class="col-md-12">
        <h2 class="text-center">Formularz Rejestracji</h2>
    </div>
    <div class="col-md-6 mx-auto bg-light shadow py-2 border rounded">
        <form action="#" method="POST" id="rejestracja">
            <div class="form-group">
                <label for="grupa">Wydział</label>
                <select class="form-select" name="grupa" id="group">
                    <option>Informatyka</option>
                    <option>Biologia</option>
                    <option>Chemia</option>
                    <option>Matematyka</option>
                    <option>Fizyka</option>
                </select>
            </div>
            <hr class="mb-2">
            <div class="form-group">
                <label for="name">Imię</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Imię" required>
            </div>
            <hr class="mb-2">
            <div class="form-group">
                <label for="surname">Nazwisko</label>
                <input type="text" name="surname" class="form-control" id="surname" placeholder="Nazwisko" required>
            </div>
            <hr class="mb-2">
            <div class="form-group">
                <label for="username">Nazwa Użytkownika</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Nazwa Użytkownika" required>
            </div>
            <hr class="mb-2">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email" placeholder="user@mail.com" required>
            </div>
            <hr class="mb-2">
            <div class="form-group">
                <label for="password">Hasło</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Hasło" required>
            </div>
            <hr class="mb-2">
            <div class="form-group">
                <button type="submit" class="btn btn-primary mt-2 w-100">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
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
        $('#rejestracja').submit(function(event) {
            event.preventDefault();
            var data = $(this).serializeFormJSON();
            var myJSON = JSON.stringify(data);
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/user/create.php",
                method: "post",
                dataType: "json",
                contentType: "application/json",
                data        : myJSON,
                success: function(response) {
                    console.log(response);
                    window.alert("Rejestracja przebiegła pomyślnie!");
                    window.location.replace('index.php');
                }
            });
        })
    });
</script>

<?php include_once 'footer.php'; ?>