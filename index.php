<?php

    require_once 'include/user.php';

    include 'include/header.php';

    $query = $db->prepare(
        'SELECT library_loaned_books.*, library_users.first_name AS user_Firstname, library_users.last_name AS user_Lastname, library_users.email AS email, library_books.name AS books_name 
        FROM library_loaned_books JOIN library_users USING (user_id) JOIN library_books USING (book_id) ORDER BY loan_id ASC');
    
    $query->execute();
    
    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($book_list)){
        echo '<div class="row">';
        foreach($book_list as $book){
            echo '<article class="col-12 col-md-6 col-lg-4 col-xxl-3 border border-dark mx-1 my-1 px-2 py-1">';
            echo '  <div><span class="badge badge-secondary">'.htmlspecialchars($book['user_Firstname']).'</span></div>';
            echo '  <div>'.nl2br(htmlspecialchars($book['email'])).'</div>';
            echo '  <div class="small text-muted mt-1">';
                        echo htmlspecialchars($book['user_Lastname']);
            echo '  </div>';
            echo '  </article>';
        }
        echo '</div>';
    }else{
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }


    if(!empty($_SESSION['user_id'])){
        echo '<div class="row my-3">
        <a href="reservation_form.php" class="btn btn-primary">Přidat rezervaci</a>
        </div>';
    };


    include 'include/footer.php';
?>