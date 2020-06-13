<?php

    require_once 'include/user.php';
    require_once 'user_required.php';

    if ($loggedUser) {
        $role = (int) $loggedUser['role_id'];
    }

    $query = $db->prepare( 
        'SELECT books.*, books.book_id AS bookID, books.name AS bookName, library_authors.name AS bookAuthor, books.max_stock AS bookMax, books.borrowed AS bookLoaned, books.description AS bookDescr  
        FROM books JOIN library_authors USING (author_id)');
    
    $query->execute();
    
    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    };

    include 'include/header.php';
?>
        <h2>Seznam knih</h2>
        <div class="col w-100 px-0">
        <?php
        foreach($book_list as $book){
            $availableBooks = $book['bookMax'] - $book['bookLoaned'];
            echo '<article class="col border border-dark my-1 py-1 w-100 bg-secondary text-white">';
            echo '  <div class="row">';
            echo '      <div class="col">';
            echo '          <h4><a href="./book_detail.php?bookID='.$book['bookID'].'" class="badge badge-light no_underline">'.htmlspecialchars($book['bookName']).'</a></h4>';
            echo '          <div>'.nl2br(htmlspecialchars($book['bookAuthor'])).'</div>';
            echo '      </div>';

            echo '      <div class="col text-right">';
            echo '          <div class="small text-white mt-1">'.'Aktuálně&nbsp;dostupné:&nbsp;'.$availableBooks.'</div>';
            echo '      </div>';
            echo '  </div>';
            echo '  </article>';
        }
        ?>
        </div>

<?php
    include 'include/footer.php';
?>