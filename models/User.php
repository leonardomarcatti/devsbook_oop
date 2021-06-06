<?php
    namespace models;
    
    class User
    {
        public $id, $nome, $email, $passwd, $id_city, $id_work, $cover, $avatar, $token, $birthday;
    }
    
    interface UserDAO
    {
        public function findByToken($token);
        public function findByEmail($email);
        public function findByID($id);
        public function updateUser(User $u);
        public function addUser(User $u);
    }


?>