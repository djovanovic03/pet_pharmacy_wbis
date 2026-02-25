<?php

global $_db;
session_start();

$_error = [];
$_message = [];
$_action = $_GET['action'] ?? '';
$_page = [];

include('./config.php');
include('./core/database.php');


include('./core/functions.php');
// router
$module = $_GET['module'] ?? 'home';
$module_filename = "./modules/module_{$module}.php";
if(!file_exists($module_filename)) $module_filename = './modules/module_error404.php';

include($module_filename);

mysqli_close($_db);

include('./view/page_header.php');
include('./view/page_body.php');
include($_page['view_filename']);
include('./view/page_footer.php');

?>