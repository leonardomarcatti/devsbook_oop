<?php
    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/PostDAOMysql.php');
    use models\Auth;
    use dao\PostDAOMysql;
    use models\Post;

    $auth = new Auth($conection, $base);    
    $userInfo = $auth->checkToken();
    $maxWidth = 800;
    $maxHeight = 800;
    $array = ['error' => ''];
    $newPostDAO = new PostDAOMysql($conection);
    $photo = $_FILES['photo'];

    if ($_FILES['photo']['tmp_name']) {
        $photo = $_FILES['photo'];
        if (in_array($photo['type'], ['image/png', 'image/jpg', 'image/jpeg'])) {
            list($widthOriginal, $heightOriginal) = getimagesize($photo['tmp_name']);
            $ratio = $widthOriginal/$heightOriginal;
            $newWidth = $maxWidth;
            $newHeight = $maxHeight;
            $ratioMax = $maxWidth/$maxHeight;

            if ($ratioMax > $ratio) {
                $newWidth = $newHeight*$ratio;
            } else{
                $newHeight = $newWidth / $ratio;
            };

            $finalImage = imagecreatetruecolor($newWidth, $newHeight);
            switch ($photo['type']) {
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($photo['tmp_name']);
                    break;
                case 'image/jpg':
                    $image = imagecreatefromjpeg($photo['tmp_name']);
                    break;
                default:
                    $image = imagecreatefrompng($photo['tmp_name']);
                    break;
            };

            $photo_name = md5(time() . rand()) . '.jpeg';
            imagecopyresampled($finalImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $widthOriginal, $heightOriginal);
            imagejpeg($finalImage, 'media/uploads/' . $photo_name);
            $newPhoto = new Post();
            $newPhoto->id_user = $userInfo->id;
            $newPhoto->type = 'photo';
            $newPhoto->body = $photo_name;
            $newPhoto->created_at = date('Y-m-d H:i:s');
            $newPostDAO->insert($newPhoto);

        } else {
           $array['error'] = 'Extensão não suportada';
        };
    }else{
        $array['error'] = 'Nenhuma imagem enviada';
    };

    header('Content-Type: application/json');
    echo json_encode($array);
    exit;

?>