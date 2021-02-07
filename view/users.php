<?php include_once 'header.php'; ?>
<?php if(!isset($_SESSION['uid'])){
    echo '<h2 class="text-center">NIE JESTEŚ ZALOGOWANY!</h2>';
    exit();
}
?>

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
        function show(){
            let i = 0;
        $.ajax({
            url: "http://localhost/TSW_160802_PROJ/api/user/read_all.php",
            method: "GET",
            dataType: "JSON",
            //contentType : "application/json",
            //data        : {}
            success: function(response) {
                $('#inside').html("");
                let userArray = response.data;
                let content = '<table class="table text-center table-bordered table-striped table-dark mt-2">'+
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
                    content +=
                        '<tr class="align-middle"s>' +
                        '<th scope="row">' + ++i + '</th>' +
                        '<td>' + row.grupa + '</td>' +
                        '<td>' + row.name + '</td>' +
                        '<td>' + row.surname + '</td>' +
                        '<td>' + row.username + '</td>' +
                        '<td>' + row.email + '</td>' +
                        '<td>' +
                        '<button type="button" name="edit" class="btn me-2 btn-primary btn-xs edit" id="' + row.id + '">Edytuj' +
                        '</button>' +
                        '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="' + row.id + '">Usuń' +
                        '</button></td>' +
                        '</tr>';
                });
                content += '</tbody></table>' +
                '<div class="col-lg-12">'+
                '<button type="button" name="create" class="btn btn-success btn-xs w-100 create" id="dodaj">Dodaj</button>'+
                '</div>';
                $('#inside').append(content);
            },
            error: function(xhr, status, err) {
                $('#inside').html(err);
            }
        });
    }
    
    show();

  //  var removeButton = $("<input type=\"button\" class=\"remove\" value=\"-\" />");

    $(document).on('click', '.delete',  function(){
        var ide = $(this).attr("id");
        let usrid = {id: ide};
        usrid = JSON.stringify(usrid);
        if(ide != 1){
        $.ajax({
            url: "http://localhost/TSW_160802_PROJ/api/user/delete.php",
            method: "DELETE",
            dataType: "JSON",
            contentType : "application/json",
            data        : usrid,
            success: function(response) {
                $('#inside').html("");
                let userArray = response.data;
                show();
            },
            error: function(xhr, status, err) {
                $('#inside').append(err);
            }
        });
    } else{
        window.alert('PRÓBUJESZ USUNĄĆ ADMINISTRATORA!');
    }
    });
    
    });
</script>