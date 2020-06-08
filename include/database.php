<?php

$db = new PDO('mysql:host=127.0.0.1;dbname=olim02;charset=utf8', 'olim02', 'uquiiw7chou4oa9Eik');

//následující nastavení zařídí, abychom byla při chybě v SQL vyhozena standardní výjimka (exception)
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
