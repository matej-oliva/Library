<?php
require_once 'include/user.php';
require_once 'include/facebook.php';

$fbHelper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $fbHelper->getAccessToken();
} catch (Exception $e) {
    echo 'Přihlášení pomocí Facebooku selhalo. Chyba: ' . $e->getMessage();
    die();
}

if (!$accessToken) {
    die('Přihlášení pomocí Facebooku se nezdařilo. Zkuste to znovu.');
}

$oAuth2Client = $fb->getOAuth2Client();

$accessTokenMetadata = $oAuth2Client->debugToken($accessToken);

$fbUserId = $accessTokenMetadata->getUserId();

$response = $fb->get('/me?fields=name,email', $accessToken);
$graphUser = $response->getGraphUser();

$fbUserEmail = $graphUser->getEmail();
$fbUserName = $graphUser->getName();

$query = $db->prepare('SELECT * FROM library_users WHERE facebook_id=:facebookId LIMIT 1;');
$query->execute([
    ':facebookId' => $fbUserId
]);

if ($query->rowCount() > 0) {
    $user = $query->fetch(PDO::FETCH_ASSOC);
}else{
    $query = $db->prepare('SELECT * FROM library_users WHERE email=:email LIMIT 1;');
    $query->execute([
        ':email' => $fbUserEmail
    ]);

    if ($query->rowCount() > 0) {
        $user = $query->fetch(PDO::FETCH_ASSOC);

        $updateQuery = $db->prepare('UPDATE library_users SET facebook_id=:facebookId WHERE user_id=:id LIMIT 1;');
        $updateQuery->execute([
            ':facebookId' => $fbUserId,
            ':id' => $user['user_id']
        ]);

    } else {
        $insertQuery = $db->prepare('INSERT INTO library_users (first_name, last_name, email, facebook_id) VALUES (:name, :name, :email, :facebookId);');
        $insertQuery->execute([
            ':name' => $fbUserName,
            ':email' => $fbUserEmail,
            ':facebookId' => $fbUserId
        ]);

        $query = $db->prepare('SELECT * FROM library_users WHERE facebook_id=:facebookId LIMIT 1;');
        $query->execute([
            ':facebookId' => $fbUserId
        ]);
        $user = $query->fetch(PDO::FETCH_ASSOC);
    }
}

if (!empty($user)) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_name'] = $user['last_name'];
}

header('Location: index.php');
