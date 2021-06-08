<?php

    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/PostDAOMysql.php');
    require_once('dao/RelationDAOMysql.php');
    use dao\PostDAOMysql;
    use dao\UserDAOMysql;
    use dao\RelationDAOMysql;
    use models\Auth;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $activeMenu = 'profile';
    $user = [];
    $feed = [];
    

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$id) {
        $id = $userInfo->id;
    };

    if ($id != $userInfo->id) {
        $activeMenu = '';
    };

    $page = intval(filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT));

    if ($page < 1) {
        $page = 1;
    };

    $post_dao = new PostDAOMysql($conection);
    $user_dao = new UserDAOMysql($conection);

    //Info do user
    $user = $user_dao->findByID($id, true);
    if (!$user) {
        header('location:' . $base);
        exit;
    };
    $nasc = new DateTime($user->birthday);
    $now = new DateTime('today');
    $user->idade = $nasc->diff($now)->y;

    //Pegar feed do user
    $info = $post_dao->getUserFeed($id, $page);
    $feed = $info['feed'];
    $pages = $info['pages'];
    $cp = $info['currentPage'];

    //Verificar se sigo o user
    $followingDAO = new RelationDAOMysql($conection);
    $isFollowing = $followingDAO->isFollowing($userInfo->id, $id);

    /*$post_dao = new PostDAOMysql($conection);
    $feed = $post_dao->getHomeFeed($userInfo->id);*/

    require_once 'partials/header.php';
    require_once 'partials/menu.php';
?>
    <section class="feed">
        <div class="row">
            <div class="box flex-1 border-top-flat">
                <div class="box-body">
                    <div class="profile-cover" style="background-image: url('<?=$base?>media/covers/<?=$user->cover?>');"></div>
                    <div class="profile-info m-20 row">
                        <div class="profile-info-avatar">
                            <img src="<?=$base?>media/avatars/<?=$user->avatar?>" />
                        </div>
                        <div class="profile-info-name">
                            <div class="profile-info-name-text"><?=$user->nome?></div>
                            <?php
                                if ($user->city) {?>
                                    <div class="profile-info-location"><?=$user->city?></div>
                            <?php }; ?>                           
                        </div>
                        <div class="profile-info-data row">
                        <?php 
                             if ($id != $userInfo->id) { ?>
                                <div class="profile-info-item m-width-20">
                                <a href="follow_action.php?id=<?=$id?>" class="button"><?=(!$isFollowing)? 'Seguir': 'Abandonar'?></a>
                            </div>
                        <?php    };
                        ?>                            
                            <div class="profile-info-item m-width-20">
                                <div class="profile-info-item-n"><?=count($user->followers)?></div>
                                <div class="profile-info-item-s">Seguidores</div>
                            </div>
                            <div class="profile-info-item m-width-20">
                                <div class="profile-info-item-n"><?=count($user->following)?></div>
                                <div class="profile-info-item-s">Seguindo</div>
                            </div>
                            <div class="profile-info-item m-width-20">
                                <div class="profile-info-item-n"><?=count($user->photos)?></div>
                                <div class="profile-info-item-s">Fotos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="column side pr-5">        
                <div class="box">
                    <div class="box-body">                
                        <div class="user-info-mini">
                            <img src="<?=$base?>assets/images/calendar.png" />
                            <?=date('d/m/Y', strtotime($user->birthday))?>  (<?=$user->idade?> anos)
                        </div>
                        <?php 
                            if ($user->city) { ?>
                                <div class="user-info-mini">
                                <img src="<?=$base?>assets/images/pin.png" />
                                <?=$user->city?>
                            </div>
                        <?php }; ?>
                        
                        <?php 
                            if ($user->work) { ?>
                                <div class="user-info-mini">
                                <img src="<?=$base?>assets/images/work.png" />
                                <?=$user->work?>
                            </div>
                          <?php }; ?>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header m-10">
                        <div class="box-header-text">
                            Seguindo
                            <span>(<?=count($user->following)?>)</span>
                        </div>
                        <div class="box-header-buttons">
                            <a href="<?=$base?>amigos.php?id=<?=$user->id?>">ver todos</a>
                        </div>
                    </div>
                    <div class="box-body friend-list">  
                        <?php
                            if (count($user->following) > 0) {
                                foreach ($user->following as $key => $value) { ?>
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
                                <?php };
                            };
                        ?>              
                        
                    </div>
                </div>
            </div>
            <div class="column pl-5">
                <div class="box">
                    <div class="box-header m-10">
                        <div class="box-header-text">
                            Fotos
                            <span>(<?=count($user->photos)?>)</span>
                        </div>
                        <div class="box-header-buttons">
                            <a href="<?=$base?>photos.php?id=<?=$user->id?>">ver todos</a>
                        </div>
                    </div>
                    <div class="box-body row m-20">
                        <?php 
                            if (count($user->photos) > 0) {
                               foreach ($user->photos as $key => $photo) { 
                                   if ($key < 4 ) { ?>
                                      <div class="user-photo-item">
                                        <a href="#modal-1" data-modal-open>
                                            <img src="<?=$base?>media/uploads/<?=$photo->body?>" />
                                        </a>
                                        <div id="modal-1" style="display:none">
                                            <img src="<?=$base?>media/uploads/<?=$photo->body?>" />
                                        </div>
                                    </div>
                                   <?php }?>                                    
                            <?php
                                };
                            } else{
                                echo "Este usuário não possui fotos.";
                            };
                        ?>
                    </div>
                </div>
                <?php
                    if ($id == $userInfo->id) {
                        require 'partials/feed_editor.php';
                    };
                ?>
                <?php
                    if (count($feed) > 0) {
                        foreach ($feed as $key => $item) { 
                            require 'partials/feed-item.php'; 
                        };
                    }else{
                        echo 'Não há postagens deste usuário';
                    };
                ?>
                <div class="feed-pagination">
                <?php 
                    for ($i=0; $i < $pages; $i++) { ?>
                        <a class="<?=($i+1 == $cp)? 'active': ''?>" href="<?=$base?>perfil.php?id=<?=$id?>&page=<?=$i+1?>"><?=$i+1?></a>
                <?php    };
                ?>
           </div>
            </div>    
        </div>
    </section>
    <script>
        window.onload = function(){
            var modal = new VanillaModal.default();
        }

    </script>
<?php
    require_once 'partials/footer.php';
?>