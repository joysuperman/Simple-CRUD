<?php
session_name('SESSION');
session_start([
    'cookie_lifetime' => 300,
]);
$error = false;
// session_destroy();

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
$fp = fopen("E:\Web_Page\TDG\Ampps\www\crud\data\user.txt", "r");

if ( $username && $password ) {
    $_SESSION['loggedin'] = false;
    $_SESSION['user'] = false ;
    $_SESSION['role'] = false ;

    while ($data = fgetcsv($fp) ) {
        if ($data[0] == $username && $data[1] == md5($password) )  {
            $_SESSION['loggedin'] = true;
            $_SESSION['user'] = $username;
            $_SESSION['role'] = $data[2];

            header('location: /crud/index.php');
        }

    }
    if (!$_SESSION['loggedin']) {
        $error = true;
    }
}



if (isset($_GET['logout'])) {
    $_SESSION['loggedin'] = false;
    $_SESSION['user'] = false ;
    $_SESSION['role'] = false ;
    session_destroy();
    header('location: /crud/index.php'); 
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>login Form</title>
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <!-- CSS Reset -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <!-- Milligram CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="column column-50 column-offset-25">
                <h1>Login Form </h1>
                <?php 
                    if (  $_SESSION['loggedin'] == false) {

                        if ( true == $error) {
                            echo "<fieldset>Username or Password does not match</fieldset>";
                        }
                        else{}
                        ?>

                        <form method="post">
                          <fieldset>
                            <label for="nameField">Username</label>
                            <input type="text" name="username" placeholder="User Name" id="nameField">

                            <label for="passwordField">Password</label>
                            <input type="password" name="password" placeholder="Password" id="passwordField">

                            <input class="button-primary" type="submit" value="Login">
                          </fieldset>
                        </form>

                    <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>