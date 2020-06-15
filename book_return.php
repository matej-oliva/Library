<?php

    require_once 'include/user.php';
    require 'user_required.php';

    $loanID = $_GET['loanID'];

    $queryInfo = $db->prepare( 
        'SELECT books.book_id AS bookID, books.name AS bookName, library_loaned_books.loan_id as loanID
        FROM library_loaned_books JOIN books USING (book_id)
        WHERE loan_id = '.$loanID.'
        ORDER BY books.name ASC');
    
    $queryInfo->execute();
    
    $book = $queryInfo->fetch();
    if(empty($book)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }else{
        $bookID = $book['bookID'];
        $bookName = $book['bookName'];
        $loanID = $book['loanID'];
    };
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #region smazani knihy
            $delQuery=$db->prepare('DELETE FROM library_loaned_books WHERE loan_id=?;');
            $delQuery->execute(array(
                $loanID
            ));

            $borQuery=$db->prepare('UPDATE books SET borrowed = borrowed - 1 WHERE book_id=?;');
            $borQuery->execute(array(
                $bookID
            ));

            header('Location: loaned_books.php');
            exit();
        #endregion pujceni knihy
    };

    $pageTitle="Vrácení knihy";
    include 'include/header.php';
?>      

<div class="row mx-3">
    <h2 class="col">Vrácení knihy</h2>
</div>
        
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Chcete tuto knihu vrátit?</h2>
                <div class="form-group">
                    <input id="name" type="text" disabled class="form-control text-center font-weight-bold<?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" 
                        name="name" value="<?php echo ''.htmlspecialchars($book['bookName']).'';?>"/>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <a href="loaned_books.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-success col form-control ml-2">ANO!</button>
                    </div>
                </div>
            </form>
        </div>


<?php
    include 'include/footer.php';
?>