<?php
    require_once('config.php');
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Devsbook - Login</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <link rel="icon" href="https://pt.seaicons.com/wp-content/uploads/2016/03/Apps-HTML-5-Metro-icon.png" type="image/png" sizes="16x16">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="<?=$base?>assets/css/login.css" />
    </head>
    <body>
        <header>
            <div class="container">
                <a href="<?=$base?>"><img src="<?=$base?>assets/images/devsbook_logo.png" /></a>
            </div>
        </header>
        <section class="container main">
            <form method="POST" action="<?=$base?>signup_action.php">
            <?php
                if ($_SESSION['flash'] != '') { ?>
                    <p id="flash"><?=$_SESSION['flash'];?></p>
                    <?php 
                            unset($_SESSION['flash']);
                        };
                    ?>
                <input type="text" name="nome" id="nome" class="input" placeholder="Digite seu nome completo">
                <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />
                <input placeholder="Digite sua senha" class="input" type="password" name="password" />
                <input type="date" name="birthday" id="birthday" class="input">
                <input class="button" type="submit" value="Cadastre-se" />
                <a href="<?=$base?>login.php">Já tem conta? Faça seu login</a>
            </form>
        </section>
        <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/ec29234e56.js" crossorigin="anonymous"></script>
        <script>
        setTimeout(() => {
                $('#flash').hide(100);
            }, 1000);
        </script>
    </body>
</html>