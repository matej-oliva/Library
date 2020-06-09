<?php

    require_once 'include/user.php';
    require_once 'admin_required.php';

    $errors=[];
    if(!empty($_POST)){

        #region kontrola vyberu knihy
        if(!empty($_POST['book'])){
            
            $booksQuery=$db->prepare('SELECT * FROM library_books WHERE book_id=:book LIMIT 1;');
            $booksQuery->execute([
                ':book'=>$_POST['book']
            ]);
            if($booksQuery->rowCount()==0){
                $errors['book']='Zvolená kniha neexistuje';
                $_POST['book']='';
            }
        }else{
            $errors['book']='Vyberte knihu.';
        };
        #endregion
        #region kontrola textu
        $text=trim(@$_POST['text']);
        if(empty($text)){
            $errors['text']='Musíte zadat text';
        }
        #endregion

        /* if(empty($errors)){
            $saveQuery = $db->prepare('INSERT INTO library_books (book_id, name, author, max_stock, borrowed) VALUES (, :book, )');
            //TODO!!!!
        }; */
    };


    $pageTitle='Nová kniha';
    include 'include/header.php';

?>
    <form method="post">
        <div>
            <label for="library_books">Knihy:</label>
            <select name="library_books" id="library_books" required>
                <option value="">--vyberte--</option>
                <?php
                    $booksQuery = $db->prepare(
                        'SELECT * FROM library_books ORDER BY name;');
                    $booksQuery->execute();
                    $books=$booksQuery->fetchAll(PDO::FETCH_ASSOC);
                    if(!empty($books)){
                        foreach($books as $book){
                            echo '<option value="'.$book['book_id'].'" '.($book['book_id']==@$_POST['book']?'selected="selected"':'').'>'.htmlspecialchars($book['name']).'</option>';
                        }
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="text">Text</label>
            <textarea name="text" id="text" required><?php echo htmlspecialchars(@$_POST['text'])?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">uložit</button>
        <a href="index.php" class="btn btn-light">zrušit</a>
    </form>

<?php
    include 'include/footer.php';