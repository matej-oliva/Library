<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

        $name=trim(@$_POST['edit_name']);
        if(empty($edit_name)){
            $errors['edit_name']='Pole je povinné';
        }

        $author=trim(@$_POST['edit_author']);
        if(empty($edit_author)){
            $errors['edit_author']='Pole je povinné';
        }

        $max_stock=trim(@$_POST['edit_max_stock']);
        if(empty($edit_max_stock)){
            $errors['edit_max_stock']='Pole je povinné';
        }

        $description=trim(@$_POST['edit_description']);
        if(empty($edit_description)){
            $errors['edit_description']='Pole je povinné';
        }

        #region uprava knihy
        if(empty($errors)){
            
            $query=$db->prepare('UPDATE library_books SET book_id=?, name=?, author=?, max_stock=?, description=? WHERE book_id=?');
            $query->execute(array(
                $edit_name,
                $edit_author,
                $edit_max_stock,
                $edit_description,
                $edit_sel_bookID
            ));

            header('Location: index.php');
            exit();
        }
        #endregion uprava knihy

        #endregion zpracovani formulare
    };

    $query = $db->prepare( 
        'SELECT library_books.*, library_books.book_id AS bookID, library_books.name AS bookName, library_books.author AS bookAuthor, library_books.max_stock AS bookMax, library_books.borrowed AS bookLoaned, library_books.description AS bookDescr  
        FROM library_books
        ORDER BY library_books.name ASC');
    
    $query->execute();
    
    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    };


    

    $pageTitle="Správa knih";
    include 'include/header.php';
?>      
        <div class="row">
            <h2 class="col">Správa knih</h2>
            <form action="users.php" method="GET" class="col text-right py-2 mr-3">
            <a href="new_book.php" class="btn btn-dark px-4">Přidat knihu</a>
            </form>
        </div>
        
        <div class="new_book-form pt-5">
            <form method="POST" id="book_pick_form" action="">   
                <div class="form-group">
                	<div class="input-group">
                        <select name="book_picker" id="book_picker" class="form-control col w-75" required>
                            <option value="">--vyberte--</option>
                            <?php
                                if(!empty($book_list)){
                                    foreach($book_list as $book){
                                        echo '<option value="' . $book['bookID'] . '"';
                                        if ($book['bookID'] == @$_POST['id']) {
                                            echo ' selected="selected" ';
                                        }
                                        echo '>' . htmlspecialchars($book['bookName']).'</option>';
                                    }
                                }
                            ?>
                        </select>
                        <input type="submit" name="sel_submit" value="vybrat">			
                    </div>
                </div>
            </form>
            <?php
            if(isset($_POST['sel_submit'])){
                $sel_book_id = $_POST['book_picker'];
                $query = $db->prepare( 
                    'SELECT library_books.*, library_books.book_id AS sel_bookID, library_books.name AS sel_bookName, library_books.author AS sel_bookAuthor, library_books.max_stock AS sel_bookMax, library_books.borrowed AS sel_bookLoaned, library_books.description AS sel_bookDescr  
                    FROM library_books
                    WHERE book_id = '.$sel_book_id.'');
                
                $query->execute();
                
                $selected_book_list = $query->fetchAll(PDO::FETCH_ASSOC);
                if(empty($selected_book_list)){
                    echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
                }else{
                    foreach($selected_book_list as $book){
                        $sel_bookID = $book['sel_bookID'];
                        $sel_bookName = $book['sel_bookName'];
                        $sel_bookAuthor = $book['sel_bookAuthor'];
                        $sel_bookMax = $book['sel_bookMax'];
                        $sel_bookDescr = $book['sel_bookDescr'];
                    }
                };
            }
            ?>

            <form method="POST">
                <h2 class="text-center">Upravit knihu</h2>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-book"></i></span>
                        <input id="edit_name" type="text" class="form-control col w-75<?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" name="edit_name" placeholder="název knihy" value="<?php if(isset($sel_bookName)){echo $sel_bookName;}else{echo '';}?>"/>
                        <?php
                            echo (!empty($errors['edit_name'])?'<div class="invalid-feedback">'.$errors['edit_name'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-user"></i></span>
                        <input id="edit_author" type="text" class="form-control col w-75<?php echo(!empty($errors['author']) ? ' is-invalid' : ''); ?>" name="edit_author" placeholder="autor knihy" value="<?php if(isset($sel_bookAuthor)){echo $sel_bookAuthor;}else{echo '';}?>"/>
                        <?php
                            echo (!empty($errors['edit_author'])?'<div class="invalid-feedback">'.$errors['edit_author'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-calculator"></i></span>
                        <input id="edit_max_stock" type="text" class="form-control col w-75<?php echo(!empty($errors['max_stock']) ? ' is-invalid' : ''); ?>" name="edit_max_stock" placeholder="Počet kusů" value="<?php if(isset($sel_bookMax)){echo $sel_bookMax;}else{echo '';}?>"/>
                        <?php
                            echo (!empty($errors['edit_max_stock'])?'<div class="invalid-feedback">'.$errors['edit_max_stock'].'</div>':'');
                        ?>			
                    </div>
                </div>
            	<div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-file-text-o"></i></span>
                        <textarea id="edit_description" type="text-area" rows="5" class="form-control col w-75<?php echo(!empty($errors['description']) ? ' is-invalid' : ''); ?>" name="edit_description" placeholder="Popis"><?php if(isset($sel_bookDescr)){echo $sel_bookDescr;}else{echo '';}?></textarea>
                        <?php
                            echo (!empty($errors['edit_description'])?'<div class="invalid-feedback">'.$errors['edit_description'].'</div>':'');
                        ?>				
                    </div>
                </div>        
                <div class="form-group">
                    <button type="submit" class="btn btn-dark login-btn btn-block">Upravit</button>
                </div>
                </div>
            </form>
        </div>

<?php
    include 'include/footer.php';
?>