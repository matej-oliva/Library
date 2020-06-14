<?php

    require_once 'include/user.php';
    require 'user_required.php';

    $bookID = $_GET['bookID'];

    $queryInfo = $db->prepare( 
        'SELECT books.book_id AS bookID, books.name AS bookName, library_loaned_books.loan_id as loanID, 
        library_loaned_books.extended as loanExtended, library_loaned_books.loan_expire_date as loanExpDate
        FROM library_loaned_books JOIN books USING (book_id)
        WHERE book_id = '.$bookID.'
        ORDER BY books.name ASC');
    
    $queryInfo->execute();
    
    $book = $queryInfo->fetch();
    if(empty($book)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }else{
        $bookID = $book['bookID'];
        $bookName = $book['bookName'];
        $loanExtended = $book['loanExtended'];
        $loanID = $book['loanID'];
        $loanExpDate = $book['loanExpDate'];
    };
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #region smazani knihy
        if($loanExtended != 0){
            echo '<div class="alert alert-danger">Kniha byla již jednou prodloužena, je nutné knihu nejdříve vrátit!</div>';
        }elseif($loanExpDate < date("Y-m-d")){
            echo '<div class="alert alert-danger">Byla překročena výpůjční lhůta. Je nutné nejdříve knihu vrátit!</div>';
        }else{
            $loanQuery=$db->prepare('UPDATE library_loaned_books SET loan_expire_date = DATE_ADD(:loanExpDate, INTERVAL 1 MONTH), extended = 1
            WHERE loan_id = '.$loanID.'');
            $loanQuery->execute(array(
                ':loanExpDate'=> htmlspecialchars($loanExpDate)
            ));

            header('Location: loaned_books.php');
            exit();
        }
        #endregion pujceni knihy
    };

    $pageTitle="Prodloužení výpůjčky";
    include 'include/header.php';
?>      

<div class="row">
    <h2 class="col">Prodloužení výpůjčky</h2>
</div>
        
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Chcete prodloužit vypůjčku této knihy?</h2>
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