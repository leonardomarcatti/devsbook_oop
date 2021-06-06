<?php

    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/UserDAOMysql.php');
    use dao\UserDAOMysql;
    use models\Auth;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $activeMenu = '';
    $userDAO = new UserDAOMysql($conection);
    $search = filter_input(INPUT_GET, 's', FILTER_SANITIZE_STRING);

    if ($search == '') {
        header('location: ./');
        exit;
    };

    $user_list = $userDAO->findByName($search);


    require_once 'partials/header.php';
    require_once 'partials/menu.php';
?>
    <section class="feed mt-10">
        <div class="row">
            <div class="column pr-5">
                <div class="full-friend-list">
                    <?php foreach ($user_list as $key => $value) { ?>
                        <div class="friend-icon">
                            <a href="<?=$base?>perfil.php?id=<?=$value->id?>">
                                <div class="friend-icon-avatar">
                                    <img src="<?=$base?>media/avatars/<?=$value->avatar?>" />
                                </div>
                                <div class="friend-icon-name">
                                    <?=$value->nome?>
                                </div>
                            </a>
                        </div>
                    <?php }; ?>
                </div>

            </div>
            <div class="column side pl-5">
            <div class="box banners">
                <div class="box-header">
                    <div class="box-header-text">Patrocinios</div>
                    <div class="box-header-buttons">
                        
                    </div>
                </div>
                <div class="box-body">
                    <a href=""><img src="https://pplware.sapo.pt/wp-content/uploads/2020/07/php_01.jpg" /></a>
                    <a href=""><img src="https://cms-assets.tutsplus.com/uploads/users/71/courses/854/preview_image/get-started-with-laravel-6-400x277.png" /></a>
                </div>
            </div>
            <div class="box">
                <div class="box-body m-10">
                    Criado com ❤️ por B7Web
                </div>
            </div>
            </div>
        </div>
    </section>
<?php
    require_once 'partials/footer.php';
?>
