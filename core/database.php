<?php
$_db = mysqli_connect(
    $_config['hostname'],
    $_config['username'],
    $_config['password'],
    $_config['dbname']);
if (!$_db) die('Database connection failed');
?>
