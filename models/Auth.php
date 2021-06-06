<?php
    namespace models;

    require_once('dao/UserDAOMysql.php');

    use dao\UserDAOMysql;

    class Auth
    {   
        private $conection, $base, $dao;

        public function __construct(\PDO $db_conection, $base)
        {
            $this->conection = $db_conection;
            $this->base = $base;
            $this->dao = new UserDaoMysql($this->conection);
        }

        public function checkToken()
        {
            if ($_SESSION['token'] != null) {
                $token = $_SESSION['token'];
                $user = $this->dao->FindByToken($token);
                if ($user) {
                    return $user;
                };
            };

            header('location: ' . $this->base . 'login.php');
            exit;
        }

        public function validateLogin($email, $password)
        {
            $user = $this->dao->findByEmail($email);
            if ($user) {
                if (password_verify($password, $user->passwd)) {
                    $token = md5(time() . rand());
                    $_SESSION['token'] = $token;
                    $user->token = $token;
                    $this->dao->updateUser($user);
                    return true;
                };
            };
            return false;
        }

        public function checkEmail($email)
        {
            $checked_email = new UserDAOMysql($this->conection);
            return ($checked_email->FindByEmail($email))? true : false;
        }

        public function registerUser($name, $email, $password, $birthday)
        {
            $newUser = new User();
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $token = md5(time() . rand()/rand());
            $newUser->nome = $name;
            $newUser->email = $email;
            $newUser->passwd = $hash;
            $newUser->birthday = $birthday;
            $newUser->token = $token;

            $this->dao->addUser($newUser);
            $_SESSION['token'] = $token;
        }
    };
?>
