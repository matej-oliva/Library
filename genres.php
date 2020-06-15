
<?php

require_once 'include/user.php';
require 'librarian_required.php';


$query = $db->prepare( 
    'SELECT library_genres.name AS bookgenre, library_genres.genre_id AS genreID
    FROM library_genres
    ORDER BY library_genres.name ASC');

$query->execute();

$genre_list = $query->fetchAll(PDO::FETCH_ASSOC);
if(empty($genre_list)){
    echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
};

$pageTitle="Žánry";
include 'include/header.php';
?>      
    <div class="row">
        <h2 class="col-4 mt-2 mx-3">Seznam žánrů</h2>
        <div class="input-group flex-nowrap col-3 py-2 mr-auto mt-1 text-center">
            <div class="input-group-prepend">
                <span class="input-group-text fa fa-search"></span>
            </div>
            <input class="form-control" id="searchBar" type="text" placeholder="Vyhledat žánr">
        </div>
        <form action="users.php" method="GET" class="col text-right py-2 mr-3">
        <a href="genre_new.php" class="btn btn-success px-4 mt-1">Přidat žánr</a>
        </form>
    </div>
    
    <div class="col w-100 px-0">
    <ul class="list-group mx-3" id="searchList">
    <?
    foreach($genre_list as $genre){
        echo '<li class="list-group-item col border border-dark my-1 py-1 w-50 bg-secondary text-white mx-auto">';
        echo '  <div class="row">';
        echo '      <div class="col ml-4">';
        echo '          <div class="row">';
        echo '              <h4><div class="badge badge-light">'.htmlspecialchars($genre['bookgenre']).'</div></h4>';
        echo '          </div>';
        echo '      </div>';

        echo '      <div class="col text-right">';
        echo '          <div class="row text-right">';
        echo '              <div class="col text-right mt-1">';
        echo '                  <a href="./genre_edit.php?genreID='.$genre['genreID'].'" class="btn btn-light btn-sm">Upravit</a>';
        echo '                  <a href="./genre_delete.php?genreID='.$genre['genreID'].'" class="btn btn-danger btn-sm">Smazat</a>';
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