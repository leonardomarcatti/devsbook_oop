<?php
    namespace dao;

    require_once 'dao/UserDAOMysql.php';
    require_once 'models/Comment.php';
    use models\CommentDAO;
    use models\Comment;

    class CommentDAOMysql implements CommentDAO
    {
        private $conection;

        public function __construct(\PDO $conection)
        {
            $this->conection = $conection;
        }

        public function addComment(Comment $c)
        {
            $sql = "insert into comments(id_user, id_post, body, created_at) values(:id_user, :id_post, :body, :created_at)";
            $insert = $this->conection->prepare($sql);
            $insert->bindValue(':id_user', $c->id_user);
            $insert->bindValue(':id_post', $c->id_post);
            $insert->bindValue(':body', $c->body);
            $insert->bindValue(':created_at', $c->created_at);
            $insert->execute();
        }

        public function deleteComments($id)
        {
            $sql = "delete from comments where id_post = :id";
            $delete = $this->conection->prepare($sql);
            $delete->bindValue(':id', $id);
            $delete->execute();
        }
        
        public function getComments($id_post)
        {
            $array = [];
            $sql = "select * from comments where id_post = :id";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':id', $id_post);
            $select->execute();
            $data = $select->fetchAll(\pdo::FETCH_ASSOC);
            $userDAO = new UserDAOMysql($this->conection);
            foreach ($data as $key => $item) {
                $comment = new Comment();
                $comment->id = $item['id'];
                $comment->id_post = $item['id_post'];
                $comment->id_user = $item['id_user'];
                $comment->body = $item['body'];
                $comment->created_at = $item['created_at'];
                $comment->user = $userDAO->findByID($item['id_user']);
                $array[] = $comment;
            };
            return $array;
        }

        
    }
    



?>