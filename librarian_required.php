<?php
require 'user_required.php';


if ($loggedUser['role_id'] < 2) {
    die ("Na tuto akci nemáte dostatečné pravomoce!");
}
?>