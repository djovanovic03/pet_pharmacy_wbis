<?php
global $_db;
global $_action;

$action_admin = $_action ?: 'list';

if (!($_SESSION['login_status'] ?? 0) || !isset($_SESSION['user']) || (int)($_SESSION['user']['is_admin'] ?? 0) !== 1) {
    $_error[] = "Nemate ovlašćenje za pristup administratorskoj strani.";
    $_page = ['title' => 'Administracija', 'view_filename' => './view/view_admin_list.php'];
    return;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

function admin_get_product($db, $id) {
    if ($id <= 0) return null;
    $stmt = $db->prepare("SELECT id, product_name, category, description, price, quantity, img, thumbnail FROM products WHERE id = ? LIMIT 1");
    if (!$stmt) return null;
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    return $row ?: null;
}

/* DELETE */
if ($action_admin === 'delete') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['yes']) && $id > 0) {
            if ($stmt = $_db->prepare("DELETE FROM products WHERE id = ?")) {
                $stmt->bind_param('i', $id);
                $_message[] = $stmt->execute() ? "Proizvod je obrisan." : "Brisanje nije uspelo.";
                $stmt->close();
            } else {
                $_error[] = "Greška u pripremi upita (DELETE).";
            }
        }
        $action_admin = 'list';
    } else {
        $_page = ['title' => 'Potvrda brisanja', 'view_filename' => './view/view_admin_confirm.php'];
        return;
    }
}

/* ADD / EDIT submit */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action_admin === 'add' || ($action_admin === 'edit' && $id > 0))) {
    $name        = trim($_POST['product_name'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $quantity    = trim($_POST['quantity'] ?? '');
    $img         = trim($_POST['img'] ?? '');
    $thumbnail   = trim($_POST['thumbnail'] ?? '');

    if ($name === '')                         $_error[] = "Naziv proizvoda je obavezan.";
    if ($category === '')                     $_error[] = "Kategorija je obavezna.";
    if ($price === '' || !is_numeric($price)) $_error[] = "Cena mora biti broj.";
    if ($quantity === '' || !ctype_digit($quantity)) $_error[] = "Količina mora biti ceo broj.";

    if (!$_error) {
        if ($action_admin === 'add') {
            $sql = "INSERT INTO products (product_name, category, description, price, quantity, img, thumbnail, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            if ($stmt = $_db->prepare($sql)) {
                $p = (float)$price; $q = (int)$quantity;
                $stmt->bind_param('sssdiss', $name, $category, $description, $p, $q, $img, $thumbnail);
                $_message[] = $stmt->execute() ? "Proizvod je dodat (ID: {$stmt->insert_id})." : "Dodavanje nije uspelo.";
                $stmt->close();
            }
        } else {
            $sql = "UPDATE products
                    SET product_name=?, category=?, description=?, price=?, quantity=?, img=?, thumbnail=?
                    WHERE id=?";
            if ($stmt = $_db->prepare($sql)) {
                $p = (float)$price; $q = (int)$quantity;
                $stmt->bind_param('sssdissi', $name, $category, $description, $p, $q, $img, $thumbnail, $id);
                $_message[] = $stmt->execute() ? "Proizvod je izmenjen." : "Izmena nije uspela.";
                $stmt->close();
                $action_admin = 'list';
                //ako zelimo da menja url i radi: header('Location: ./index.php?module=admin&action=list');
            }
        }
    }
}

/* EDIT: učitaj trenutne vrednosti */
if ($action_admin === 'edit' && $id > 0) {
    $current = admin_get_product($_db, $id);
    if (!$current) $_error[] = "Proizvod (ID $id) nije pronađen.";
    else $_page_view['current'] = $current;
}

/* LIST: lista proizvoda */
if ($action_admin === 'list') {
    $sql = "SELECT id, product_name, category, description, price, quantity, thumbnail FROM products";
    $where = [];
    if (!empty($_POST['filter']) && $_POST['filter'] !== '0') {
        $cat = $_db->real_escape_string($_POST['filter']);
        $where[] = "category = '$cat'";
    }
    if (!empty($_POST['search'])) {
        $s = $_db->real_escape_string($_POST['search']);
        $where[] = "product_name LIKE '%$s%'";
    }
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY id DESC";

    $_page_view['_data'] = [];
    if ($res = $_db->query($sql)) {
        while ($row = $res->fetch_assoc()) $_page_view['_data'][] = $row;
        $res->close();
    }
}

/* CHARTS */
if ($action_admin === 'charts') {
    $from   = trim($_POST['from']   ?? '');
    $to     = trim($_POST['to']     ?? '');
    $sort   = strtolower(trim($_POST['sort'] ?? 'desc'));
    $limit  = (int)($_POST['limit'] ?? 15);
    $userId = (int)($_POST['user_id'] ?? 0);

    if ($limit <= 0 || $limit > 100) $limit = 15;
    $dir = ($sort === 'asc') ? 'ASC' : 'DESC';

    $where = [];
    $bindTypes = '';
    $bindVals  = [];

    if ($from !== '') { $where[] = "o.created_at >= ?"; $bindTypes .= 's'; $bindVals[] = $from.' 00:00:00'; }
    if ($to   !== '') { $where[] = "o.created_at <= ?"; $bindTypes .= 's'; $bindVals[] = $to.' 23:59:59'; }
    if ($userId > 0)  { $where[] = "o.user_id = ?";     $bindTypes .= 'i'; $bindVals[] = $userId; }

    $whereSql = $where ? ("WHERE ".implode(' AND ', $where)) : '';

    // Top proizvodi (komadi) u okviru filtera
    $_page_view['top_products'] = [];
    $sqlTop = "
        SELECT p.product_name, SUM(op.quantity) AS sold_qty
        FROM orders_products op
        JOIN products p ON p.id = op.product_id
        JOIN orders   o ON o.id = op.order_id
        $whereSql
        GROUP BY p.id, p.product_name
        ORDER BY sold_qty $dir
        LIMIT ?
    ";
    if ($stmt = $_db->prepare($sqlTop)) {
        $types = $bindTypes.'i'; $vals = $bindVals; $vals[] = $limit;
        $stmt->bind_param($types, ...$vals);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $_page_view['top_products'][] = $r;
        $stmt->close();
    }

    // Najveće porudžbine (vrednost) + korisnik
    $_page_view['big_orders'] = [];
    $sqlOrders = "
        SELECT o.id AS order_id, DATE(o.created_at) AS created, u.username, u.email,
               SUM(op.quantity * p.price) AS total_value
        FROM orders o
        JOIN users u ON u.id = o.user_id
        JOIN orders_products op ON op.order_id = o.id
        JOIN products p ON p.id = op.product_id
        $whereSql
        GROUP BY o.id, created, u.username, u.email
        ORDER BY total_value $dir
        LIMIT ?
    ";
    if ($stmt = $_db->prepare($sqlOrders)) {
        $types = $bindTypes.'i'; $vals = $bindVals; $vals[] = $limit;
        $stmt->bind_param($types, ...$vals);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $_page_view['big_orders'][] = $r;
        $stmt->close();
    }

    // Mesečna prodaja
    $_page_view['monthly_revenue'] = [];
    $sqlMonthly = "
        SELECT DATE_FORMAT(o.created_at, '%Y-%m') AS ym,
               SUM(op.quantity * p.price) AS revenue
        FROM orders o
        JOIN orders_products op ON op.order_id = o.id
        JOIN products p ON p.id = op.product_id
        $whereSql
        GROUP BY ym
        ORDER BY ym ASC
    ";
    if ($stmt = $_db->prepare($sqlMonthly)) {
        if ($bindTypes !== '') $stmt->bind_param($bindTypes, ...$bindVals);
        $stmt->execute();
        $rs = $stmt->get_result();
        while ($r = $rs->fetch_assoc()) $_page_view['monthly_revenue'][] = $r;
        $stmt->close();
    }

    // Udeo kategorija (fiksno)
    $_page_view['category_share'] = [];
    if ($rs = $_db->query("
        SELECT p.category, SUM(op.quantity * p.price) AS revenue
        FROM orders_products op
        JOIN products p ON p.id = op.product_id
        JOIN orders o ON o.id = op.order_id
        GROUP BY p.category
        ORDER BY revenue DESC
    ")) {
        while ($r = $rs->fetch_assoc()) $_page_view['category_share'][] = $r;
        $rs->close();
    }

    // Top kupci (fiksno)
    $_page_view['top_customers'] = [];
    if ($rs = $_db->query("
        SELECT u.username, u.email, SUM(op.quantity * p.price) AS spent
        FROM users u
        JOIN orders o ON u.id = o.user_id
        JOIN orders_products op ON o.id = op.order_id
        JOIN products p ON p.id = op.product_id
        GROUP BY u.id, u.username, u.email
        ORDER BY spent DESC
        LIMIT 5
    ")) {
        while ($r = $rs->fetch_assoc()) $_page_view['top_customers'][] = $r;
        $rs->close();
    }

    $_page_view['filters'] = [
        'from'    => $from,
        'to'      => $to,
        'sort'    => $dir === 'ASC' ? 'asc' : 'desc',
        'limit'   => $limit,
        'user_id' => $userId,
    ];
}

/* View */
$map = [
    'list'   => './view/view_admin_list.php',
    'add'    => './view/view_admin_add.php',
    'edit'   => './view/view_admin_edit.php',
    'charts' => './view/view_admin_charts.php',
    'delete' => './view/view_admin_confirm.php',
];
$_page = [
    'title'         => 'Administracija',
    'view_filename' => $map[$action_admin] ?? './view/view_admin_list.php',
];
