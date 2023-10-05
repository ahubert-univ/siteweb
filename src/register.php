<?php

require_once __DIR__ . "/Model/User.php";
require_once __DIR__ . "/Service/UserManager.php";
require_once __DIR__ . "/Service/Storage.php";
ob_start();
session_start();
if($_SERVER['REQUEST_METHOD'] !== "POST"){
    header('Location: index.php');
}


$result = "ko";
$userManager = new UserManager();
$user = $userManager->mappedUser($_POST['username'],$_POST['email'],$_POST['pswd']);
$_SESSION['user_obj'] = $user;
if($userManager->verificationData($user) === true){
    $userManager->insertUser($user);
    $_SESSION['form_msg'] = 'You are register please login';
    $result = "ok";
}

header(sprintf('Location: index.php?result=%s',$result));