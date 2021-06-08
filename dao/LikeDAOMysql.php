<?php
    namespace dao;

    require_once 'models/Like.php';
    use models\LikeDAO;

    class LikeDAOMysql implements LikeDAO
    {
        private $conection;

        public function __construct(\PDO $conection)
        {
            $this->conection = $conection;
        }

        public function deleteLike($id)
        {
            $sql = "delete from likes where id_post = :id";
            $delete = $this->conection->prepare($sql);
            $delete->bindValue(':id', $id);
            $delete->execute();
        }

        public function getLikeCount($id_post)
        {
            $sql = "select count(*) as 'likes' from likes where id_post = :id_post";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':id_post', $id_post);
            $select->execute();
            $data = $select->fetch(\pdo::FETCH_ASSOC)['likes'];
            return $data;
        }
        public function isLiked($id_post, $id_user)
        {
            $sql = "select * from likes where id_post = :id_post and id_user = :id_user";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':id_post', $id_post);
            $select->bindValue(':id_user', $id_user);
            $select->execute();
            $data = $select->fetch(\pdo::FETCH_ASSOC);
            return ($data)? true : false;
        }
        public function likeToggle($id, $id_user)
        {
            if ($this->isLiked($id, $id_user)) {
                $sql = "delete from likes where id_post = :id_post and id_user = :id_user";
            } else{
                $sql = "insert into likes(id_post, id_user, created_at) values(:id_post, :id_user, now())";                
            };
            $like = $this->conection->prepare($sql);
            $like->bindValue(':id_post', $id);
            $like->bindValue(':id_user', $id_user);
            $like->execute();
        }
    }
    



?>