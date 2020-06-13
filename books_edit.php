<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $bookID = $_GET['bookID'];

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
            
            $query=$db->prepare('UPDATE books SET book_id=?, name=?, author=?, max_stock=?, description=? WHERE book_id=?');
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
        'SELECT books.*, books.book_id AS bookID, books.name AS bookName, library_authors.name
         AS bookAuthor, books.max_stock AS bookMax, books.borrowed AS bookLoaned, books.description AS bookDescr  
        FROM books JOIN library_authors USING (author_id)
        WHERE book_id = '.$bookID.'
        ORDER BY books.name ASC');
    
    $query->execute();
    
    $book_list = $query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }else{
        foreach($book_list as $book){
            $bookID = $book['bookID'];
            $bookName = $book['bookName'];
            $bookAuthor = $book['bookAuthor'];
            $bookMax = $book['bookMax'];
            $bookDescr = $book['bookDescr'];
        }
    };

    $pageTitle="Správa knih";
    include 'include/header.php';
?>      
        <div class="row">
            <h2 class="col">Správa knih</h2>
        </div>
        
        <div class="new_book-form pt-5">
            

            <form method="POST">
                <h2 class="text-center mb-4 pl-5">Upravit knihu</h2>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-book"></i></span>
                        <input id="edit_name" type="text" class="form-control col w-75<?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" name="edit_name" placeholder="Název knihy" value="<?php echo ''.htmlspecialchars($book['bookName']).''?>"/>
                        <?php
                            echo (!empty($errors['edit_name'])?'<div class="invalid-feedback">'.$errors['edit_name'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-user"></i></span>
                        <input id="edit_author" type="text" class="form-control col w-75<?php echo(!empty($errors['author']) ? ' is-invalid' : ''); ?>" name="edit_author" placeholder="Autor knihy" value="<?php echo ''.htmlspecialchars($book['bookAuthor']).''?>"/>
                        <?php
                            echo (!empty($errors['edit_author'])?'<div class="invalid-feedback">'.$errors['edit_author'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-calculator"></i></span>
                        <input id="edit_max_stock" type="text" class="form-control col w-75<?php echo(!empty($errors['max_stock']) ? ' is-invalid' : ''); ?>" name="edit_max_stock" placeholder="Celkem svazků" value="<?php echo ''.htmlspecialchars($book['bookMax']).''?>"/>
                        <?php
                            echo (!empty($errors['edit_max_stock'])?'<div class="invalid-feedback">'.$errors['edit_max_stock'].'</div>':'');
                        ?>			
                    </div>
                </div>
            	<div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-file-text-o"></i></span>
                        <textarea id="edit_description" type="text-area" rows="5" class="form-control col w-75<?php echo(!empty($errors['description']) ? ' is-invalid' : ''); ?>" name="edit_description" placeholder="Popis"><?php echo ''.htmlspecialchars($book['bookDescr']).''?></textarea>
                        <?php
                            echo (!empty($errors['edit_description'])?'<div class="invalid-feedback">'.$errors['edit_description'].'</div>':'');
                        ?>				
                    </div>
                </div>        
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon col w-50"></span>
                        <button type="submit" class="btn btn-dark col w-75 form-control">Upravit</button>
                    </div>
                </div>
                </div>
            </form>
        </div>

<?php
    include 'include/footer.php';
?>