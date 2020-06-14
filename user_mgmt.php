
<?php

require_once 'include/user.php';


$query = $db->prepare( 
    'SELECT library_users.name AS username, library_users.user_id AS userID, library_users.email AS email, library_users.role_id AS role_id
    FROM library_users
    ORDER BY library_users.name ASC');

$query->execute();

$user_list = $query->fetchAll(PDO::FETCH_ASSOC);
if(empty($user_list)){
    echo '<div class="alert alert-info">Nebyly nalezeni žádní uživatelé.</div>';
};

$pageTitle="Správa uživatelů";
include 'include/header.php';
?>      
    <div class="row">
        <h2 class="col-4 mt-2 mx-3">Správa uživatelů</h2>
        <div class="input-group flex-nowrap col-3 py-2 mr-auto mt-1 text-center">
            <div class="input-group-prepend">
                <span class="input-group-text fa fa-search"></span>
            </div>
            <input class="form-control" id="searchBar" type="text" placeholder="Vyhledat uživatele, roli, ID">
        </div>
    </div>
    
    <div class="col w-100 px-0">
    <ul class="list-group mx-3 py-4" id="searchList">
    <?
    foreach($user_list as $user){
        if(htmlspecialchars($user['role_id']) == 1){
            $userRole = 'Uživatel';
        }elseif(htmlspecialchars($user['role_id']) == 2){
            $userRole = 'Knihovník';
        }else{
            $userRole = 'Administrátor';
        }
        echo '<li class="list-group-item col border border-dark my-1 py-1 w-75 bg-secondary text-white mx-auto">';
        echo '  <div class="row">';
        echo '      <div class="col-6 ml-4">';
        echo '          <div class="row">';
        echo '              <h4><div class="badge badge-light">'.htmlspecialchars($user['username']).'</div></h4>';
        echo '              <h6><div class="badge badge-warning ml-3">'.$userRole.'</div></h6>';
        echo '          </div>';
        echo '          <div class="row">';
        echo '              <h6><div>'.htmlspecialchars($user['email']).'</div></h6>';
        echo '          </div>';
        echo '      </div>';

        echo '      <div class="col text-right">';
        echo '          <div>User ID: '.nl2br(htmlspecialchars($user['userID'])).'</div>';
        echo '          <div class="row text-right">';
        echo '              <div class="col text-right mt-2">';
        echo '                  <a href="./user_roles.php?userID='.$user['userID'].'" class="btn btn-info btn-sm">Změnit roli</a>';
        echo '                  <a href="./user_edit.php?userID='.$user['userID'].'" class="btn btn-light btn-sm">Upravit</a>';
        echo '                  <a href="./user_delete.php?userID='.$user['userID'].'" class="btn btn-danger btn-sm">Smazat</a>';
        echo '              </div>';
        echo '          </div>';
        echo '      </div>';
        
        echo '  </div>';
        echo '  </li>';
    }
    ?>
    </ul>
    </div>
    
<?php
include 'include/footer.php';
?>