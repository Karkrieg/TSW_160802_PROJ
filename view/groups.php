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
        <h1 class="text-center mt-2">Grupy</h1>
        <div id="inside">

        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>

<div id="modal_action" tabindex="-1" aria-labelledby="modal_add_label" aria-hidden="true" class="modal fade" data-backdrop="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="POST" id="form_add">
                <div class="modal-header">
                    <h2 class="modal-title">Dodawanie Grupy</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3 mt-3">
                        <input type="text" name="name" class="form-control" id="grupa" placeholder="Grupa" required>
                        <label for="grupa">Grupa</label>
                    </div>
                </div>
                <div class="modal-footer border">
                    <input type="button" class="btn mx-auto btn-danger w-50" data-bs-dismiss="modal" value="Anuluj" />
                    <input type="submit" class="btn mx-auto w-50 btn-success" name="btn-clk" id="btn-clk" value="Dodaj" />
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal_reaction" tabindex="-1" aria-labelledby="modal_edit_label" class="modal fade" data-backdrop="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="PUT" id="form_update">
                <div class="modal-header">
                    <h2 class="modal-title text-center">Edycja grupy</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-3 mt-3">
                        <input type="text" name="name" class="form-control" id="egrupa" placeholder="Grupa" />
                        <label for="egrupa">Grupa</label>
                    </div>
                </div>
                <div class="modal-footer border">
                    <input type="button" class="btn mx-auto btn-danger w-50" data-bs-dismiss="modal" value="Anuluj" />
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
                url: "http://localhost/TSW_160802_PROJ/api/group/read_all.php",
                method: "POST",
                dataType: "JSON",
                //contentType : "application/json",
                //data        : {}
                success: function(response) {
                    $('#inside').html("");
                    let groupArray = response.data;
                    let content =
                        '<div class="col-lg-12 mt-3">' +
                        '<button type="button" name="create" class="btn btn-success btn-xs w-100 create" id="dodaj">&plus; Dodaj grupę &plus;</button>' +
                        '</div>' +
                        '<table class="table text-center table-bordered table-striped table-dark mt-3">' +
                        '<thead><tr>' +
                        '<th scope="col" span=2>N</th>' +
                        '<th scope="col">Grupa</th>' +
                        '<th scope="col">Akcja</th>' +
                        '</tr></thead>' +
                        '<tbody>';

                    groupArray.forEach(function(row, index, array) {
                        if (row.id != <?php echo $_SESSION['uid']; ?>)
                            content +=
                            '<tr class="align-middle">';
                        else
                            content +=
                            '<tr class="align-middle">';
                        content +=
                            '<th scope="row">' + ++i + '</th>' +
                            '<td>' + row.name + '</td>' +
                            '<td>';
                        if (row.id > 2) {
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

        // DELETE
        $(document).on('click', '.delete', function() {
            let ide = $(this).attr("id");
            let grrid = {
                id: ide
            };
            grrid = JSON.stringify(grrid);
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/group/delete.php",
                method: "DELETE",
                dataType: "JSON",
                contentType: "application/json",
                data: grrid,
                success: function(response) {
                    //$('#inside').html("");
                    let groupArray = response.data;
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
        });

        // CREATE
        $(document).on('submit', '#form_add', function(event) {
            event.preventDefault();
            let data = $(this).serializeFormJSON();
            let myJSON = JSON.stringify(data);
            console.log(myJSON);
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/group/create.php",
                method: "post",
                dataType: "json",
                contentType: "application/json",
                data: myJSON,
                success: function(response) {
                    //window.alert("Poprawnie dodano grupę!");
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
                url: "http://localhost/TSW_160802_PROJ/api/group/update.php",
                method: "PUT",
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
                    console.log(err);
                    //$('#modal_reaction').modal('hide');
                    //show();
                }
            });
        });

        $(".modal").on("hidden.bs.modal", function() {
            $(this).find('form').trigger("reset");
        });

    });
</script>