<?php

$user_id = $_SESSION['user_id'];
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    die();
}

$stmt = $db->prepare("SELECT * FROM library_users WHERE user_id = ? LIMIT 1");
$stmt->execute(array($_SESSION["user_id"]));

$loggedUser = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loggedUser) {
    session_destroy();
    header('Location: index.php');
    die();
}
?>