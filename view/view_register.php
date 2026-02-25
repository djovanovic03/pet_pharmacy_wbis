<page_register>
    <form method="POST">
        <?php if(!isset($_SESSION['login_status'])): ?>
        <label>Ime:</label>
        <input type="text" name="firstname" placeholder="Danilo" required>
        <label>Prezime:</label>
        <input type="text" name="lastname" placeholder="Jovanovic" required>
        <label>Korisničko ime:</label>
        <input type="text" name="username" placeholder="Dan" required>
        <label>Email:</label>
        <input type="email" name="email" placeholder="danilo@gmail.com" required>
        <label>Lozinka:</label>
        <input type="password" name="password" required>
        <button>Registruj se</button>
        Već imate nalog?&nbsp<a href="./index.php?module=login">Prijava.</a>
        <?php endif ?>
    </form>
</page_register>

