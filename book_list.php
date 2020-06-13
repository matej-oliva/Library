
<?php

    require_once 'include/user.php';
    

    $query = $db->prepare( 
        'SELECT books.*, books.book_id AS bookID, books.name AS bookName, library_authors.name AS bookAuthor, books.max_stock AS bookMax, books.borrowed AS bookLoaned, books.description AS bookDescr  
        FROM books JOIN library_authors USING (author_id)
        ORDER BY library_authors.name ASC');

    $query->execute();

    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    };

    $pageTitle="Seznam knih";
    include 'include/header.php';
?>      
        <div class="row">
            <h2 class="col">Seznam knih</h2>
            <form action="users.php" method="GET" class="col text-right py-2 mr-3">
            <a href="new_book.php" class="btn btn-success px-4">Přidat knihu</a>
            </form>
        </div>
        
        <div class="col w-100 px-0">
        
        <?
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
            echo '          <div class="row text-right">';
            echo '              <div class="col text-right">';
            echo '                  <a href="./book_detail.php?bookID='.$book['bookID'].'" class="btn btn-info btn-sm">Rezervovat</a>';
            echo '                  <a href="./books_edit.php?bookID='.$book['bookID'].'" class="btn btn-light btn-sm">Upravit</a>';
            echo '                  <a href="./book_delete.php?bookID='.$book['bookID'].'" class="btn btn-danger btn-sm">Smazat</a>';
            echo '              </div>';
            echo '          </div>';
            echo '      </div>';
            
            echo '  </div>';
            echo '  </article>';
        }
        echo '</div>';
        ?>

<?php
    include 'include/footer.php';
?>