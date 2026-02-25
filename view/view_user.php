<page_user>
    <?php if(isset($_SESSION['login_status'])): ?>
        <div class="user-info">
            <ul>
                <?php foreach ($_page_view['_data'] as $data): ?>
                     <li>Ime: <?= $data['first_name']; ?></li>
                    <li>Prezime: <?= $data['last_name']; ?></li>
                    <li>Korisničko ime: <?= $data['username']; ?></li>
                    <li>Email: <?= $data['email']; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="user-change-info">
            <form method="POST">
                <h2>Promenite lozinku</h2>
                <label for="old-password">Unesite trenutnu lozinku: </label>
                <input type="password" name="old-password" required>
                <label for="new-password">Unesite novu lozinku: </label>
                <input type="password" name="new-password" required>
                <button>Promeni</button>
            </form>
            <form method="POST">
                <h2>Obrišite Vaš nalog.</h2>
                <label for="password">Unesite lozinku:</label>
                <input type="password" name="password" required>
                <label for="confirmat">Štiklirajte dugme:</label>

                <p class="checkbox-message">Siguran sam da želim da obrišem nalog.
                    <input class="checkbox" type="checkbox" name="confirmation" required>
                </p>
                <button type="submit">Obriši nalog</button>
            </form>
        </div>
    <?php endif ?>
</page_user>
