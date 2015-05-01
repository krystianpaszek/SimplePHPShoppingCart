<?php
    session_start();
    session_destroy();
    var_dump($_POST);
    echo $_POST['return_url'];
    echo $_POST['product_code'];
    $return_url = base64_decode($_POST['return_url']); //return url
    header("Location: " . $return_url);
//    if(isset($_POST['product_code'])) {
//        $obj = $_POST['product_code'];
//        $products = $_SESSION['temp'];
//        if (is_array($products)) {
//            array_push($products, $obj);
//        } else {
//            $products = array($obj);
//        }
//        $_SESSION['temp'] = $products;
//        header("Location: " . $_SERVER['REQUEST_URI']);
//    }
//    if(isset($_POST["clear"]) && $_POST['clear']==1)
//    {
//        session_destroy();
//        header("Location: " . $return_url);
//    }
?>