<?php
    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/PostDAOMysql.php');
    use models\Auth;
    use dao\PostDAOMysql;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id) {
        $postDAO = new PostDAOMysql($conection);
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