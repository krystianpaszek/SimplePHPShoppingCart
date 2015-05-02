<?php
$user = "scott2";
$password = "tiger";
$host = "localhost";
$database = "sklep";
require_once('MDB2.php');
$dsn = "mysql://".$user.":".$password."@".$host."/".$database;
$db = MDB2::connect($dsn);

if (MDB2::isError($db))
    die($db->getMessage());
?>