<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $bookID = $_GET['bookID'];

    $queryInfo = $db->prepare( 
        'SELECT books.book_id AS bookID, books.name AS bookName, books.max_stock AS maxStock, books.borrowed AS borrowed
        FROM books
        WHERE book_id = '.$bookID.'
        ORDER BY books.name ASC');
    
    $queryInfo->execute();
    
    $book = $queryInfo->fetch();
    if(empty($book)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }else{
        $bookID = $book['bookID'];
        $bookName = $book['bookName'];
        $bookStock = $book['maxStock'];
        $bookBor = $book['borrowed'];
    };
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #region smazani knihy
        if($bookStock > $bookBor){
            $loanQuery=$db->prepare('INSERT INTO library_loaned_books (user_id, book_id, loan_start_date, loan_expire_date) 
            VALUES (:user_id, :bookID, Now(), DATE_ADD(Now(), INTERVAL 1 MONTH));');
            $loanQuery->execute(array(
                ':user_id'=>$_SESSION['user_id'],
                ':bookID'=>$bookID
            ));

            $borQuery=$db->prepare('UPDATE books SET borrowed = borrowed + 1 WHERE book_id=?;');
            $borQuery->execute(array(
                $bookID
            ));

            header('Location: book_list.php');
            exit();
        }else{
            echo '<div class="alert alert-danger">Aktuálně není žádná kniha k vypůjčení, počkejte až knihu někdo vrátí.</div>';
        }
        #endregion pujceni knihy
    };

    $pageTitle="Výpůjčka knihy";
    include 'include/header.php';
?>      

<div class="row">
    <h2 class="col">Výpůjčka knihy</h2>
</div>
        
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Chcete si tuto knihu vypůjčit?</h2>
                <div class="form-group">
                    <input id="delete_name" type="text" disabled class="form-control text-center font-weight-bold<?php echo(!empty($errors['delete_name']) ? ' is-invalid' : ''); ?>" 
                        name="delete_name" value="<?php echo ''.htmlspecialchars($book['bookName']).'';?>"/>
                    <?php
                        echo (!empty($errors['delete_name'])?'<div class="invalid-feedback">'.$errors['delete_name'].'</div>':'');
                    ?>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <a href="book_list.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-success col form-control ml-2">ANO!</button>
                    </div>
                </div>
            </form>
        </div>


<?php
    include 'include/footer.php';
?>