<?php
$products = array();
$subtotal = 0.00;



if (!($_SESSION['login_status'] ?? 0)) {
    $_error[] = "Morate biti ulogovani da biste pristupili korpi.";
    $_page = [
        'title'         => 'Korpa',
        'view_filename' => './view/view_cart.php',
    ];
    return;
}



if (isset($_POST['update-cart']) && isset($_SESSION['cart'])) {
    foreach ($_POST as $k => $v) {
        if (strpos($k, 'amount') !== false && is_numeric($v)) {
            $id = str_replace('amount-', '', $k);
            $amount = (int)$v;
            if (is_numeric($id) && isset($_SESSION['cart'][$id]) && $amount > 0) {
                $_SESSION['cart'][$id] = $amount;
            }
        }
    }
}

if (isset($_POST['remove-cart']) && isset($_SESSION['cart'])){
    unset($_SESSION['cart'][$_POST['remove-cart']]);
}

if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
    $array_to_question_marks = implode(',', array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM products WHERE id IN ($array_to_question_marks)";
    $result = mysqli_query($_db,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $_page_view['_data'][] = $row;
        $subtotal += (float)$row['price'] * (int)$_SESSION['cart'][$row['id']];
    }
    $_page_view['_total_price']= $subtotal;
}

if(isset($_POST['order-cart']) && isset($_SESSION['cart']) && isset($_SESSION['login_status'])){
    if(isset($_POST['address']) && isset($_POST['order-cart']) && $_POST['address'] == ""){
        $_error[]="Popunite polje za adresu.";
        
    }
    if(isset($_POST['card']) && isset($_POST['order-cart']) && $_POST['card'] == ""){
        $_error[]="Popunite polje broj kartice.";
        
    }
    if(!$_error){
        $user_id = $_SESSION['user']['id'];
        $address = $_POST['address'];
        $sql = "INSERT INTO `orders` (`user_id`,`address`) VALUES ('$user_id','$address')";
        mysqli_query($_db,$sql);
        $id = mysqli_insert_id($_db);
        $sql = "INSERT INTO `orders_products` (`order_id`,`product_id`,`quantity`) VALUES ";
        $i=0;
        foreach($_SESSION['cart'] as $product_id => $quantity){
            $sql .= "($id, $product_id, $quantity)";
            $i++;
            if(count($_SESSION['cart']) > 1 && $i < count($_SESSION['cart'])) {
                $sql .= ", ";
            }
        }
        mysqli_query($_db,$sql);
        $_message[] = "Uspešna porudzbina.";
        $_page_view['_data'] = [];
        $_page_view['_total_price'] = 0;
        unset($_SESSION['cart']);
    }
}

if ($subtotal == 0) {
    $_message[] = "Korpa je prazna.";
    $_page_view['_data'] = [];
    $_page_view['_total_price'] = 0;
}

$_page = [
    'title'         => 'Korpa',
    'view_filename' => './view/view_cart.php',
];

?>