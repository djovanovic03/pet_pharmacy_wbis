 <form method="POST">
    <label for="filter">Filtriraj po kategoriji: </label>
        <select name="filter">
            <option value=0>Izaberi</option>
            <option value="Psi">Prozivodi za pse</option>
            <option value="Macke">Prozivodi za macke</option>
        </select>
     <label for="filter2">Sortiraj po ceni: </label>
        <select name="filter2">
             <option value="">Izaberi</option>
             <option value="asc">Cena rastuće</option>
             <option value="desc">Cena opadajuće</option>
        </select>
        <label for="search">Pretraga po imenu proizvoda: </label>
        <input type="text" name="search">
        <button>Search</button>
</form>
<div class="products">
    <?php foreach($_page_view['_data'] as $data):?>
        <a href="<?= "./index.php?module=product&id={$data['id']}"; ?>">
         <img src="<?= $data['thumbnail']; ?>" alt="Proizvod">
         <h3><?= $data['product_name']; ?></h3>
        </a>
    <?php endforeach; ?>
</div>
