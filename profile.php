<?php $phpPath='./php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Łącznik Online - strona Twojego profilu</title>
</head>
<body>
    <div class="main">
        <div class="baner">
        <img src="zsl-logo.png" class="logo">
        <img src="bufet-lacznik-blue.png" class="logo2">
            <h1>Bufet "Łącznik" - Twój profil</h1>
            <h3 id="dateParagraph"></h3>
            <script src="js/date.js"></script>
        </div>
        <div class="main_menu">
            <div class="menu"><a class="napis" href="index.php">Strona główna</a></div>
            <div class="menu"><a href="menu.php">Nasze menu</a></div>
            <div class="menu"><a href="products.php">Nasze produkty</a></div>
            <div class="menu"><a href="basket.php">Koszyk</a></div>
            <?php require "$phpPath/profileOrLogin.php"; 
            if(!isLoggedIn()){
                header('Location: user.php');
            }?>
        </div>
        <br><br><div class="left" style="clear: both;">
        <h3><a href="./php/logoff.php">Wyloguj</a></h3>
        <h2>Witaj <span name="username"><?php
            require './php/dbaccess.php';
            $conn = new mysqli($adr,$usr,$pwd,$db);
            $conn->set_charset("utf8mb4");
            echo $conn->query("SELECT CONCAT(`imie`,' ',`nazwisko`) AS `nazwa` FROM `klienci` WHERE `login`='".$_SESSION['login']."' AND `haslo`=PASSWORD('".$_SESSION['password']."')")->fetch_object()->nazwa;
            $conn->close();
            ?></span> <sub name="login">(<?php echo $_SESSION['login']?>)</sub></h2>
            <h3>Ustawienia:</h3>
            <h4>Zmiana loginu:</h4>
            <form action="php/changeLogin.php" method="POST">
                <p>Podaj stary login: </p><input type="text" name="old_login" required>
                <p>Podaj nowy login: </p><input type="text" name="new_login" required>
                <p>Podaj hasło: </p><input type="password" name="current_password" required>
                <br><span style="color:red;" class="error"><?php displayFromGET('loginChangeError'); ?></span><br>
                <p><input type="submit" class="button" name="change_username" value="Zmień login"></p>
            </form>
            <h4>Zmiana hasła użytkownika:</h4>
            <form action="php/changePass.php" method="POST">
                <p>Podaj stare hasło: </p><input type="password" class="pass" name="current_password" required>
                <p>Podaj nowe hasło: </p><input type="password" class="pass" name="new_password1" required>
                <p>Potwierdź nowe hasło: </p><input type="password" class="pass" name="new_password2" required>
                <br><span style="color:red;" class="error"><?php displayFromGET('passError'); ?></span><br>
                <p><input type="submit" class="button" name="change_password" value="Zmień hasło"></p>
            </form>
        </div>
        <div class="right">
            <h3>Lista dokonanych zamówień/zakupów w naszym przedsiębiorstwie dla konta: <span name="username"></span></h3>
            <?php require "$phpPath/displayOrders.php";?>
        </div>
        <div class="footer">
            <h4>Autorzy:</h4>
            <ul>
                <li>Julian Rybarczyk</li>
                <li>Piotr Gierba</li>
                <li>Jacek Jędra</li>
                <li>Renata Sakhnevych</li>
            </ul>
            <h4>Źródła (dla strony o profilu):</h4>
            <ul>

            </ul>
        </div>
    </div>
    <marquee behavior="scroll" direction="right" class="plywtekst"><p>lacznik-zsl.pl</p></marquee>
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/cancelOrder.js"></script>
    <script src="./js/showCode.js"></script>
</body>
</html>