<?php
    namespace models;
    
    class Relation
    {
        public $id, $from, $to;
    }
    
    interface RelationDAO
    {
        public function insert(Relation $r);
        public function getFollowing($id);
        public function getFollowers($id);
    }


?>