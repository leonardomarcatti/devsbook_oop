<?php
    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/PostDAOMysql.php');
    use models\Auth;
    use dao\PostDAOMysql;
    use models\Post;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $body = filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING);
    if ($body) {
        $postDao = new PostDAOMysql($conection);
        $newPost = new Post();
        $newPost->id_user = $userInfo->id;
        $newPost->type = 'text';
        $newPost->body = $body;
        $newPost->created_at = date('Y-m-d H:i:s');

        $postDao->insert($newPost);
    };

    header('location: ' . $base);
    exit;
?>