<?php
require 'librarian_required.php';


if ($loggedUser['role_id'] < 3) {
    die ("Na tuto akci nemáte dostatečné pravomoce!");
}
?>