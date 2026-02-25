<?php

if ($_POST) {
	$name = $_POST['ime'];
    $email = $_POST['email'];
    $contact_msg = $_POST['poruka'];

    if ($name == '') $_error[] = 'Unesite ime';
    if ($email == '') $_error[] = 'Unesite email';
    if ($contact_msg == '') $_error[] = 'Unesite poruku';

    if (!$_error) {
        /*$poslato = mail(
            'admin@wbis.com',
            'Poruka sa sajta',
            "Od: {$ime} <{$email}>\n
            Poruka: {$poruka}"
        ); */
        $isSent = true; // SIMULACIJA ISPRAVNO POSLATOG MEJLA
        if ($isSent === false) {
            $_error[] = 'Poruka nije poslata. Probajte ponovo kasnije.';
        } else {
            $_message[] = 'Poruka je poslata.';
        }
    }

    //print_r($_POST);
}

$_page = [
    'title' => 'Kontakt',
    'view_filename' => './view/view_contact.php',
];

?>