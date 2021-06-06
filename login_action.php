<?php
    require_once('config.php');
    require_once('models/Auth.php');
    use models\Auth;

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    
    if ($email && $password) {
        $auth = new Auth($conection, $base);
        if ($auth->validateLogin($email, $password)) {
            header('location: ' . $base);        
            exit;
        };
    };

    $_SESSION['flash'] = 'Email e/ou senha errados!';
    header('location: ' . $base . 'login.php');
    exit;

?>