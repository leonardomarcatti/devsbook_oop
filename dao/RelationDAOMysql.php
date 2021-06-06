<?php
    namespace dao;
    
    require_once 'models/Relation.php';
    use models\Relation;
    use models\RelationDAO;

    class RelationDAOMysql implements RelationDAO
        {
            private $conection;

            public function __construct(\PDO $db_conection)
            {
                $this->conection = $db_conection;
            }

            public function insert(Relation $r)
            {
                # code...
            }

            public function getFollowing($id)
            {
                $users = [];
                $sql = "select user_to from relations where user_from = :id";
                $select = $this->conection->prepare($sql);
                $select->bindValue(':id', $id);
                $select->execute();                
                $relations = $select->fetchAll();
                
                foreach ($relations as $key => $value) {
                   $users[] = $value['user_to'];
                };
                return $users;
            }

            public function getFollowers($id)
            {
                $users = [];
                $sql = "select user_from from relations where user_to = :id";
                $select = $this->conection->prepare($sql);
                $select->bindValue(':id', $id);
                $select->execute();                
                $relations = $select->fetchAll();
                
                foreach ($relations as $key => $value) {
                   $users[] = $value['user_from'];
                };
                return $users;
            }

            public function isFollowing($from, $to)
            {
                $sql = "select * from relations where user_from = :from and user_to = :to";
                $select = $this->conection->prepare($sql);
                $select->bindValue(':from', $from);
                $select->bindValue(':to', $to);
                $select->execute();
                return ($select->fetch(\pdo::FETCH_ASSOC))? true : false;
            }
        }