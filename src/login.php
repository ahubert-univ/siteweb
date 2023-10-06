<?php

require_once __DIR__ . "/Model/User.php";
require_once __DIR__ . "/Service/UserManager.php";
require_once __DIR__ . "/Service/Storage.php";

if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header('Location: index.php');
}

//$storage = new Storage('db','my_user','root','my_root_password');
$userManager = new UserManager();

$email = $_POST['email'];
$password = $_POST['pswd'];

// Check if the user exists
$user = $userManager->existUsers($email);

if ($user && password_verify($password, $user->getPassword())) {
    ob_start();
    session_start();
    $_SESSION['is_logged'] = true;
    $_SESSION['user_obj'] = $user;

    header('Location: my_page.php');
} else {
    header('Location: index.php?result=ko');
}
