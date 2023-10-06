<?php

require_once __DIR__ . "/Model/User.php";
require_once __DIR__ . "/Service/UserManager.php";
require_once __DIR__ . "/Service/Storage.php";
ob_start();
session_start();
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    header('Location: index.php');
}

$result = "ko";
$userManager = new UserManager();
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['pswd'];

$user = $userManager->mappedUser($username, $email, $hashedPassword);
$_SESSION['user_obj'] = $user;

if ($userManager->verificationData($user) === true) {

    $password = password_hash($password, PASSWORD_DEFAULT);
    
    $userManager->insertUser($user);
    $_SESSION['form_msg'] = 'You are registered. Please log in.';
    $result = "ok";
}

header(sprintf('Location: index.php?result=%s', $result));
