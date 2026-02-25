<?php

if(isset($_SESSION['login_status'])) {
    $id = $_SESSION['user']['id'];
    $sql = "SELECT * FROM `users` WHERE `id` = $id";
    $result = mysqli_query($_db,$sql);
    $row = mysqli_fetch_assoc($result);
    $_page_view['_data'][] = $row;
    if ((isset($_POST['old-password']) && $_POST['old-password']=='') || (isset($_POST['new-password']) && $_POST['new-password']=='')) {
        $_error[] = "Polje za lozinku ne može biti prazno.";
    }
    if(isset($_POST['old-password']) && isset($_POST['new-password'])){
        $new_password = $_POST['new-password'];
        $old_password = $_POST['old-password'];
        if (!password_verify($old_password, $_page_view['_data'][0]['password'])) {
            $_error[] = "Netačna trenutna lozinka.";
        }
        if (password_verify($new_password, $_page_view['_data'][0]['password'])){
            $_error[] = "Nova lozinka ne može biti ista kao stara.";
        }
        if (strlen($new_password) < 5) {
            $_error[]  = "Lozinka mora da sadrži barem 5 karaktera.";
        }
        if (!$_error) {
            $new_password_encrypted = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE `users` SET `password` = '$new_password_encrypted' WHERE `id` = $id";
            mysqli_query($_db, $sql);
            $_message[] = "Uspešno ste promenili lozinku.";
        }
    }
    if (!isset($_POST['confirmation']) && isset($_POST['password'])){
        $_error[] = "Mora da polje bude štiklirano da nastavite.";
    }
    if(isset($_POST['password']) && isset($_POST['confirmation'])){
        $password = $_POST['password'];
        if ($_POST['password']=='') {
            $_error[] = "Polje za lozinku ne sme biti prazno.";
        }
        else if (!password_verify($password, $_page_view['_data'][0]['password'])){
            $_error[] = "Pogrešna lozinka.";
        }
        if (password_verify($password, $_page_view['_data'][0]['password']) && $_POST['confirmation'] && !$_error) {
            $sql = "DELETE FROM `users` WHERE `id` = $id";
            mysqli_query($_db, $sql);
            unset($_SESSION['login_status']);
            unset($_SESSION['user']);
            mysqli_close($_db);
            redirect("./index.php");
        }
    }
    $_page = [
        'title' => 'Podaci o Korisniku',
        'view_filename' => './view/view_user.php',
    ];
}
else {
    $_error[] = "Morate biti ulogovani da bi ste videli podatke o korisniku!";
    $_page = [
        'title' => 'Podaci o Korisniku',
        'view_filename' => './view/view_user.php',
    ];
}

?>