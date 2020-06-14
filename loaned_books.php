<?php

    require_once 'include/user.php';
    require 'user_required.php';

    $userID = $_SESSION['user_id'];

    $query = $db->prepare( 
        'SELECT library_loaned_books.*, books.name AS book_name, library_authors.name AS bookAuthor, library_loaned_books.loan_id AS loanID, 
        library_loaned_books.loan_start_date AS startDate, library_loaned_books.loan_expire_date AS expDate, library_genres.name as genre, books.book_id as bookID
        FROM library_loaned_books JOIN books USING (book_id) JOIN library_authors USING (author_id) JOIN library_genres USING (genre_id)
        WHERE user_id = '.$userID.'
        ORDER BY loan_id ASC 
    ');
    
    $query->execute();
    
    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    };
    $pageTitle="Vypůjčené knihy";
    include 'include/header.php';
?>
        <h2 class="mx-3">Vypůjčené knihy</h2>
        <div class="col text-white w-100 px-0">
        <ul class="list-group mx-3">
        <?php
        $today = date("Y-m-d");
            foreach($book_list as $book){
                $originalDate = htmlspecialchars($book['expDate']);
                $expDateCZ = date("d. m. Y", strtotime($originalDate));
                echo '<li class="list-group-item col border border-dark my-1 py-1 w-100 text-white';
                    if($today < $book['expDate']){echo ' bg-secondary">';}else{echo ' bg-danger">';}
                echo '  <div class="row">';
                echo '      <div class="col">';
                echo '          <div class="row">';
                echo '              <h4><a href="./book_detail.php?bookID='.$book['bookID'].'" class="badge badge-light">'.htmlspecialchars($book['book_name']).'</a></h4>';
                echo '              <h6><div class="badge badge-warning ml-3">'.htmlspecialchars($book['genre']).'</div></h6>';
                echo '          </div>';
                echo '          <div class="align-bottom">'.nl2br(htmlspecialchars($book['bookAuthor'])).'</div>';
                echo '      </div>';

                echo '      <div class="col text-right">';
                echo '          <div class="small text-white my-1">'.'Půjčeno do:&nbsp;'.htmlspecialchars($expDateCZ).'</div>';
                echo '          <div class="small text-white my-1">'.'ID výpůjčky:&nbsp;'.htmlspecialchars($book['loanID']).'</div>';
                echo '          <div class="row text-right">';
                echo '              <div class="col text-right">';
                echo '                  <a href="./book_extension.php?bookID='.$book['bookID'].'" class="btn btn-success btn-sm px-3">Prodloužit</a>';
                echo '                  <a href="./book_return.php?bookID='.$book['bookID'].'" class="btn btn-light btn-sm px-4">Vrátit</a>';
                echo '              </div>';
                echo '          </div>';
                echo '      </div>';
                echo '  </div>';
                echo '</li>';
            }
        ?>
        </ul>
        </div>

<?php
    include 'include/footer.php';
?>