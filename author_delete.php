<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $authorID = $_GET['authorID'];

    $query = $db->prepare( 
        'SELECT library_authors.author_id AS authorID, library_authors.name AS authorName
        FROM library_authors
        WHERE author_id = '.$authorID.'');
    
    $query->execute();
    
    $author = $query->fetch();
    if(empty($author)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádní autoři.</div>';
    }else{
        $authorID = $author['authorID'];
        $authorName = $author['authorName'];
    };
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #region smazani autora
            
            $delQuery=$db->prepare('DELETE FROM library_authors WHERE author_id=?;');
            $delQuery->execute(array(
                $authorID
            ));

            header('Location: authors.php');
            exit();
        #endregion smazani autora
    };

    $pageTitle="Odstranění autora";
    include 'include/header.php';
?>      

<div class="row">
    <h2 class="col">Odstranění autora</h2>
</div>
        
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Opravdu chcete tohoto autora smazat z databáze?</h2>
                <div class="form-group">
                    <input id="delete_name" type="text" disabled class="form-control text-center font-weight-bold" 
                        name="delete_name" value="<?php echo ''.htmlspecialchars($authorName).'';?>"/>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <a href="authors.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-danger col form-control ml-2">Smazat</button>
                    </div>
                </div>
            </form>
        </div>


<?php
    include 'include/footer.php';
?>