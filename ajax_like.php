<?php

    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/LikeDAOMysql.php');
    use models\Auth;
    use dao\LikeDAOMysql;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    
    if ($id) {
        $likeDAO = new LikeDAOMysql($conection);
        $likeDAO->likeToggle($id, $userInfo->id);
    };

?>