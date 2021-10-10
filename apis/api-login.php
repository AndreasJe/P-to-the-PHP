<?php

require_once('globals.php');

// Validate
if (!isset($_POST['email'])) {
  _res(400, ['info' => 'email required']);
}
if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  _res(400, ['info' => 'email is invalid']);
}

// Validate the password
if (!isset($_POST['password'])) {
  _res(400, ['info' => 'password required']);
}
if (strlen($_POST['password']) < _PASSWORD_MIN_LEN) {
  _res(400, ['info' => 'password min ' . _PASSWORD_MIN_LEN . ' characters']);
}
if (strlen($_POST['password']) > _PASSWORD_MAX_LEN) {
  _res(400, ['info' => 'password max ' . _PASSWORD_MAX_LEN . ' characters']);
}

try {
  $db = _db();
} catch (Exception $ex) {
  _res(500, ['info' => 'system under maintainance', 'error' => __LINE__]);
}


try {
  $q = $db->prepare('SELECT * FROM users WHERE user_email = :user_email');
  $q->bindValue(':user_email', $_POST['email']);
  $q->execute();
  $row = $q->fetch();
  if (!$row) {
    _res(400, ['info' => 'wrong credentials', 'error' => __LINE__]);
  }

  // Success
  session_start();
  $_SESSION['user_email'] = $row['user_email'];
  $_SESSION['user_password'] = $row['user_password'];
  $_SESSION['user_name'] = $row['user_name'];
  $_SESSION['user_id'] = $row['user_id'];
  _res(200, ['info' => 'success login']);
} catch (Exception $ex) {
  _res(500, ['info' => 'system under maintainance', 'error' => __LINE__]);
}
