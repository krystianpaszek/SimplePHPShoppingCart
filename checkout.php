<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sklep "Ku pamięci"</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<?php
    session_start();
    include_once('database_config.php');
    $current_url = base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
//    echo $_SERVER['HTTP_HOST'];

if (isset($_SESSION["passed_data"])) {
    $product_array = $_SESSION["passed_data"];?>


    <h1>Podsumowanie zakupów</h1>
    <?php $sum = 0;?>
    <table class="table">
        <?php foreach ($product_array as $product) {
            echo '<tr class="'.$product["code"].'">';
            printf('<td>%dx</td><td>%s</td><td class="capacity">%s</td><td>%7.2f</td>', $product["quantity"], $product["name"], $product["capacity"], $product["price"]);
            $sum += $product["quantity"]*$product["price"];
            ?>
            <td><form method="POST" action="cart.php">
                    <button type="submit" class="remove_from_cart">X</button>
                    <input type="hidden" name="remove_product_code" value="<?php echo $product["code"];?>" />
                    <input type="hidden" name="return_url" value="<?php echo $current_url;?>" />
                </form></td>
            <?php
            echo "</tr>";
        } ?>
        <tr><td></td><td></td><td></td><td class="sum"><?php printf("%7.2f",$sum);  ?></td><td></td></tr>
    </table>

    <?php
    }

    if (isset($_POST["buy"]) and $_POST["buy"]==1) {
        if (isset($_SESSION["passed_data"])) {
            $product_array = $_SESSION["passed_data"];
        }

        $stock = true;
        foreach ($product_array as $cart_itm) {
            $quantity = $cart_itm["quantity"];
            $code = $cart_itm["code"];
            $sql = 'SELECT sztuk FROM produkty WHERE id="'.$code.'"';
            $result = $db->queryOne($sql);
            if ($result >= $quantity) {
//                echo $cart_itm["name"].": OK (".$result.">=".$quantity.")";
            } else {
//                echo $cart_itm["name"].": X (".$result."<".$quantity.")";
//                echo 'Oops! Somebody bought <span class="sum">'.$cart_itm["name"].'</span> and there is not enough for you (you wanted ';
//                echo '<span class="sum">'.$quantity.'</span> and there is only <span class="sum">'.$result.'</span> left).';
//                echo '<br>Remove this item from your basket and add it again with decreased quantity';
                ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Oops!</strong> Somebody bought <strong><?php echo $cart_itm["name"]?></strong> and there is not enough for you (you wanted
                    <strong><?php echo $quantity?></strong> and there is only <strong><?php echo $result?></strong> left).
                    <br>Remove this item from your basket and add it again with decreased quantity.
                </div>
                <script>
                    $('.<?php echo $code ?>').addClass("red");
                </script>
                <?php
                $stock = false;
            }
            echo "<br>";
        }

        if ($stock == true) {
            session_destroy();
            foreach ($product_array as $cart_itm) {
                $quantity = $cart_itm["quantity"];
                $code = $cart_itm["code"];
                $sql = 'UPDATE produkty SET sztuk = sztuk -'.$quantity.' WHERE id="'.$code.'"';
                $result = $db->queryOne($sql);
            }
            session_start();
            $_SESSION["complete"]=1;
            header("Location: http://" . $_SERVER['HTTP_HOST']."/sklep/index.php");
        }
    }?>

        <table class="table table-checkout"><tr><td class="noborder">
            <a href="index.php"><- Powrót do sklepu</a>
        </td><td class="buy noborder">
            <form method="POST" action="checkout.php">
                <button type="submit" onclick="javascript:return confirm('Do you want to buy this those products for <?php echo $sum ?>?')">Kup</button>
                <input type="hidden" name="buy" value="1"/>
            </form>
        </td>
        </tr></table>
        </div>
</body>
</html>