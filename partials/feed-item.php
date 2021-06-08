<?php
    require_once 'feed_script.php';
?>
<div class="box feed-item" data-id="<?=$item->id?>">
    <div class="box-body">
        <div class="feed-item-head row mt-20 m-width-20">
            <div class="feed-item-head-photo">
                <a href="<?=$base?>perfil.php?id=<?=$item->user->id?>"><img src="<?=$base?>media/avatars/<?=$item->user->avatar?>"/></a>
            </div>
            <div class="feed-item-head-info">
                <a href="<?=$base?>perfil.php?id=<?=$item->user->id?>"><span class="fidi-name"><?=$item->user->nome?></span></a>
                <span class="fidi-action">
                    <?php
                        if ($item->type == 'text') {
                            echo 'fez um post';
                        } else {
                            echo 'postou uma foto';
                        };                        
                    ?>
                </span>
                <br/>
                <span class="fidi-date"><?=date('d/m/Y', strtotime($item->created_at))?></span>
            </div>
            <?php
                if ($item->mine) { ?>
                    <div class="feed-item-head-btn">
                        <img src="<?=$base?>assets/images/more.png" />
                        <div class="feed-item-more-window">
                            <a href="<?=$base?>excluir_post.php?id=<?=$item->id?>">Excluir Post</a>
                        </div>
                    </div>
                    
            <?php   
                };
            ?>
        </div>
        <div class="feed-item-body mt-10 m-width-20">
        <?php
            if ($item->type == 'text') { ?>
               <?=nl2br($item->body)?>
            <?php } else {?>
                <img src="<?=$base?>media/uploads/<?=$item->body?>" alt="" srcset="">
           <?php }
            
        ?>
            
        </div>
        <div class="feed-item-buttons row mt-20 m-width-20">
            <div class="like-btn <?=($item->liked)? 'on' : ''?>"><?=$item->likeCount?></div>
            <div class="msg-btn"><?=count($item->comments)?></div>
        </div>
        <div class="feed-item-comments">        
            <div class="feed-item-comments-area">
            <?php 
                foreach ($item->comments as $key => $value) {?>
                    <div class="fic-item row m-height-10 m-width-20">
                        <div class="fic-item-photo">
                            <a href="<?=$base?>perfil.php?id=<?=$value->id_user?>"><img src="<?=$base?>media/avatars/<?=$value->user->avatar?>"/></a>
                        </div>
                        <div class="fic-item-info">
                            <a href="<?=$base?>perfil.php?id=<?=$value->id_user?>"><?=$value->user->nome?></a>
                            <?=$value->body?>
                        </div>
                    </div>
            <?php    }
            ?>
            </div>
            <div class="fic-answer row m-height-10 m-width-20">
                <div class="fic-item-photo">
                    <a href="<?=$base?>perfil.php"><img src="<?=$base?>media/avatars/<?=$userInfo->avatar?>" /></a>
                </div>
                <input type="text" class="fic-item-field" placeholder="Escreva um comentário" />
            </div>
        </div>
    </div>
</div>