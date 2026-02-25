<page_cart>
    <?php if ($subtotal != 0): ?>
    <form method="POST">
            <div class="cart">
                <div class="cart-products">
                    <?php foreach($_page_view['_data'] as $product): ?>
                        <div class="cart-product">
                            <img src="<?=$product['img']?>" alt="Cart Product">
                            <div class="copy">
                                <h2><?=$product['product_name']?></h2>
                                <p><?=$product['price']?> RSD</p>
                                <div class="cart-buttons">
                                    <input name="amount-<?=$product['id']?>" class= "cart-product-amount" type="number" min=1 max=100 value="<?= $_SESSION['cart'][$product['id']] ?? 1 ?>">
                                    <button name="remove-cart" value="<?=$product['id']?>" class="cart-remove-product">Ukloni</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="cart-overall">
                    <p>Ukupno: <?= $_page_view['_total_price'] ?> RSD</p>
                    <button name="update-cart" class="update-cart">Ažuriraj korpu</button>
                    <label for="address">Adresa: </label>
                    <input type="text" name="address">
                    <label for="card">Broj kartice: </label>
                    <input type="text" name="card">
                    <button name="order-cart" class="update-cart">Naruči</button>
                </div>

            </div>
    </form>
    <?php endif; ?>
</page_cart>
