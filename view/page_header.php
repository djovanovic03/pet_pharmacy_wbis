<html>
<head>
    <link rel="stylesheet" href="./public/css/style.css">
    <script src="./public/js/utils.js"></script>
    <script src="./public/js/script.js"></script>
    <title>Veterinarska Apoteka</title>
</head>
<body>
    <header_above>
        <?php if (($_SESSION['login_status'] ?? 0)): ?>
            <?php if ((int)($_SESSION['user']['is_admin'] ?? 0) === 1): ?>
                <a href="./index.php?module=admin">Administracija</a>
            <?php endif; ?>

            <a href="./index.php?module=login&action=logout">Odjavi se</a>
            <a href="./index.php?module=cart">Moja korpa</a>
            <a href="./index.php?module=user">Moji podaci</a>
    <?php else: ?>
        <a href="./index.php?module=login">Prijavi se</a>
        <a href="./index.php?module=register">Registruj se</a>
    <?php endif; ?>
    </header_above>
    <header>
    </header>
    <?php include('./view/page_nav.php');?>
    <wrapper>



