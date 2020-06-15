<?php

    require_once 'include/user.php';
    require 'admin_required.php';

    $userID = $_GET['userID'];

    $query = $db->prepare( 
        'SELECT library_users.user_id AS userID, library_users.name AS userName
        FROM library_users
        WHERE user_id = '.$userID.'
        ORDER BY library_users.name ASC');
    
    $query->execute();
    
    $user = $query->fetch();
    if(empty($user)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }else{
        $userID = $user['userID'];
        $userName = $user['userName'];
    };
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #region smazani knihy
        if(empty($errors)){
            
            $delQuery=$db->prepare('DELETE FROM library_users WHERE user_id=?;');
            $delQuery->execute(array(
                $userID
            ));

            header('Location: user_mgmt.php');
            exit();
        }
        #endregion smazani knihy
    };

    $pageTitle="Smazání uživatele";
    include 'include/header.php';
?>    

<div class="row">
    <h2 class="col">Odstranění žánru</h2>
</div>
        
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Opravdu chcete tohoto uživatele odstranit z databáze?</h2>
                <div class="form-group">
                    <input id="delete_name" type="text" disabled class="form-control text-center font-weight-bold" 
                        name="delete_name" value="<?php echo ''.htmlspecialchars($userName).'';?>"/>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <a href="user_mgmt.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-danger col form-control ml-2">Odstranit</button>
                    </div>
                </div>
            </form>
        </div>

<?php
    include 'include/footer.php';
?>