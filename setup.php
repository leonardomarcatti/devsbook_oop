<?php

    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/UserDAOMysql.php');
    use dao\UserDAOMysql;
    use models\Auth;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $activeMenu = 'config';
    $posts = new UserDAOMysql($conection);

    require_once 'partials/header.php';
    require_once 'partials/menu.php';
?>
    <section class="feed mt-10">
        <h1>Configuações</h1>
        <?php if (isset($_SESSION['flash'])) { ?>
            <p id="flash"><?=$_SESSION['flash'];?></p>
            <?php 
                    unset($_SESSION['flash']);
                };
            ?>
        <form action="setup_action.php" method="post" class="config-form" enctype="multipart/form-data" id="setup_form">
            <label for="avatar">Avatar:</label>
            <input type="file" name="avatar" id="avatar"><br>
            <img src="<?=$base?>media/avatars/<?=$userInfo->avatar?>" alt="" srcset="" class="mini avatar">
           
            <label for="capa">Capa:</label>
            <input type="file" name="capa" id="capa"><br>
            <img src="<?=$base?>media/covers/<?=$userInfo->cover?>" alt="" srcset="" class="mini">
            <hr>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?=$userInfo->nome?>" class="input">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?=$userInfo->email?>" class="input">
            <label for="city">Cidade:</label>
            <input type="text" name="city" id="city" value="<?=$userInfo->city?>" class="input">
            <label for="work">Trabalho:</label>
            <input type="text" name="work" id="work" value="<?=$userInfo->work?>" class="input">
            <label for="date">Data:</label>
            <input type="date" name="date" id="date"  value="<?=$userInfo->birthday?>" class="input">

            <hr>
            <label for="senha">Nova Senha:</label>
            <input type="password" name="senha" id="senha" class="input">
            <label for="senha2">Repita Senha:</label>
            <input type="password" name="senha2" id="senha2" class="input">
            <button type="submit" class="button">Enviar</button>
        </form>
    </section>
    <script>
        document.querySelector('#setup_form').addEventListener('submit', function (e) { 
            let senha = document.querySelector('#senha').value;
            let senha2 = document.querySelector('#senha2').value;
            if (senha != senha2) {
                e.preventDefault();
                alert('As senhas são diferentes!');
                $('#senha').removeClass('input');
                $('#senha').addClass('input_error');
                $('#senha2').removeClass('input');
                $('#senha2').addClass('input_error');
            };
         });

         document.querySelector('#senha').addEventListener('focus', function(e){
            $('#senha').addClass('input');
            $('#senha').removeClass('input_error');
         })
         document.querySelector('#senha2').addEventListener('focus', function(e){
            $('#senha2').addClass('input');
            $('#senha2').removeClass('input_error');
         })
         setTimeout(() => {
            $('#flash').hide(250) 
         }, 1000);
    </script>
<?php
    require_once 'partials/footer.php';
?>
