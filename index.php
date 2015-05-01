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

        require_once('MDB2.php');
        $dsn = "mysql://scott2:tiger@localhost/sklep";
        $db = MDB2::connect($dsn);
        if (MDB2::isError($db))
            die($db->getMessage());

        printf("<h1>PRODUKTY</h1>");
        printf("<table class=\"table table-bordered product-table\"><thead>");
        printf("<tr><th>ID</th><th>NAZWA</th><th>POJEMNOSC</th><th>CENA</th><th>SZTUK</th><th>KUP</th></tr></thead>");;
        $sql = "SELECT id,nazwa,pojemnosc,cena,sztuk FROM produkty";
        $result = $db->query($sql);
        while ($row = $result->fetchrow(MDB2_FETCHMODE_ORDERED))
        {
            echo "<form method=\"post\" action=\"index.php\">";
            printf("<tr><td>%d</td><td>%s</td><td>%s</td><td>%7.2f</td><td>%d</td>",$row[0],$row[1],$row[2],$row[3],$row[4]);
            echo "<td><select>";
            for ($i=1; $i<=$row[4]; $i++) {
                echo '<option value="'.$i.'">'.$i.'</option>';
            }
            echo "</select></td>";
            echo "<td><button class=\"add_to_cart\">Add To Cart</button>";
            echo '<input type="hidden" name="product_code" value="'.$row[1].'" />';
            //echo "<span class=\"glyphicon glyphicon-shopping-cart\"></span>";
            echo "</td></tr>";

            echo "</form>";
        }
        printf("</table>");
        $result->free();
        $sql = "SELECT COUNT(*) FROM produkty";
        $result = $db->queryOne($sql);
        echo "<i>Query returned $result rows</i>";

        $db->disconnect();
        ?>
    </div>

    <div class="shopping-cart container">
        <h3>Koszyk</h3>
        <?php
        if (isset($_SESSION['temp'])) {
            if (is_array($_SESSION['temp'])) {
                $i = 0;
                foreach ($_SESSION['temp'] as $cart_item) {
                    echo "<br>".$cart_item;
                    $i++;
                }
            } else {
                echo "zmienna: " . $_SESSION['temp'];
            }
        }
        ?>
                <form method="POST" action="index.php">
                    <input type="hidden" name="clear" value="1"/>
                    <input type="submit" value="Clear basket">
                </form>
    </div>

        <?php
        if(isset($_POST['product_code'])) {
            $obj = $_POST['product_code'];
            $products = $_SESSION['temp'];
            if (is_array($products)) {
                array_push($products, $obj);
            } else {
                $products = array($obj);
            }
            $_SESSION['temp'] = $products;
            header("Location: " . $_SERVER['REQUEST_URI']);
        }
        if(isset($_POST["clear"]) && $_POST['clear']==1)
                {
                    session_destroy();
                    header("Location: " . $_SERVER['REQUEST_URI']);
                }
        ?>

    </div>
</body>
</html>
