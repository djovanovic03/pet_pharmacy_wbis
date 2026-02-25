<?php
if (isset($_SESSION['login_status'])) {
    $_error[] = 'Izlogujte se ako želite da registrujete drugi nalog.';
}
if ($_POST) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM `users` WHERE `email` = '$email' OR `username` = '$username'";
    $result = mysqli_query($_db,$sql);
    $row = mysqli_fetch_assoc($result);
    if($row != NULL) {
        if($row['username'] == $username){
            $_error[] = "Korisničko ime nije dostupno.";
        }
        if($row['email'] == $email){
            $_error[] = "Ovaj email je već u upotrebi.";
        }
    }

    if (!$_error) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users` (first_name, last_name, username, email, password) VALUES ('$firstname', '$lastname', '$username', '$email', '$hashed_password')";

        mysqli_query($_db, $sql);
        $_message[] = "Uspešno ste kreirali nalog.";
    }
}
$_page = [
    'title' => 'Registracija',
    'view_filename' => './view/view_register.php',
];
?>