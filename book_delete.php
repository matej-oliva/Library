<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $bookID = $_GET['bookID'];

    $query = $db->prepare( 
        'SELECT books.book_id AS bookID, books.name AS bookName
        FROM books
        WHERE book_id = '.$bookID.'
        ORDER BY books.name ASC');
    
    $query->execute();
    
    $book = $query->fetch();
    if(empty($book)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }else{
        $bookID = $book['bookID'];
        $bookName = $book['bookName'];
    };
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        #region smazani knihy
        if(empty($errors)){
            
            $delQuery=$db->prepare('DELETE FROM books WHERE book_id=?;');
            $delQuery->execute(array(
                $bookID
            ));

            header('Location: book_list.php');
            exit();
        }
        #endregion smazani knihy
    };

    $pageTitle="Smazání knihy";
    include 'include/header.php';
?>      

<div class="row">
    <h2 class="col">Smazání knihy</h2>
</div>
        
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-5">Opravdu chcete knihu smazat?</h2>
                <div class="form-group">
                    <input id="delete_name" type="text" disabled class="form-control text-center font-weight-bold<?php echo(!empty($errors['delete_name']) ? ' is-invalid' : ''); ?>" 
                        name="delete_name" value="<?php echo ''.htmlspecialchars($book['bookName']).'';?>"/>
                    <?php
                        echo (!empty($errors['delete_name'])?'<div class="invalid-feedback">'.$errors['delete_name'].'</div>':'');
                    ?>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <button type="submit" class="btn btn-danger col form-control mr-2">Smazat</button>
                        <a href="book_list.php" class="col btn btn-outline-secondary ml-2">Zrušit</a>
                    </div>
                </div>
            </form>
        </div>


<?php
    include 'include/footer.php';
?>