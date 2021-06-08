<?php
    namespace dao;

    require_once 'dao/RelationDAOMysql.php';
    require_once 'models/Post.php';
    require_once 'dao/UserDAOMysql.php';
    require_once 'dao/LikeDAOMysql.php';
    require_once 'dao/CommentDAOMysql.php';
    use dao\CommentDAOMysql;
    use models\Post;
    use models\PostDAO;
    use dao\RelationDAOMysql;
    use dao\UserDAOMysql;
    use dao\LikeDAOMysql;

class PostDAOMysql implements PostDAO
    {
        private $conection;

        public function __construct(\PDO $conection)
        {
            $this->conection = $conection;
        }

        public function insert(Post $p)
        {
           $sql = 'insert into posts(post_type, created_at, body, id_user) values(:type, :created, :body, :user)';
           $insert = $this->conection->prepare($sql);
           $insert->bindValue(':type', $p->type);
           $insert->bindValue(':created', $p->created_at);
           $insert->bindValue(':body', $p->body);
           $insert->bindValue(':user', $p->id_user);
           $insert->execute();
        }

        public function checkType($id)
        {
            $sql = "select post_type from posts where id = :id";
            $check = $this->conection->prepare($sql);
            $check->bindValue(':id', $id);
            $check->execute();
            return ($check->fetch(\pdo::FETCH_ASSOC)['post_type']) ? true : false;
        }

        public function getPhotoBody($id)
        {
            $sql = "select body from posts where id = :id";
            $check = $this->conection->prepare($sql);
            $check->bindValue(':id', $id);
            $check->execute();
            return $check->fetch(\pdo::FETCH_ASSOC)['body'];
        }

        public function deletePhoto($body)
        {
           unlink('/var/www/html/programacao/curso/B7Web/php/devsbook_oop/media/uploads/' . $body);
        }

        public function delete($id)
        {
           $sql = 'delete from posts where id = :id';
           $insert = $this->conection->prepare($sql);
           $insert->bindValue(':id', $id);           
           $insert->execute();
        }

        public function getHomeFeed($id_user, $page = 1)
        {
            $myarray = array();
            $perPage = 5;
            $offset = ($page-1)*$perPage;
            $relation = new RelationDAOMysql($this->conection);
            $list = $relation->getFollowing($id_user);
            $list[] = $id_user; 
            $sql = 'select * from posts where id_user in (' . implode(',', $list) .') order by created_at desc limit ' . $offset . ", $perPage";
            $select = $this->conection->prepare($sql);
            $select->execute();
            $data = $select->fetchAll(\PDO::FETCH_ASSOC);
            $myarray['feed'] = $this->postListToObject($data, $id_user);

            $sql = 'select count(*) as c from posts where id_user in (' . implode(',', $list) .')';
            $count = $this->conection->prepare($sql);
            $count->execute();
            $total = $count->fetch()['c'];
            $myarray['pages'] = ceil($total/$perPage);
            $myarray['currentPage'] = $page;
            return $myarray;
        }


        public function getPhotos($id)
        {
            $photos = array();
            $sql = "select * from posts where id_user = :id and post_type = 'photo' order by created_at desc";
            $select = $this->conection->prepare($sql);
            $select->bindParam(':id', $id);
            $select->execute();
            $pics = $select->fetchAll(\PDO::FETCH_ASSOC);
            $photos = $this->postListToObject($pics, $id);
            return $photos;
        }

        public function getUserFeed($id, $page = 1)
        {
            $perPage = 5;
            
            $offset = ($page-1)*$perPage;
            $myarray = array();
            $sql = "select * from posts where id_user = :id order by created_at desc limit $offset, $perPage";
            $select = $this->conection->prepare($sql);
            $select->bindValue(':id', $id);
            $select->execute();
            $data = $select->fetchAll(\PDO::FETCH_ASSOC);
            $myarray['feed'] = $this->postListToObject($data, $id);

            $sql = 'select count(*) as c from posts where id_user = :id';
            $count = $this->conection->prepare($sql);
            $count->bindValue(':id', $id);
            $count->execute();
            $total = $count->fetch()['c'];
            $myarray['pages'] = ceil($total/$perPage);
            $myarray['currentPage'] = $page;

            return $myarray;
        }

        private function postListToObject($list, $id)
        {
            $posts = [];
            $userDAO = new UserDAOMysql($this->conection);
            $likeDao = new LikeDAOMysql($this->conection);
            $commentDAO = new CommentDAOMysql($this->conection);
            foreach ($list as $key => $item) {
                $newPost = new Post();
                $newPost->id = $item['id'];
                $newPost->type = $item['post_type'];
                $newPost->created_at = $item['created_at'];
                $newPost->body = $item['body'];
                $newPost->id_user = $item['id_user'];
                $newPost->mine = false;
                if ($item['id_user'] == $id) {
                    $newPost->mine = true;
                };
                //Pegar info sobre user
                $newPost->user = $userDAO->findByID($item['id_user']);

                //Likes
                $newPost->likeCount = $likeDao->getLikeCount($newPost->id);
                $newPost->liked = $likeDao->isLiked($newPost->id, $newPost->id_user);
                //Comments
                $newPost->comments = $commentDAO->getComments($newPost->id);
                $posts[] = $newPost;
            };
            return $posts;
        }
    }
?>