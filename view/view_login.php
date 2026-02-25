<?php global$_message; ?>
<page_login>
        <form method="POST">
            <?php if(!isset($_SESSION['login_status'])): ?>
                <label>Korisničko ime</label>
                <input type="username" name="name">
                <label>Lozinka</label>
                <input type="password" name="password">
                <br>
                <button>Prijavi se</button>
            <?php endif ?>
        </form>
</page_login>


