<?php
    namespace dao;

    require_once 'models/Work.php';
    use models\WorkDAO;

    class WorkDAOMysql implements WorkDAO
    {
        private $conection;

        public function __construct(\PDO $db_conection)
        {
            $this->conection = $db_conection;
        }

        public function insert($val)
        {
            $sql = "insert into works(nome) values(:val)";
            $insert = $this->conection->prepare($sql);
            $insert->bindParam(':val', $val);
            $insert->execute();
        }

        public function getWork($val)
        {
            $sql = "select * from works where nome = :val";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':val', $val);
            $select->execute();
            $data = $select->fetch(\PDO::FETCH_ASSOC);
            return $data;
        }

        public function checkWork($val)
        {
            $sql = "select * from works where nome = :val";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':val', $val);
            $select->execute();

            return ($select->fetch(\PDO::FETCH_ASSOC))? true : false;
        }
    }
    

?>