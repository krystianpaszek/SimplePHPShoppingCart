<?php
    session_start();
    include_once('database_config.php');
    $return_url = base64_decode($_POST['return_url']); //return url

    if (isset($_POST['product_code'])) {
        $product_code = $_POST['product_code'];
        $sql = 'SELECT nazwa,pojemnosc,cena FROM produkty WHERE id="'.$product_code.'"';
        $result = $db->queryRow($sql);
        if (isset($_POST['quantity'])) {
            $quantity = $_POST['quantity'];
        } else {
            $quantity = -1;
        }

        if (isset($_SESSION['passed_data'])) {
            $product_array = $_SESSION['passed_data'];
        }

        foreach ($product_array as $cart_itm) //loop through session array
        {
             if($cart_itm["code"] == $product_code){ //the item exist in array
                 $product[] = array('name'=>$cart_itm["name"], 'code'=>$cart_itm["code"], 'quantity'=>$quantity, 'price'=>$cart_itm["price"], 'capacity'=>$cart_itm['capacity']);
                 $found = true;
            }else{
                 //item doesn't exist in the list, just retrive old info and prepare array for session var
                $product[] = array('name'=>$cart_itm["name"], 'code'=>$cart_itm["code"], 'quantity'=>$cart_itm["quantity"], 'price'=>$cart_itm["price"], 'capacity'=>$cart_itm['capacity']);
            }
        }

        if($found == false) //we didn't find item in array
        {
            //add new user item in array
            $product[] = array('code' => $product_code, 'name' => $result[0], 'capacity' => $result[1], 'price' => $result[2], 'quantity' => $quantity);
        }

        $_SESSION['passed_data'] = $product;
    }

    if (isset($_POST["clear"])) {
        session_destroy();
    }

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