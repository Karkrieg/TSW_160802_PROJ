<?php include_once 'header.php'; ?>

<div class="row">
    <div class="col-lg-12">
        <div id="inside">

        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>


<!--JavaScript -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#getUsers').click(function() {
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/user/read_all.php",
                method: "GET",
                dataType: "JSON",
                //contentType : "application/json",
                //data        : {}
                success: function(response) {
                    $('#inside').html("");
                    let userArray = response.data;
                    let content = '<table class="table table-dark mt-2"><thead><tr>' +
                        '<th scope="col">Id</th>' +
                        '<th scope="col">Imie</th>' +
                        '<th scope="col">Nazwisko</th>' +
                        '<th scope="col">Username</th>' +
                        '<th scope="col" colspan="2">Action</th>' +
                        '</tr></thead>' +
                        '<tbody>';
                    //$('#inside').append();

                    userArray.forEach(function(row, index, array) {
                        content +=
                            '<tr>' +
                            '<th scope="row">' + row.id + '</th>' +
                            '<td>' + row.name + '</td>' +
                            '<td>' + row.surname + '</td>' +
                            '<td>' + row.username + '</td>' +
                            '<td>' +
                            '<button type="button" name="edit" class="btn me-2 btn-primary btn-xs edit" id="' + row.id + '">Edit' +
                            '</button>' +
                            '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' + row.id + '">Delete' +
                            '</button></td>' +
                            '</tr>';
                    });
                    content += '</tbody></table>';
                    $('#inside').append(content);
                }
            });
        });
    });
</script>