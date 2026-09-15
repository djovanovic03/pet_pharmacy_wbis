<html>
<head>
    <link rel="stylesheet" href="./public/css/style.css">
    <script src="./public/js/utils.js"></script>
    <script src="./public/js/script.js"></script>
    <title>Veterinarska Apoteka</title>
</head>

<body>

<header>

    <a href="./index.php" class="logo">
        <img src="./public/images/vet-logo.png" alt="Veterinarska apoteka">
    </a>

    <nav class="main-nav">
        <a href="./index.php">Početna</a>
        <a href="./index.php?module=products">Proizvodi</a>
        <a href="./index.php?module=contact">Kontakt</a>
    </nav>

    <nav class="user-nav">

        <?php if ($_SESSION['login_status'] ?? false): ?>

            <a href="./index.php?module=cart">Korpa</a>

            <a href="./index.php?module=user">
                <?= htmlspecialchars($_SESSION['user']['first_name'] ?? 'Moj nalog') ?>
            </a>

            <?php if ((int)($_SESSION['user']['is_admin'] ?? 0) === 1): ?>
                <a class="admin-link" href="./index.php?module=admin">
                    Administracija
                </a>
            <?php endif; ?>

            <a href="./index.php?module=login&action=logout">
                Odjava
            </a>

        <?php else: ?>

            <a href="./index.php?module=login">
                Prijava
            </a>

            <a class="register-btn"
               href="./index.php?module=register">
                Registracija
            </a>

        <?php endif; ?>

    </nav>

</header>

<wrapper>
