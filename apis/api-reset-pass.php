<?php
require_once("globals.php");

//Initial validation of the parameter
if (!isset($_POST['email'])) {
    echo "We need an email to find your user";
    exit();
}



try {
    $db = _db();
} catch (Exception $ex) {
    _res(500, ['info' => 'system under maintainance', 'error' => __LINE__]);
}


try {
    $email = $_POST['email'];


    $q = $db->prepare('SELECT * FROM users WHERE user_email = :email');
    $q->bindValue(':email', $email);
    $q->execute();
    $row = $q->fetch();
    echo 'Number of users found: ' . $q->rowCount();
    $verification_key = $row['forgot_pass_key'];


    if (!isset($row['forgot_pass_key'])) {
        echo 'Verification_Key is not present in Database - Create new user or contact your administrator';
        exit();
    }
    if (strlen($row['forgot_pass_key']) != 32) {
        echo "mmm... suspicious (key is not 32 chars)";
        exit();
    }


    $name =  $row['user_name'];
    $_to_email =  $_POST['email'];
    $_message = "<h1>Hello $name! </h1> <p>Follow the link to reset your password</p><p> <a href='http://localhost/reset-password.php?key=$verification_key'>Click here to set a new password</a></p>";
    //$verification_key = bin2hex(random_bytes(16));

    require_once("../private/send-email.php");

    exit();
} catch (PDOException $ex) {
    echo $ex;
    echo 'No dice! ';
}