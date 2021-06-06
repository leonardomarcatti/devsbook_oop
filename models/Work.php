<?php
    namespace models;
    
    class Work
    {
        public $id, $nome;
    }
    
    interface WorkDAO
    {
        public function insert($nome);
        public function getWork($id);
        public function checkWork($id);
    }

?>