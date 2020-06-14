
<?php

require_once 'include/user.php';


$query = $db->prepare( 
    'SELECT library_authors.name AS bookAuthor, library_authors.author_id AS authorID
    FROM library_authors
    ORDER BY library_authors.name ASC');

$query->execute();

$author_list = $query->fetchAll(PDO::FETCH_ASSOC);
if(empty($author_list)){
    echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
};

$pageTitle="Autoři";
include 'include/header.php';
?>      
    <div class="row">
        <h2 class="col-4 mt-2 mx-3">Seznam autorů</h2>
        <div class="input-group flex-nowrap col-3 py-2 mr-auto mt-1 text-center">
            <div class="input-group-prepend">
                <span class="input-group-text fa fa-search"></span>
            </div>
            <input class="form-control" id="searchBar" type="text" placeholder="Vyhledat autora">
        </div>
        <form action="users.php" method="GET" class="col text-right py-2 mr-3">
        <a href="author_new.php" class="btn btn-success px-4 mt-1">Přidat autora</a>
        </form>
    </div>
    
    <div class="col w-100 px-0">
    <ul class="list-group mx-3" id="searchList">
    <?
    foreach($author_list as $author){
        echo '<li class="list-group-item col border border-dark my-1 py-1 w-50 bg-secondary text-white mx-auto">';
        echo '  <div class="row">';
        echo '      <div class="col ml-4">';
        echo '          <div class="row">';
        echo '              <h4><div class="badge badge-light">'.htmlspecialchars($author['bookAuthor']).'</div></h4>';
        echo '          </div>';
        echo '      </div>';

        echo '      <div class="col text-right">';
        echo '          <div class="row text-right">';
        echo '              <div class="col text-right mt-1">';
        echo '                  <a href="./author_edit.php?authorID='.$author['authorID'].'" class="btn btn-light btn-sm">Upravit</a>';
        echo '                  <a href="./author_delete.php?authorID='.$author['authorID'].'" class="btn btn-danger btn-sm">Smazat</a>';
        echo '              </div>';
        echo '          </div>';
        echo '      </div>';
        
        echo '  </div>';
        echo '  </li>';
    }
    echo '</div>';
    ?>
    </ul>
    
<?php
include 'include/footer.php';
?>