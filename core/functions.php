<?php
// koristim samo u login ;
function redirect($path) {

    global $_db;

    if ($path == '')
        return;

    header("Location: $path");
    mysqli_close($_db);
    exit;
}

?>