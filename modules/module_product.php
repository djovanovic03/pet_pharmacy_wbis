<?php
global $_db;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product-id'])) {
    $product_id = (int) $_POST['product-id'];

    if (!($_SESSION['login_status'] ?? 0)) {
        $_error[] = "Morate biti ulogovani da biste dodali proizvod u korpu.";
    }
    else {
        $_SESSION['cart'] = $_SESSION['cart'] ?? [];
            if (!isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id] = 1;   // prvi put - količina = 1
            } else {
                $_SESSION['cart'][$product_id]++;     // sledeći put - +1
            }
        $_message[] = "Proizvod je dodat u korpu.";
    }
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $_error[] = "Neispravan ID proizvoda.";
} else {
    //prepare koristimo kao sigurniju opciju; $res je rezultat; $row je prvi red;
    if ($stmt = $_db->prepare("SELECT id, product_name, category, description, price, quantity, created_at, img, thumbnail
                               FROM products
                               WHERE id = ? LIMIT 1")) {
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if ($row) {
            $_page_view['_data'] = [$row];
        } else {
            $_error[] = "Proizvod nije pronađen.";
        }
    } else {
        $_error[] = "Greška u pripremi upita.";
    }
}

$_page = [
    'title'         => 'Proizvod',
    'view_filename' => './view/view_product.php',
];
