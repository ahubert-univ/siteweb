<?php
//require_once __DIR__ .'/config.php';

require_once __DIR__ . "/Model/User.php";
session_start();
$message = null;
$result = $_GET['result'] ?? null;
if (null !== $result) {
    $message = $_SESSION['form_msg'] ?? null;
    unset($_SESSION["form_msg"]);
}
$_SESSION["is_logged"] = false;
$username = "";
$email = "";
$password = "";
if ($result !== "logout") {
    /** @var User $user */
    $user = $_SESSION["user_obj"] ?? null;
    if (null !== $user) {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPassword();
    }
}

unset($_SESSION["user_obj"]);


?>
<!DOCTYPE html>
<html>
<head>
    <title>Slide Navbar</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
<div class="main">
    <input type="checkbox" id="chk" aria-hidden="true">
    <div class="signup">
        <?php if (null !== $message): ?>
            <center>
                <span class="label2"><?php echo $message ?></span>
            </center>
        <?php else: ?>
            <label for="chk" aria-hidden="true">Sign up</label>
        <?php endif; ?>

        <?php if ($result !== 'ok'): ?>
            <form action="register.php" method="POST">

                <input type="text" name="username" placeholder="User name" value="<?php echo $username ?>">
                <input type="email" name="email" placeholder="Email" value="<?php echo $email ?>">
                <input type="password" name="pswd" placeholder="Password" value="<?php echo $password ?>">
                <button>Sign up</button>
            </form>
        <?php endif; ?>
    </div>

    <div class="login">
        <form action="login.php" method="post">
            <label for="chk" aria-hidden="true">Login</label>
            <input type="email" name="email" placeholder="Email" required="">
            <input type="password" name="pswd" placeholder="Password" required="">
            <button>Login</button>
        </form>
    </div>
</div>
</body>
</html>