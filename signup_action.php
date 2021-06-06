<?php
    require_once('config.php');
    require_once('models/Auth.php');
    use models\Auth;

    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);

    if ($email && $password && $name && $birthday) {
        $auth = new Auth($conection, $base);
        if ($auth->checkEmail($email)) {
            $_SESSION['flash'] = 'Email em uso!';
            header('location: ' . $base . 'signup.php');
            exit;
        } else {
            $auth->registerUser($name, $email, $password, $birthday);
            $_SESSION['flash'] = 'Campos não enviados';
            header('location: ' . $base);
            exit;
        };
        
    } else {
        $_SESSION['flash'] = 'Campos não enviados';
        header('location: ' . $base . 'signup.php');
        exit;
    };

    
    