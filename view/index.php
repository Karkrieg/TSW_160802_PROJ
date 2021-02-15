<?php include_once 'header.php'; ?>

        <div class="row">
            <div class="col-lg-12">
                <div id="inside">
                    <h1 class="my-5 text-center">Strona główna</h1>
                    <div class="bg-dark text-light text-center p-5 col-lg-6 mx-auto rounded-circle shadow">
                    <?php if(isset($_SESSION['uid']) && $_SESSION['uGroup'] > 2){?>
                        <a href='available-tests.php' id='test-question-student' class=' btn btn-primary p-4'>Dostępne testy</a>
                        <a href='test-results.php' id='test-question-results' class=' btn btn-warning p-4'>Wyniki</a>
                        <?php } else if (isset($_SESSION['uid']) && $_SESSION['uGroup'] <= 2) { ?>
                            <a href='test-creator.php' id='test-question-student' class=' btn btn-primary p-4'>Stwórz test</a>
                            <a href='test-results.php' id='test-question-results' class=' btn btn-warning p-4'>Wyniki studentów</a>
                        <?php } else { ?>
                            Zaloguj się, aby uzyskać dostęp do testów.
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

<?php include_once 'footer.php'; ?>

<