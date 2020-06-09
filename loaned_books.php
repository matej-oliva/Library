<?php

    require_once 'include/user.php';

    $userID = $_SESSION['user_id'];

    $query = $db->prepare( 
        'SELECT library_loaned_books.*, library_books.name AS book_name, library_books.author AS bookAuthor, library_loaned_books.loan_id AS loanID, 
        library_loaned_books.loan_start_date AS startDate, library_loaned_books.loan_expire_date AS expDate, library_loaned_books.loan_overdue AS laon_overdue
        FROM library_loaned_books JOIN library_books USING (book_id)
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
        <h2>Vypůjčené knihy</h2>
        <div class="col text-white w-100 px-0 mx-0">
        <?php
            foreach($book_list as $book){
                $originalDate = htmlspecialchars($book['expDate']);
                $expDateCZ = date("d. m. Y", strtotime($originalDate));
                echo    '<div class="row border border-dark border-strong my-1 mx-0 py-1 w-100 bg-secondary text-white">';
                echo    '  <div class="col">';
                echo    '   <h4><span class="badge badge-secondary">'.htmlspecialchars($book['book_name']).'</span></h4>';
                echo    '   <div class="align-bottom">'.nl2br(htmlspecialchars($book['bookAuthor'])).'</div>';
                echo    '  </div>';
                echo    '  <div class="col text-right">';
                echo    '   <div class="small mt-1">';
                echo    '   Půjčeno do: '.htmlspecialchars($expDateCZ).'';
                echo    '   </div>';
                echo    '   <div class="small mt-1">';
                echo    '   ID výpůjčky: '.htmlspecialchars($book['loanID']).'';
                echo    '   </div>';
                echo    '   <div class="small mt-1">';
                echo    '   <div class="mt-2"><a href="reservation_form.php" class="btn btn-light px-4">Prodloužit</a></div>';
                echo    '   </div>';
                echo    '  </div>';
                echo    '</div>';
            }
        ?>
        </div>

<?php
    include 'include/footer.php';
?>