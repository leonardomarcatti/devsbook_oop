<?php

    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/CommentDAOMysql.php');
    use models\Auth;
    use dao\CommentDAOMysql;
    use models\Comment;

    $auth = new Auth($conection, $base);    
    $userInfo = $auth->checkToken();
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $txt = filter_input(INPUT_POST, 'txt', FILTER_SANITIZE_STRING);

    if ($id && $txt) {
        $commentDAO = new CommentDAOMysql($conection);
        $newComment = new Comment();

        $newComment->id_post = $id;
        $newComment->id_user = $userInfo->id;
        $newComment->body = $txt;
        $newComment->created_at = date('Y-m-d H:i:s');
        $commentDAO->addComment($newComment);

        $array = [
            'error' => '',
            'link' => $base . 'perfil.php?id=' . $userInfo->id,
            'avatar' => $base . 'media/avatars/' . $userInfo->avatar,
            'name' =>  $userInfo->nome,
            'body' => $txt
        ];

    };

    header('Content-Type: application/json');
    echo json_encode($array);
    exit;

?>