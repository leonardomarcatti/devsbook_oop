<?php

    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/UserDAOMysql.php');
    require_once('dao/CityDAOMysql.php');
    require_once('dao/WorkDAOMysql.php');
    use dao\UserDAOMysql;
    use dao\CityDAOMysql;
    use dao\WorkDAOMysql;
    use models\Auth;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $userdao = new UserDAOMysql($conection);
    $cityDAO = new CityDAOMysql($conection);
    $workDAO = new WorkDAOMysql($conection);

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $work = filter_input(INPUT_POST, 'work', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);
    $senha2 = filter_input(INPUT_POST, 'senha2', FILTER_SANITIZE_STRING);

    if (isset($_FILES['avatar']) && $_FILES['avatar']['tmp_name'] != '') {
        $newAvatar = $_FILES['avatar'];
        if (in_array($newAvatar['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
            $avatarW = 200;
            $avatarH = 200;

            list($widthO, $heightO) = getimagesize($newAvatar['tmp_name']);
            $ratio = $widthO/$heightO;
            $newW = $avatarW;
            $newH = $newW / $ratio;
            if ($newH < $avatarH) {
                $newH = $avatarH;
                $newW = $newH * $ratio;
            };

            $x = $avatarW - $newW;
            $y = $avatarH - $newH;
            $x = ($x < 0)? $x/2 : $x;
            $y = ($y < 0)? $y/2 : $y;
            
            $finalImage = imagecreatetruecolor($avatarW, $avatarH);

            switch ($newAvatar['type']) {
                case 'image/jpg':
                    $image = imagecreatefromjpeg($newAvatar['tmp_name']);
                break;
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($newAvatar['tmp_name']);
                break;
                default:
                    $image = imagecreatefrompng($newAvatar['tmp_name']);
                break;
            };

            $avatar_name = md5(time() . rand()) . '.jpeg';

            imagecopyresampled($finalImage, $image, $x, $y, 0, 0, $newW, $newH, $widthO, $heightO);
            imagejpeg($finalImage, './media/avatars/' . $avatar_name, 100);
            $userInfo->avatar = $avatar_name;
        };
    };

    if (isset($_FILES['capa']) && $_FILES['capa']['tmp_name'] != '') {
        $newcapa = $_FILES['capa'];
        if (in_array($newcapa['type'], ['image/jpeg', 'image/jpg', 'image/png'])) {
            $capaW = 850;
            $capaH = 313;

            list($widthO, $heightO) = getimagesize($newcapa['tmp_name']);
            $ratio = $widthO/$heightO;
            $newW = $capaW;
            $newH = $newW / $ratio;
            if ($newH < $capaH) {
                $newH = $capaH;
                $newW = $newH * $ratio;
            };

            $x = $capaW - $newW;
            $y = $capaH - $newH;
            $x = ($x < 0)? $x/2 : $x;
            $y = ($y < 0)? $y/2 : $y;
            
            $finalImage = imagecreatetruecolor($capaW, $capaH);

            switch ($newcapa['type']) {
                case 'image/jpg':
                    $image = imagecreatefromjpeg($newcapa['tmp_name']);
                break;
                case 'image/jpeg':
                    $image = imagecreatefromjpeg($newcapa['tmp_name']);
                break;
                default:
                    $image = imagecreatefrompng($newcapa['tmp_name']);
                break;
            };

            $capa_name = md5(time() . rand()) . '.jpeg';

            imagecopyresampled($finalImage, $image, $x, $y, 0, 0, $newW, $newH, $widthO, $heightO);
            imagejpeg($finalImage, './media/covers/' . $capa_name, 100);
            $userInfo->cover = $capa_name;
        };
    };
    

    if ($nome && $email) {
        $userInfo->birthday = $date;
        $userInfo->nome = $nome;
        if ($userInfo->email != $email) {
            if ($userdao->FindByEmail($email)) {
                $_SESSION['flash'] = 'Este email já existe.';
                header('location:' . $base . 'setup.php');
                exit;
            } else{
                $userInfo->email = $email;
                $userdao->updateEmail($userInfo->id, $email);
            };
        };
        if ($userInfo->city != $city) {
            if ($cityDAO->checkCity($city)) {
                $id_city = $cityDAO->getCity($city);
                $userInfo->id_city = $id_city['id'];
            } else {
                $cityDAO->insert($city);
                $id_city = $cityDAO->getCity($city);
                $userInfo->id_city = $id_city['id'];
            };
        };
        $userInfo->city = $city;

        if ($userInfo->work != $work) {
            if ($workDAO->checkWork($work)) {
                $id_work = $workDAO->getWork($work);
                $userInfo->id_work = $id_work['id'];
            } else {
                $workDAO->insert($work);
                $id_work = $workDAO->getWork($work);
                $userInfo->id_work = $id_work['id'];
            };

            $userInfo->work = $work;
        };

        if ($senha) {
            if ($senha != $senha2) {
                $_SESSION['flash'] = 'As senhas não batem!';
                header('location:' . $base . 'setup.php');
                exit;
            } else {
                $hash = password_hash($senha, PASSWORD_BCRYPT);
                $userInfo->passwd = $hash;
            };
        };
        $userdao->updateUser($userInfo);
    };

    header('location:' . $base . 'perfil.php?id=' . $userInfo->id);
    exit;

?>