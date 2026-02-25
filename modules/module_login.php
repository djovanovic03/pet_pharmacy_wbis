<?php

global $_db;
include('./core/database.php');

if (($_GET['action'] ?? '') == 'logout') {
    unset($_SESSION['login_status']);
    unset($_SESSION['user']);
    redirect("./index.php");
}
if (isset($_SESSION['login_status'])) {
    $_message[] = 'Već ste ulogovani.';
}
else if ($_POST) {
    $username = $_POST['name'];
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $_error[] = "Unesite korisničko ime i lozinku.";
    }
    else {
        $sql = "SELECT * FROM `users` WHERE `username` = '{$username}'";
        $result = mysqli_query($_db, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['login_status'] = 1;
                $_SESSION['user'] = $row;
                redirect("./index.php");
            } else {
                $_error[] = "Korisincko ime ili lozinka je netacna.";
            }
        } else {
            $_error[] = "Korisincko ime ili lozinka je netacna.";
        }
    }
}

$_page = [
        'title' => 'Prijava',
        'view_filename' => './view/view_login.php',
];

?>

