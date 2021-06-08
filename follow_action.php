<?php
    require_once('config.php');
    require_once('models/Auth.php');
    require_once('dao/RelationDAOMysql.php');
    require_once 'dao/UserDAOMysql.php';
    require_once 'models/Relation.php';
    use dao\UserDAOMysql;
    use models\Auth;
    use dao\RelationDAOMysql;
    use models\Relation;

    $auth = new Auth($conection, $base);
    $userInfo = $auth->checkToken();
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        $relationDao = new RelationDAOMysql($conection);
        $userDAO = new UserDAOMysql($conection);

        if ($userDAO->findByID($id)) {
            $relation = new Relation();
            $relation->from = $userInfo->id;
            $relation->to = $id;

            if ($relationDao->isFollowing($userInfo->id, $id)) {
                $relationDao->delete($relation);
            } else{
                $relationDao->insert($relation);
            };
        };
        header('location: ' . $base . 'perfil.php?id=' . $id);
        exit;

    };

    header('location: ' . $base);
    exit;
?>