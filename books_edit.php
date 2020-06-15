<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $bookID = $_GET['bookID'];

    $infoQuery = $db->prepare( 
        'SELECT books.*, books.book_id AS bookID, books.name AS bookName, library_authors.name AS bookAuthor, library_genres.name AS bookGenre, library_genres.genre_id AS genreID,
        books.year AS bookYear, books.max_stock AS bookMax, books.borrowed AS bookLoaned, books.description AS bookDescr, library_authors.author_id AS authorID  
        FROM books JOIN library_authors USING (author_id) JOIN library_genres USING (genre_id)
        WHERE book_id = '.$bookID.'
        ORDER BY books.name ASC');
    
    $infoQuery->execute();
    
    $book = $infoQuery->fetch();
    if(empty($book)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné knihy.</div>';
    }else{
        $bookID = $book['bookID'];
        $bookName = $book['bookName'];
        $bookGenre = $book['bookGenre'];
        $genreID = $book['genreID'];
        $authorID = $book['authorID'];
        $bookAuthor = $book['bookAuthor'];
        $bookYear = $book['bookYear'];
        $bookMax = $book['bookMax'];
        $bookDescr = $book['bookDescr'];
    };

    $genre_query = $db->prepare( 
        'SELECT DISTINCT library_genres.name AS bookGenre, library_genres.genre_id AS genreID  
        FROM library_genres
        ORDER BY name ASC');

    $genre_query->execute();
    
    $genre_list = $genre_query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($genre_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné žánry.</div>';
    };

    $author_query = $db->prepare( 
        'SELECT DISTINCT library_authors.name AS bookAuthor, library_authors.author_id AS authorID  
        FROM library_authors
        ORDER BY name ASC');

    $author_query->execute();
    
    $author_list = $author_query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($author_list)){
        echo '<div class="alert alert-info">Nebyly nalezeni žádní autoři.</div>';
    };

    $allBooks_query = $db->prepare( 
        'SELECT DISTINCT books.name AS bookName
        FROM books
        ORDER BY name ASC');

    $allBooks_query->execute();
    
    $book_list = $allBooks_query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($book_list)){
        echo '<div class="alert alert-info">Nebyly nalezeni žádní autoři.</div>';
    };

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

        $edit_name=trim(@$_POST['edit_name']);
        if(empty($edit_name)){
            $errors['edit_name']='Pole je povinné';
        }

        if($edit_name !== $book['bookName']){
            foreach($book_list as $book){
                $bookCheck = preg_replace('/\s+/', '', $book['bookName']);
                $nameCheck = preg_replace('/\s+/', '', $edit_name);
                if($nameCheck === $bookCheck){
                    $errors['edit_name']='Tato kniha již existuje!';
                }
            }
        }

        $author_picker=trim(@$_POST['author_picker']);
        if(empty($author_picker)){
            $errors['author_picker']='Pole je povinné';
        }

        $genre_picker=trim(@$_POST['genre_picker']);
        if(empty($genre_picker)){
            $errors['genre_picker']='Pole je povinné';
        }

        $edit_year=trim(@$_POST['edit_year']);
        if(empty($edit_year)){
            $errors['edit_year']='Pole je povinné';
        }elseif(!is_numeric($edit_year)){
            $errors['edit_year']='Povolená jsou pouze čísla!';
        }

        $edit_max_stock=trim(@$_POST['edit_max_stock']);
        if(empty($edit_max_stock)){
            $errors['edit_max_stock']='Pole je povinné';
        }elseif(!is_numeric($edit_max_stock)){
            $errors['edit_max_stock']='Povolená jsou pouze čísla!';
        }

        $edit_description=trim(@$_POST['edit_description']);
        if(empty($edit_description)){
            $errors['edit_description']='Pole je povinné';
        }

        #region uprava knihy
        if(empty($errors)){
            
            $updateQuery=$db->prepare('UPDATE books SET name=?, author_id=?, genre_id=?, max_stock=?, description=? WHERE book_id=?');
            $updateQuery->execute(array(
                $edit_name,
                $author_picker,
                $genre_picker,
                $edit_max_stock,
                $edit_description,
                $bookID
            ));

            header('Location: book_list.php');
            exit();
        }
        #endregion uprava knihy

        #endregion zpracovani formulare
    };

    $pageTitle="Úprava knihy";
    include 'include/header.php';
?>      
        <div class="row mx-3">
            <h2 class="col">Úprava knihy</h2>
        </div>
        
        <div class="new_book-form pt-5">
            

            <form method="POST">
                <h2 class="text-center mb-4 pl-5">Upravit knihu</h2>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-book"></i></span>
                        <input id="edit_name" type="text" class="form-control col w-75<?php echo(!empty($errors['edit_name']) ? ' is-invalid' : ''); ?>" 
                        name="edit_name" placeholder="Název knihy" value="<?php if(empty($_POST)){echo ''.htmlspecialchars($book['bookName']).'';}else{echo htmlspecialchars(@$edit_name);}?>"/>
                        <?php
                            echo (!empty($errors['edit_name'])?'<div class="invalid-feedback">'.$errors['edit_name'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-user"></i></span>
                        <select name="author_picker" id="author_picker" class="form-control custom-picker selectpicker col w-75 <?php echo(!empty($errors['author_picker']) ? ' is-invalid' : ''); ?>" data-size="5" data-dropup-auto="false" data-live-search="true"
                         required>
                                    <option value="<?php if(empty($_POST)){echo ''.htmlspecialchars($authorID).'';}else{echo htmlspecialchars(@$author_picker);}?>">
                                    <?php echo ''.htmlspecialchars($bookAuthor).'';?>
                                    </option>
                                    <?php
                                        if(!empty($author_list)){
                                            foreach($author_list as $author){
                                                echo '<option value="' . $author['authorID'] . '"';
                                                if ($author['authorID'] == @$_POST['authorID']) {
                                                    echo ' selected="selected" ';
                                                }
                                                echo '>' . htmlspecialchars($author['bookAuthor']).'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                        <?php
                            echo (!empty($errors['author'])?'<div class="invalid-feedback">'.$errors['author'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-dragon"></i></span>
                        <select name="genre_picker" id="genre_picker" class="form-control custom-picker selectpicker col w-75 <?php echo(!empty($errors['genre_picker']) ? ' is-invalid' : ''); ?>" data-size="5" data-dropup-auto="false" data-live-search="true" required>
                                    <option value="<?php if(empty($_POST)){echo ''.htmlspecialchars($genreID).'';}else{echo htmlspecialchars(@$genre_picker);}?>">
                                    <?php echo ''.htmlspecialchars($bookGenre).'';?>
                                    </option>
                                    <?php
                                        if(!empty($genre_list)){
                                            foreach($genre_list as $genre){
                                                echo '<option value="' . $genre['genreID'] . '"';
                                                if ($genre['genreID'] == @$_POST['genreID']) {
                                                    echo ' selected="selected" ';
                                                }
                                                echo '>' . htmlspecialchars($genre['bookGenre']).'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                        <?php
                            echo (!empty($errors['genre'])?'<div class="invalid-feedback">'.$errors['genre'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-feather"></i></span>
                        <input id="edit_year" type="text" class="form-control col w-75<?php echo(!empty($errors['edit_year']) ? ' is-invalid' : ''); ?>" 
                        name="edit_year" placeholder="Rok vydání originálu" value="<?php if(empty($_POST)){echo ''.htmlspecialchars($book['bookYear']).'';}else{echo htmlspecialchars(@$edit_year);}?>"/>
                        <?php
                            echo (!empty($errors['edit_year'])?'<div class="invalid-feedback">'.$errors['edit_year'].'</div>':'');
                        ?>			
                    </div>
                </div>
                <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-calculator"></i></span>
                        <input id="edit_max_stock" type="text" class="form-control col w-75<?php echo(!empty($errors['edit_max_stock']) ? ' is-invalid' : ''); ?>" 
                        name="edit_max_stock" placeholder="Celkem svazků" value="<?php if(empty($_POST)){echo ''.htmlspecialchars($book['bookMax']).'';}else{echo htmlspecialchars(@$edit_max_stock);}?>"/>
                        <?php
                            echo (!empty($errors['edit_max_stock'])?'<div class="invalid-feedback">'.$errors['edit_max_stock'].'</div>':'');
                        ?>			
                    </div>
                </div>
            	<div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-file-text-o"></i></span>
                        <textarea id="edit_description" type="text-area" rows="5" class="form-control col w-75<?php echo(!empty($errors['edit_description']) ? ' is-invalid' : ''); ?>" 
                        name="edit_description" placeholder="Popis"><?php if(empty($_POST)){echo ''.htmlspecialchars($book['bookDescr']).'';}else{echo htmlspecialchars(@$edit_description);}?></textarea>
                        <?php
                            echo (!empty($errors['edit_description'])?'<div class="invalid-feedback">'.$errors['edit_description'].'</div>':'');
                        ?>				
                    </div>
                </div>        
                <div class="form-group">
                    <div class="input-group">
                        <a href="book_list.php" class="col btn btn-outline-secondary mr-2 w-25">Zrušit</a>
                        <button type="submit" class="btn btn-dark col form-control ml-2">Upravit</button>
                    </div>
                </div>
                </div>
            </form>
        </div>

<?php
    include 'include/footer.php';
?>