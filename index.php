<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Sklep "Ku pamięci"</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>

    <?php
    session_start();
    ?>
    <div class="container">
    <div class="product-list container">
        <?php
        require_once('database_config.php');

        $current_url = base64_encode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $sql = "SELECT id,nazwa,pojemnosc,cena,sztuk FROM produkty";
        $result = $db->query($sql);

        ?>

        <h1>PRODUKTY</h1>
        <table class="table table-bordered product-table"><thead>
        <tr><th>ID</th><th>NAZWA</th><th>POJEMNOSC</th><th>CENA</th><th>SZTUK</th><th>KUP</th></tr></thead>
        
        <?php
        while ($row = $result->fetchrow(MDB2_FETCHMODE_ORDERED))
        {
            ?>
            <form method="POST" action="cart.php"><tr>
            <?php
            printf("<td>%d</td><td>%s</td><td>%s</td><td>%7.2f</td><td>%d</td>",$row[0],$row[1],$row[2],$row[3],$row[4]);
            ?>
            <td><select>
            <?php
            for ($i=1; $i<=$row[4]; $i++) {
                echo '<option value="'.$i.'">'.$i.'</option>';
            }
            ?>
            </select></td>
            <td><button type="submit" class="add_to_cart">Add To Cart</button>
            <input type="hidden" name="product_code" value="<?php echo $row[1];?>" />
            <input type="hidden" name="return_url" value="<?php echo $current_url;?>" />
            </td></tr>

            </form>
        <?php } ?>
        </table>
        <?php
        $result->free();
        $sql = "SELECT COUNT(*) FROM produkty";
        $result = $db->queryOne($sql);

        $db->disconnect();
        ?>
    </div>

    <div class="shopping-cart container">
        <h3>Koszyk</h3>
        <?php
        if (isset($_SESSION[@"passed_data"])) {
            echo $_SESSION[@"passed_data"];
        }
        ?>
        <form method="POST" action="cart.php">
            <input type="hidden" name="clear" value="1"/>
            <input type="hidden" name="return_url" value="<?php echo $current_url;?>" />
            <input type="submit" value="Clear basket">
        </form>
    </div>

    </div>
</body>
</html>