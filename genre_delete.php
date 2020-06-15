<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $genreID = $_GET['genreID'];

    $query = $db->prepare( 
        'SELECT library_genres.genre_id AS genreID, library_genres.name AS genreName
        FROM library_genres
        WHERE genre_id = '.$genreID.'');
    $query->execute();
    
    $genre = $query->fetch();
    if(empty($genre)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné žánry.</div>';
    }else{
        $genreID = $genre['genreID'];
        $genreName = $genre['genreName'];
    };
    $BooksQuery = $db->prepare( 
        'SELECT books.name AS bookName, genre_id as genreID
        FROM books
        WHERE genre_id = '.$genreID.'');
    $BooksQuery->execute();
    
    $hasBook = $BooksQuery->fetch();



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #region smazani autora

            if(!empty($hasBook)){
                echo '<div class="alert alert-danger">Nelze smazat žánr, v knihovně jsou knihy s tímto žánrem!</div>';
            }else{    
            $delQuery=$db->prepare('DELETE FROM library_genres WHERE genre_id=?;');
            $delQuery->execute(array(
                $genreID
            ));

            header('Location: genres.php');
            exit();
            }
        #endregion smazani autora
    };

    $pageTitle="Odstranění žánru";
    include 'include/header.php';
?>      

<div class="row mx-3">
    <h2 class="col">Odstranění žánru</h2>
</div>
        
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Opravdu chcete tento žánr smazat z databáze?</h2>
                <div class="form-group">
                    <input id="delete_name" type="text" disabled class="form-control text-center font-weight-bold" 
                        name="delete_name" value="<?php echo ''.htmlspecialchars($genreName).'';?>"/>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <a href="genres.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-danger col form-control ml-2">Smazat</button>
                    </div>
                </div>
            </form>
        </div>


<?php
    include 'include/footer.php';
?>