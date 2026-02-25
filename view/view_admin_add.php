<?php if (($_SESSION['login_status'] ?? 0) && isset($_SESSION['user']) && (int)($_SESSION['user']['is_admin'] ?? 0) == 1): ?>
<form class="add-edit-form" method="POST">
        <h1>Dodaj proizvod</h1>
        <label>Naziv</label>
        <input type="text" name="product_name" required>
        <label>Kategorija</label>
        <input type="text" name="category" required>
        <label>Opis</label>
        <textarea name="description"></textarea>
        <label>Cena</label>
        <input type="number" name="price" step="any" required>
        <label>Količina</label>
        <input type="number" name="quantity" required>
        <label>Slika (img)</label>
        <input type="text" name="img" placeholder="./public/images/product_x.jpg">
        <label>Thumbnail</label>
        <input type="text" name="thumbnail" placeholder="./public/images/product_x.jpg">

        <button type="submit" name="save">Sačuvaj</button>
        <a href="./index.php?module=admin&action=list">Otkaži</a>
    </form>
<?php else: ?>
<?=$_error[] = "Nemate pristup."?>
<?php endif; ?>

