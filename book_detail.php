<?php

    require_once 'include/user.php';

    $bookID = $_GET['bookID'];

    $query = $db->prepare( 
        'SELECT library_books.*, library_books.book_id AS bookID, library_books.name AS bookName, library_books.author AS bookAuthor, library_books.max_stock AS bookMax, library_books.borrowed AS bookLoaned, library_books.description AS bookDescr  
        FROM library_books
        WHERE book_id = '.$bookID.'');
    
    $query->execute();
    
    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    };
    include 'include/header.php';
?>

        <div class="col w-100 px-0">
            <?php
        foreach($book_list as $book){
            $availableBooks = $book['bookMax'] - $book['bookLoaned'];
            echo '<article class="col border border-dark my-1 py-1 mx-auto w-75 bg-secondary text-white">';
            echo '  <div class="container-fluid">';
            echo '      <div class="col">';
            echo '          <h4><div class="badge badge-light no_underline">'.htmlspecialchars($book['bookName']).'</div></h4>';
            echo '          <div>'.nl2br(htmlspecialchars($book['bookAuthor'])).'</div>';
            echo '          <div>'.htmlspecialchars($book['bookDescr']).'</div>';
            echo '      </div>';

            echo '      <div class="col text-right">';
            echo '          <div class="small text-white mt-1">'.'Aktuálně&nbsp;dostupné:&nbsp;'.$availableBooks.'</div>';
            if(!empty($_SESSION['user_id'])){
                echo '<div class="mt-2"><a href="reservation_form.php" class="btn btn-light px-4">Rezervovat</a></div>';
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