<?php
    namespace dao;
    
    require_once 'models/User.php';
    require_once 'RelationDAOMysql.php';
    require_once 'PostDAOMysql.php';
    use dao\PostDAOMysql;
    use models\User;
    use models\UserDAO;
    use dao\RelationDAOMysql;

    class UserDAOMysql implements UserDAO
    {
        private $conection;

        public function __construct(\PDO $db_conection)
        {
            $this->conection = $db_conection;
        }

        private function generateUser($array, $full = false)
        {
            $u = new User;
            $u->id = $array['id'] ?? 0;
            $u->nome = $array['nome'] ?? '';
            $u->email = $array['email'] ?? null;
            $u->id_city = $array['id_city'] ?? null;
            $u->id_work = $array['id_work'] ?? null;
            $u->work = $array['work'] ?? null;
            $u->avatar = $array['avatar'] ?? null;
            $u->birthday = $array['birthday'] ?? '';
            $u->cover = $array['cover'] ?? '';
            $u->token = $array['token'] ?? '';
            $u->passwd = $array['passwd'] ?? '';
            $u->work = $array['work'] ?? '';
            $u->city = $array['cidade'] ?? '';

            if ($full) {
                $relation = new RelationDAOMysql($this->conection);
                $pics = new PostDAOMysql($this->conection);

                $u->followers = $relation->getFollowers($u->id);
                foreach ($u->followers as $key => $value) {
                    $follower = $this->findByID($value);
                    $u->followers[$key] = $follower;
                };

                $u->following = $relation->getFollowing($u->id);
                foreach ($u->following as $key => $value) {
                    $following = $this->findByID($value);
                    $u->following[$key] = $following;
                };
                $u->photos = $pics->getPhotos($u->id);
            };
            return $u;
        }

        public function FindByToken($token)
        {
            if ($token) {
                $sql = "select u.id, u.nome, ifnull(w.nome, '-') as work, u.id_city, u.id_work, u.passwd, u.birthday, u.avatar, u.cover, u.token, ifnull(c.nome, '-') as cidade, e.address as 'email' from users u join emails e on u.id = e.id_user left join citys c on u.id_city = c.id left join works w on w.id = u.id_work where token = :token";
                $select = $this->conection->prepare($sql);
                $select->bindValue(':token', $token);
                $select->execute();
                
                $data = $select->fetch(\PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;    
            };
            return false;
        }

        public function FindByEmail($email)
        {
            if ($email) {
                $sql = "select id_user from emails where address = :email";
                $select = $this->conection->prepare($sql);
                $select->bindValue(':email', $email);
                $select->execute();
                $data = $select->fetch(\PDO::FETCH_ASSOC)['id_user'];
                if ($data) {
                    $sql_user = "select u.id, u.nome, ifnull(w.nome, '-') as work, u.id_city, u.id_work, u.passwd, u.birthday, u.avatar, u.cover, u.token, ifnull(c.nome, '-') as cidade, e.address as 'email' from users u join emails e on u.id = e.id_user left join citys c on u.id_city = c.id left join works w on w.id = u.id_work where u.id = :id";
                    $checked_user = $this->conection->prepare($sql_user);
                    $checked_user->bindValue(':id', $data);
                    $checked_user->execute();
                    $user = $this->generateUser($checked_user->fetch(\PDO::FETCH_ASSOC));
                    return $user;  
                };
            };
            return false;
        }

        public function updateUser(User $u)
        {
            $sql = "update users set nome = :nome, passwd = :passwd, birthday = :birthday, id_city = :id_city, id_work = :id_work, avatar = :avatar, cover = :cover, token = :token where id = :id";
            $update = $this->conection->prepare($sql);
            $update->bindValue(':passwd', $u->passwd);
            $update->bindValue(':nome', $u->nome);
            $update->bindValue(':birthday', $u->birthday);
            $update->bindValue(':id_city', $u->id_city);
            $update->bindValue(':id_work', $u->id_work);
            $update->bindValue(':avatar', $u->avatar);
            $update->bindValue(':cover', $u->cover);
            $update->bindValue(':token', $u->token);
            $update->bindValue(':id', $u->id);
            $update->execute();
        }

        public function updateEmail($id, $email)
        {
            $sql = "update emails set address = :email where id_user = :id";
            $update = $this->conection->prepare($sql);
            $update->bindParam(':id', $id);
            $update->bindParam(':email', $email);
            $update->execute();
        }

        public function addEmail($email)
        {
            $id = $this->getLastID();
            $sql = "insert into emails(address, id_user) values(:address, :id_user)";
            $add = $this->conection->prepare($sql);
            $add->bindValue(':address', $email);
            $add->bindValue(':id_user', $id);
            $add->execute();
        }
        
        public function addUser(User $u)
        {
            $sql = "insert into users(nome, passwd, birthday, token) values(:nome, :passwd, :birthday, :token)";
            $add = $this->conection->prepare($sql);
            $add->bindValue(':nome', $u->nome);
            $add->bindValue(':passwd', $u->passwd);
            $add->bindValue(':birthday', $u->birthday);
            $add->bindValue(':token', $u->token);
            $add->execute();
            $this->addEmail($u->email);
        }

        private function getLastID()
        {
            $sql = "select max(id) as id from users";
            $select = $this->conection->prepare($sql);
            $select->execute();
            $id = $select->fetch(\PDO::FETCH_ASSOC)['id'];
            return $id;
        }

        public function findByID($id, $full = false)
        {
            if ($id) {
                $sql_user = "select u.id, u.nome, ifnull(w.nome, '-') as work, u.id_city, u.id_work, u.passwd, u.birthday, u.avatar, u.cover, u.token, ifnull(c.nome, '-') as cidade, e.address as 'email' from users u join emails e on u.id = e.id_user left join citys c on u.id_city = c.id left join works w on w.id = u.id_work where u.id = :id";
                $checked_user = $this->conection->prepare($sql_user);
                $checked_user->bindValue(':id', $id);
                $checked_user->execute();
                $user = $this->generateUser($checked_user->fetch(\PDO::FETCH_ASSOC), $full);
                return $user;
            };
            return false;
        }

        public function findByName($val)
        {
            if ($val) {
                $array = array();
                $sql = "select * from users where nome like :val";
                $select = $this->conection->prepare($sql);
                $select->bindValue(':val', '%'. $val .'%');
                $select->execute();
                $result = $select->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($result as $key => $value) {
                    $array[] = $this->generateUser($value);
                };
                return $array;
            } else {
               return false;
            };
        }
    };

?>