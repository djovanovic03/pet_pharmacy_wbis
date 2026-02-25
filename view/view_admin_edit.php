<?php if (($_SESSION['login_status'] ?? 0) && isset($_SESSION['user']) && (int)($_SESSION['user']['is_admin'] ?? 0) == 1): ?>
    <?php $c = $_page_view['current'] ?? null; ?>
    <form class="add-edit-form" method="POST">

        <h1>Izmena proizvoda #<?= (int)($c['id'] ?? 0) ?></h1>
        <label>Naziv</label>
        <input type="text" name="product_name" value="<?= $c['product_name'] ?? '' ?>" required>
        <label>Kategorija</label>
        <input type="text" name="category" value="<?= $c['category'] ?? '' ?>" required>
        <label>Opis</label>
        <textarea name="description"><?= $c['description'] ?? '' ?></textarea>
        <label>Cena</label>
        <input type="number" name="price" step="any" value="<?= $c['price'] ?? '' ?>" required>
        <label>Količina</label>
        <input type="number" name="quantity" value="<?= $c['quantity'] ?? '' ?>" required>
        <label>Slika (img)</label>
        <input type="text" name="img" value="<?= $c['img'] ?? '' ?>">
        <label>Thumbnail</label>
        <input type="text" name="thumbnail" value="<?= $c['thumbnail'] ?? '' ?>">

        <button type="submit" name="save">Sačuvaj izmene</button>
        <a href="./index.php?module=admin&action=list">Nazad</a>
    </form>
<?php else: ?>
    <?=$_error[] = "Nemate pristup."?>
<?php endif; ?>
