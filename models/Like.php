<?php
    namespace models;
    
    class Like
    {
        public $id, $id_post, $id_user, $created_at;
    }
    
    interface LikeDAO
    {
        public function getLikeCount($id_post);
        public function isLiked($id_post, $id_user);
        public function likeToggle($id_post, $id_user);
        public function deleteLike($id_post);
    }


?>