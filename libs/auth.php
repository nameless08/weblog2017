<?php

function authorize($username, $password)
{
    $user = userGetBy('login', $username);//username
    //var_dump($user);
    if (count($user) != 1) {
        return false;
    }
    
    $user = $user[0];
    
    if (!password_verify($password, $user['password'])) {
        return false;
    }
    
    $_SESSION['user'] = [
        'id' => $user['id'],
        'login' => $user['login'],//username
    ];
    
    return true;
}

function isAuthorized()
{
    return isset($_SESSION['user']);
}


function logout()
{
    unset($_SESSION['user']);
}
