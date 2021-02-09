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

<div class="row">
    <div class="col-lg-12">
    <h1 class="text-center mt-2">Użytkownicy</h1>
        <div id="inside">

        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>

<div id="modal_action" tabindex="1" class="modal fade" data-backdrop="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="POST" id="form_add">
                <div class="modal-header border">
                    <h2 class="modal-title">Dodawanie użytkownika</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="opt-group">Grupa</label>
                        <select class="form-select" name="grupa" id="opt-group">
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
                </div>
                <div class="modal-footer border">
                    <input type="button" class="btn mx-auto btn-danger w-50" data-dismiss="modal" value="Anuluj" />
                    <input type="submit" class="btn mx-auto w-50 btn-success" name="btn-clk" id="btn-clk" value="Dodaj" />
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_reaction" tabindex="1" class="modal fade" data-backdrop="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="POST" id="form_update">
                <div class="modal-header border">
                    <h2 class="modal-title">Edycja użytkownika</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="list1">
                        <div class="form-group">
                            <label for="opt-group2">Grupa</label>
                            <select class="form-select" name="grupa" id="opt-group2">
                            </select>
                        </div>
                        <hr class="mb-2">
                    </div>
                    <div class="form-group">
                        <label for="ename">Imię</label>
                        <input type="text" name="name" class="form-control" id="ename" placeholder="Imię" />
                    </div>
                    <hr class="mb-2">
                    <div class="form-group">
                        <label for="esurname">Nazwisko</label>
                        <input type="text" name="surname" class="form-control" id="esurname" placeholder="Nazwisko" />
                    </div>
                    <hr class="mb-2">
                    <div class="form-group">
                        <label for="eusername">Nazwa Użytkownika</label>
                        <input type="text" name="username" class="form-control" id="eusername" placeholder="Nazwa Użytkownika" />
                    </div>
                    <hr class="mb-2">
                    <div class="form-group">
                        <label for="eemail">Email</label>
                        <input type="email" name="email" class="form-control" id="eemail" placeholder="user@mail.com" />
                    </div>
                    <hr class="mb-2">
                    <div class="form-group">
                        <label for="epassword">Hasło</label>
                        <input type="password" name="password" class="form-control" id="epassword" placeholder="Hasło" />
                    </div>
                </div>
                <div class="modal-footer border">
                    <input type="button" class="btn mx-auto btn-danger w-50" data-dismiss="modal" value="Anuluj" />
                    <input type="submit" class="btn mx-auto w-50 btn-success" name="btn-clk" id="ebtn-clk" value="Edytuj" />
                </div>
            </form>
        </div>
    </div>
</div>


<!--JavaScript -->
<script type="text/javascript">
    let curr_id = 0;
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
        function show() {
            let i = 0;
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/user/read_all.php",
                method: "POST",
                dataType: "JSON",
                //contentType : "application/json",
                //data        : {}
                success: function(response) {
                    $('#inside').html("");
                    let userArray = response.data;
                    let content =
                        '<div class="col-lg-12 mt-3">' +
                        '<button type="button" name="create" class="btn btn-success btn-xs w-100 create" id="dodaj">&plus; Dodaj użytkownika &plus;</button>' +
                        '</div>' +
                        '<table class="table text-center table-bordered table-striped table-dark mt-3">' +
                        '<thead><tr>' +
                        '<th scope="col">N</th>' +
                        '<th scope="col">Grupa</th>' +
                        '<th scope="col">Imie</th>' +
                        '<th scope="col">Nazwisko</th>' +
                        '<th scope="col">Username</th>' +
                        '<th scope="col">Email</th>' +
                        '<th scope="col">Akcja</th>' +
                        '</tr></thead>' +
                        '<tbody>';

                    userArray.forEach(function(row, index, array) {
                        if (row.id != <?php echo $_SESSION['uid']; ?>)
                            content +=
                            '<tr class="align-middle">';
                        else
                            content +=
                            '<tr class="align-middle">';
                        content +=
                            '<th scope="row">' + ++i + '</th>' +
                            '<td>' + row.grupa + '</td>' +
                            '<td>' + row.name + '</td>' +
                            '<td>' + row.surname + '</td>' +
                            '<td>' + row.username + '</td>' +
                            '<td>' + row.email + '</td>' +
                            '<td>';
                        if (<?php echo $_SESSION['uGroup']; ?> == 1) {
                            content += '<button type="button" name="edit" class="btn me-2 btn-primary btn-xs edit" id="' + row.id + '">Edytuj' +
                                '</button>';
                            if (row.id != 1) {
                                content +=
                                    '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' + row.id + '">Usuń' +
                                    '</button>';
                            }
                        }
                        // Aby instruktor mógł zmieniać i usuwać tylko swoje i studenckie konta
                        else if (<?php echo $_SESSION['uGroup']; ?> == 2) {
                            if ((row.grupa != 'Instruktor' && row.grupa != 'Administrator') || row.id == <?php echo $_SESSION['uid']; ?>) {
                                content += '<button type="button" name="edit" class="btn me-2 btn-primary btn-xs edit" id="' + row.id + '">Edytuj' +
                                    '</button>';
                                // Nie można usunąć administratora
                                if (row.id != 1) {
                                    content +=
                                        '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' + row.id + '">Usuń' +
                                        '</button>';
                                }
                            }
                        } else {
                            content += '<button type="button" name="edit" class="btn me-2 btn-primary btn-xs edit" id="' + row.id + '">Edytuj' +
                                '</button>' +
                                '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' + row.id + '">Usuń' +
                                '</button>';
                        }

                        content += '</td></tr>';
                    });
                    content += '</tbody></table>';
                    $('#inside').append(content);
                },
                error: function(xhr, status, err) {
                    console.log(err);
                }
            });
        }

        show();

        //  var removeButton = $("<input type=\"button\" class=\"remove\" value=\"-\" />");

        // DELETE
        $(document).on('click', '.delete', function() {
            let ide = $(this).attr("id");
            let usrid = {
                id: ide
            };
            usrid = JSON.stringify(usrid);
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/user/delete.php",
                method: "DELETE",
                dataType: "JSON",
                contentType: "application/json",
                data: usrid,
                success: function(response) {
                    //$('#inside').html("");
                    let userArray = response.data;
                    show();
                },
                error: function(xhr, status, err) {
                    $('#inside').append(err);
                }
            });
        });

        $(document).on('click', '.create', function() {
            $('#modal_action').modal('show');
            let idc = $(this).attr("id");
            curr_id = idc;
        });

        $(document).on('click', '.edit', function() {
            $('#modal_reaction').modal('show');
            let idi = $(this).attr("id");
            curr_id = idi;
           if(curr_id == 1){
               $('#opt-group2').val('1');
               $('#list1').hide();
           } else{
               $('#list1').show();
           }
            
        });

        // CREATE
        $(document).on('submit', '#form_add', function(event) {
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
                    //window.alert("Poprawnie dodano użytkownika!");
                    window.alert(response.message);
                    $('#modal_action').modal('hide');
                    show();
                },
                error: function(xhr, stat, err) {
                    window.alert(stat);
                }
            });
        });

        // UPDATE
        $(document).on('submit', '#form_update', function(event) {
            event.preventDefault();
            let data = $(this).serializeFormJSON();
            data["id"] = curr_id;
            let myJSON = JSON.stringify(data);
            //console.log(myJSON);
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/user/update.php",
                method: "put",
                dataType: "json",
                contentType: "application/json",
                data: myJSON,
                success: function(response) {
                    //window.alert("Poprawnie edytowano użytkownika!");
                    window.alert(response.message);
                    $('#modal_reaction').modal('hide');
                    show();
                },
                error: function(xhr, stat, err) {
                    console.log(stat);
                    //$('#modal_reaction').modal('hide');
                    //show();
                }
            });
        });

        $(".modal").on("hidden.bs.modal", function() {
            $(this).find('form').trigger("reset");
        });

        // Ładowanie grup do rozwijanej listy
        $(document).ready(function() {
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/group/read_all.php",
                method: "post",
                success: function(response) {
                    let options = response.data;
                    let content = "";
                   // console.log(options);
                    options.forEach(function(row, index, array) {
                        if (row.id > 2 || (<?php echo $_SESSION['uGroup'] ?> == 1 && row.id != 1)) {
                            content += '<option value="' + row.id + '">' + row.name + '</option>';
                        }
                    });
                    $('#opt-group').html(content);
                    $('#opt-group2').html(content);
                },
                error: function(xhr, status, err) {
                    console.log(xhr, status, err);
                }
            });
        });

    });
</script>