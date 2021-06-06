<?php

    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/PostDAOMysql.php');
    use dao\PostDAOMysql;
    use models\Auth;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $activeMenu = 'home';
    $posts = new PostDAOMysql($conection);
    $feed = $posts->getHomeFeed($userInfo->id);
    require_once 'partials/header.php';
    require_once 'partials/menu.php';
?>
    <section class="feed mt-10">
        <div class="row">
            <div class="column pr-5">
                <?php 
                    require_once 'partials/feed_editor.php'; 
                    foreach ($feed as $key => $item) {
                        require 'partials/feed-item.php';
                    };                    
                ?>

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
