<?php
    namespace models;
    
    class Comment
    {
        public $id, $id_post, $id_user, $created_at;
    }
    
    interface CommentDAO
    {
        public function addComment(Comment $c);
        public function getComments($id_post);
        public function deleteComments($id);
    }

?>