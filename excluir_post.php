<?php
    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/PostDAOMysql.php');
    require_once('dao/LikeDAOMysql.php');
    require_once('dao/CommentDAOMysql.php');
    use dao\LikeDAOMysql;
    use dao\CommentDAOMysql;
    use dao\PostDAOMysql;
    use models\Auth;
    

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $postDAO = new PostDAOMysql($conection);
        $commentsDAO = new CommentDAOMysql($conection);
        $LikeDAO = new LikeDAOMysql($conection);
        $commentsDAO->deleteComments($id);
        $LikeDAO->deleteLike($id);
        $type = $postDAO->checkType($id);
        if ($type == 'photo') {
            $body = $postDAO->getPhotoBody($id);
            $postDAO->deletePhoto($body);
        };

        $postDAO->delete($id);
    };
    header('location: ' . $base);
    exit;
?>