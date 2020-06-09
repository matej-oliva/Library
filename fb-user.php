<?php

session_start();

require_once 'include/user.php';
require_once __DIR__ . '/facebook/vendor/autoload.php';

if (!empty($_SESSION['user_id'])) {
    $userQuery = $db->prepare('SELECT user_id FROM library_users WHERE user_id=:id LIMIT 1;');
    $userQuery->execute([
        ':id' => $_SESSION['user_id']
    ]);
    if ($userQuery->rowCount() != 1) {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        header('Location: index.php');
        exit();
    }
}
?>
