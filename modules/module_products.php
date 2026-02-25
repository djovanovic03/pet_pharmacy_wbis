<?php
if(isset($_POST['search']) || isset($_POST['filter']) || isset($_POST['filter2'])){
    if($_POST['search'] != "" || $_POST['filter'] != null || $_POST['filter2'] != null){
        $sql = "SELECT * FROM products ";
        if($_POST['search'] != ""){
            $search = $_POST['search'];
            $sql .= "WHERE `product_name` LIKE '%$search%' ";
            if($_POST['filter'] ?? null){
                $sql .= "AND ";
            }
        }
        if($_POST['filter'] ?? null){
            $filter = $_POST['filter'];
            if($_POST['search'] == ""){
                $sql .= "WHERE ";
            }
            $sql .= "`category` = '$filter'";
        }
        if (($_POST['filter2'] ?? '') !== '') {
            $filter2 = $_POST['filter2'];
            if ($filter2 === "asc") {
                $sql .= "ORDER BY `price` ASC";
            } elseif ($filter2 === "desc") {
                $sql .= "ORDER BY `price` DESC";
            }
        }
        $result = mysqli_query($_db, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $_page_view['_data'][] = $row;
        }
    }
}
else{
    $sql = "SELECT * FROM products";
    $result = mysqli_query($_db, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
        $_page_view['_data'][] = $row;
    }
}

$_page = [
    'title'         => 'Proizvodi',
    'view_filename' => './view/view_products.php',
];

?>