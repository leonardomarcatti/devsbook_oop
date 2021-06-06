<?php
    namespace models;
    
    class Post
    {
        public $id, $type, $created_at, $body, $id_user;
    }
    
    interface PostDAO
    {
        public function insert(Post $p);
        public function getHomeFeed($id);
        public function getUserFeed($id);
        public function getPhotos($id);
    }


?>