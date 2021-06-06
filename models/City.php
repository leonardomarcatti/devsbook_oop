<?php
    namespace models;
    
    class City
    {
        public $id, $nome;
    }
    
    interface CityDAO
    {
        public function insert($nome);
        public function getCity($id);
        public function checkCity($id);
    }

?>