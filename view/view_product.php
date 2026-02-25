<?php
$product = $_page_view['_data'][0] ?? null;


if (!$product) {
    return;
}
?>

<page_product>
    <h2><?= $product['product_name'] ?? 'Proizvod' ?></h2>

    <?php if (!empty($product['thumbnail'])): ?>
        <div class="product-thumbnail">
            <img src="<?= $product['thumbnail'] ?>"
                 alt="<?= $product['product_name'] ?? '' ?>"
                 onclick="showFullImage('<?= $product['img'] ?>')">
        </div>
    <?php endif; ?>

    <!-- Popup za punu sliku -->
    <div id="imageModal" class="image-modal" onclick="hideFullImage()">
        <span class="close">&times;</span>
        <img class="modal-content" id="fullImage">
    </div>


    <p><strong>Kategorija:</strong> <?= $product['category'] ?? '-' ?></p>

    <?php if (!empty($product['description'])): ?>
        <p><strong>Opis:</strong> <?= $product['description'] ?? '' ?></p>
    <?php endif; ?>

    <p><strong>Cena:</strong> <?= $product['price'] ?? '-' ?> RSD</p>

    <form method="POST">
        <input type="hidden" name="product-id" value="<?= (int)$product['id'] ?>">
        <button class="product-button">Dodaj u korpu</button>
    </form>

</page_product>
