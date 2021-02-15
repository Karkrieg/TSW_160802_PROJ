<?php include_once 'header.php'; ?>
<?php if (!isset($_SESSION['uid'])) {
    echo '<h2 class="text-center bg-danger my-5 py-5">NIE JESTEŚ ZALOGOWANY!</h2>';
    exit();
}
?>

<div class="row">
    <div class="col-lg-12">
        <h1 class="text-center mt-2">Dostępne testy</h1>
        <div id="inside">

        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>

<!--JavaScript -->
<script type="text/javascript">
    let curr_id = 0;

    $(document).ready(function() {
        function show() {
            let i = 0;
            $.ajax({
                url: "http://localhost/TSW_160802_PROJ/api/test-student/return_safe_list.php",
                method: "POST",
                success: function(response) {
                    //$('#inside').html('');
                    $('#inside').html(response);
                },
                error: function(xhr, status, err) {
                    console.log(xhr,status,err);
                }
            });
        }
        
        show();

        $(document).on('click', '.edit', function(e) {
            let $this = $(this);
            e.preventDefault();
            let idi = $this.attr("id");
            curr_id = idi;
            window.location.replace('student-test-completion.php?tid='+curr_id);
            // $.post('student-test-completion.php',{'tid':curr_id}, function(){
            //     window.location = 'student-test-completion.php';
            // });
        });
    });
</script>