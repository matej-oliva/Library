<?php
require_once 'facebook/vendor/autoload.php';
$fb = new Facebook\Facebook([
    'app_id' => 'your app id',
    'app_secret' => 'your secret code',
    'default_graph_version' => 'v4.0'
]);
