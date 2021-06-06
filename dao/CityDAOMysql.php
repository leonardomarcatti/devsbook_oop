<?php
    namespace dao;

    require_once 'models/City.php';
    use models\CityDAO;

    class CityDAOMysql implements CityDAO
    {
        private $conection;

        public function __construct(\PDO $db_conection)
        {
            $this->conection = $db_conection;
        }

        public function insert($val)
        {
            $sql = "insert into citys(nome) values(:val)";
            $insert = $this->conection->prepare($sql);
            $insert->bindParam(':val', $val);
            $insert->execute();
        }

        public function getCity($val)
        {
            $sql = "select * from citys where nome = :val";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':val', $val);
            $select->execute();
            $data = $select->fetch(\PDO::FETCH_ASSOC);
            return $data;
        }

        public function checkCity($val)
        {
            $sql = "select * from citys where nome = :val";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':val', $val);
            $select->execute();

            return ($select->fetch(\PDO::FETCH_ASSOC))? true : false;
        }
    }
    

?>