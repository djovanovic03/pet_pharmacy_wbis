<page_admin>
    <?php if (($_SESSION['login_status'] ?? 0) && isset($_SESSION['user']) && (int)($_SESSION['user']['is_admin'] ?? 0) == 1): ?>
    <div class="search-and-filter">
            <div class="forms-holder">
                <form method="POST">
                    <label for="filter">Filter po kategoriji: </label>
                    <select name="filter">
                        <option value="0">— sve —</option>
                        <option value="Psi">Psi</option>
                        <option value="Macke">Macke</option>
                    </select>
                    <label for="search">Naziv proizvoda: </label>
                    <input type="text" name="search" placeholder="Unesite naziv…">
                    <button>Primeni</button>
                    <a href="./index.php?module=admin&action=add">+ Dodaj proizvod</a>
                    <a href="./index.php?module=admin&action=charts">📈 Statistika</a>
                </form>
            </div>

            <div class="products-admin">
                <?php foreach (($_page_view['_data'] ?? []) as $data): ?>
                    <div class="product-card">
                        <img src="<?= $data['thumbnail'] ?? '' ?>" alt="thumb">
                        <h3><?= $data['product_name'] ?></h3>
                        <ul>
                            <li>Kategorija: <?= $data['category'] ?></li>
                            <li>Cena: <?= $data['price'] ?></li>
                            <li>Količina: <?= $data['quantity'] ?></li>
                        </ul>
                        <a href="./index.php?module=admin&action=edit&id=<?= (int)$data['id']?>">Izmeni</a>
                        <a href="./index.php?module=admin&action=delete&id=<?= (int)$data['id']?>">Obriši</a>
                    </div>
                <?php endforeach; ?>
            </div>
    </div>
    <?php else: ?>
        <?=$_error[] = "Nemate pristup."?>
    <?php endif; ?>
</page_admin>
