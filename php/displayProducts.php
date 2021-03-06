<?php
    require "$phpPath/dbaccess.php";
    $conn = new mysqli($adr,$usr,$pwd,$db);
    $conn->set_charset("utf8mb4");
    $and = (isset($_GET['productQuery'])&&!empty($_GET['productQuery'])) ? " WHERE `nazwa` LIKE '%".htmlentities(convertSpecialChars($_GET['productQuery']),ENT_QUOTES)."%'"  : "";
    $res = $conn->query("SELECT * FROM `produkty`$and");
    if($res->num_rows==0){
        echo "Nie znaleziono produktu.";
    };
    while($obj = $res->fetch_object()){

        if(isLoggedIn()){
            $id_k = $conn->query("SELECT `id` FROM `klienci` WHERE `login`='".htmlentities($_SESSION['login'],ENT_QUOTES)."' AND `haslo`=PASSWORD('".htmlentities($_SESSION['password'],ENT_QUOTES)."')")->fetch_object()->id;            
            $res1 = $conn->query("SELECT * FROM `koszyk` WHERE `id_p`=$obj->id AND `id_k`=$id_k");
            if($res1->num_rows==1){
                $inBasket = $res1->fetch_object()->ilosc;
            }else{
                $inBasket = 0;
            }
        }else{
            $inBasket = 0;
        }
        $zapas= $conn->query("SELECT $obj->zapas - $inBasket AS `zapas`")->fetch_object()->zapas;


        if($obj->promocja > 0){
            $obj->cena = $obj->cena - $obj->cena*(0.01*$obj->promocja);
            $obj->cena = $obj->cena."zł (-$obj->promocja%)";
        }else{
            $obj->cena = $obj->cena."zł";

        }
        
        echo "
            <div class='product' style='width:25vw; display:inline-block; margin:0 2.5vw'>
                <div class='imageDiv'>
                    <img class='productImage' src='./graphics/$obj->zdjecie.png' style='width:25vw'>
                </div>
                <table class='priceANDstock'>
                    <tr>
                        <td class='nameTd' style='width:12.5vw; text-align:left;'>$obj->nazwa</td>
                        <td class='buttonTd' style='width:12.5vw; text-align:right;'>";
                        if(isLoggedIn()){
                            echo "<input id='quantity$obj->id' type='number' max='$zapas' min='1' value='1' style='width:25%'><button class='addToCartButton' onclick='addToCart($obj->id)'>Dodaj do koszyka</button>";
                        }else{
                            echo "<button class='addToCartButton' disabled>Zaloguj się aby dodać do koszyka</button>";
                        }
                        
                        echo "</td>
                    </tr>
                    <tr>
                        <td class='priceTd' style='width:12.5vw; text-align:left;'>$obj->cena</td>
                        <td class='stockTd' style='width:12.5vw; text-align:right;'>Pozostało $obj->zapas sztuk</td>
                    </tr>
                </table>
            </div>
        ";
    }
    $conn->close();
?>
