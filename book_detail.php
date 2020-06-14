<?php

    require_once 'include/user.php';

    $bookID = $_GET['bookID'];

    $query = $db->prepare( 
        'SELECT books.*, books.book_id AS bookID, books.name AS bookName, library_authors.name AS bookAuthor, books.max_stock AS bookMax, 
        books.borrowed AS bookLoaned, books.description AS bookDescr, library_genres.name AS genre, books.year AS bookYear  
        FROM books  JOIN library_authors USING (author_id) JOIN library_genres USING (genre_id)
        WHERE book_id = '.$bookID.'');
    
    $query->execute();
    
    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    };

    $pageTitle="Detail knihy";
    include 'include/header.php';
?>
        <h2>Detail knihy</h2>
        <div class="col w-100 px-0">
            <?php
        foreach($book_list as $book){
            $availableBooks = $book['bookMax'] - $book['bookLoaned'];
            echo '<article class="col border border-dark my-1 py-1 mx-auto w-75 bg-secondary text-white">';
            echo '  <div class="container-fluid">';
            echo '      <div class="col">';
            echo '          <div class="row">';
            echo '              <h4><div class="badge badge-light">'.htmlspecialchars($book['bookName']).'</div></h4>';
            echo '              <h4><div class="badge badge-info ml-3">'.htmlspecialchars($book['genre']).'</div></h4>';
            echo '          </div>';
            echo '          <div class="font-weight-bold">Autor: '.nl2br(htmlspecialchars($book['bookAuthor'])).'</div>';
            echo '          <div class="font-weight-bold mb-3">Rok prvního vydání: '.nl2br(htmlspecialchars($book['bookYear'])).'</div>';
            echo '          <div class="mb-3">'.htmlspecialchars($book['bookDescr']).'</div>';
            echo '      </div>';

            echo '      <div class="col text-right">';
            echo '          <div class="small text-white mt-1">'.'Aktuálně&nbsp;dostupné:&nbsp;'.$availableBooks.'</div>';
            if(!empty($_SESSION['user_id'])){
                echo '<div class="mt-2"><a href="./reservation.php?bookID='.$book['bookID'].'" class="btn btn-light px-4">Vypůjčit</a>';
            }
            echo '      </div>';
            echo '  </div>';
            echo '  <div class="row">';
            
            echo '  </div>';
            echo '</article>';
        }
            ?>
        </div>

<?php
    include 'include/footer.php';
?>