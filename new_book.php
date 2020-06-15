<?php
    require_once 'include/user.php';
    require_once 'librarian_required.php';

    $book_query = $db->prepare( 
        'SELECT DISTINCT books.name AS bookName
        FROM books');

    $book_query->execute();
    
    $book_list = $book_query->fetchAll(PDO::FETCH_ASSOC);

    $author_query = $db->prepare( 
        'SELECT DISTINCT library_authors.name AS bookAuthor, library_authors.author_id AS authorID  
        FROM library_authors
        ORDER BY name ASC');

    $author_query->execute();
    
    $author_list = $author_query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($author_list)){
        echo '<div class="alert alert-info">Nebyly nalezeni žádní autoři.</div>';
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

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

        $name=trim(@$_POST['name']);
        if(empty($name)){
            $errors['name']='Pole je povinné';
        }

        foreach($book_list as $book){
            $bookCheck = preg_replace('/\s+/', '', $book['bookName']);
            $nameCheck = preg_replace('/\s+/', '', $name);
            if($nameCheck === $bookCheck){
                $errors['name']='Kniha již existuje!';
            }
        }

        $author=trim(@$_POST['author_picker']);
        if(empty($author)){
            $errors['author']='Pole je povinné';
        }

        $genre=trim(@$_POST['genre_picker']);
        if(empty($genre)){
            $errors['genre']='Pole je povinné';
        }

        $year=trim(@$_POST['year']);
        if(empty($year)){
            $errors['year']='Pole je povinné';
        }elseif(!is_numeric($year)){
            $errors['year']='Povolená jsou pouze čísla!';
        }

        $max_stock=trim(@$_POST['max_stock']);
        if(empty($max_stock)){
            $errors['max_stock']='Pole je povinné';
        }elseif(!is_numeric($max_stock)){
            $errors['max_stock']='Povolená jsou pouze čísla!';
        }

        $description=trim(@$_POST['description']);
        if(empty($description)){
            $errors['description']='Pole je povinné';
        }

        #region nova kniha
        if(empty($errors)){
            
            $edit_query=$db->prepare('INSERT INTO books (name, author_id, genre_id, year, max_stock, description) VALUES (:name, :author, :genre, :year, :max_stock, :description);');
            $edit_query->execute([
                ':name'=>$name,
                ':author'=>$author,
                ':genre'=>$genre,
                ':year'=>$year,
                ':max_stock'=>$max_stock,
                ':description'=>$description
            ]);

            header('Location: book_list.php');
            exit();
        }
        #endregion nova kniha

        #endregion zpracovani formulare
    };
    $pageTitle="Nová kniha";
    include 'include/header.php';
?>
<div class="new_book-form pt-5">
    <form method="post">
        <h2 class="text-center pl-5">Přidat knihu</h2>   
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-book"></i></span>
                <input id="name" type="text" class="form-control col w-75<?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" name="name" placeholder="název knihy" value="<?php echo htmlspecialchars(@$name);?>"/>
                <?php
                    echo (!empty($errors['name'])?'<div class="invalid-feedback">'.$errors['name'].'</div>':'');
                ?>			
            </div>
        </div>
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-user"></i></span>
                <select name="author_picker" id="author_picker" class="form-control custom-picker selectpicker col w-75" data-size="5" data-live-search="true" required>
                            <option value="">--vyberte--</option>
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
                <select name="genre_picker" id="genre_picker" class="form-control custom-picker selectpicker col w-75" data-size="5" data-dropup-auto="false" data-live-search="true" required>
                            <option value="">--vyberte--</option>
                            <?php
                                if(!empty($genre_list)){
                                    foreach($genre_list as $genre){
                                        echo '<option value="' . $genre['genreID'] . '"';
                                        if ($genre['genreID'] == @$_POST['id']) {
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
                <input id="year" type="text" class="form-control col w-75<?php echo(!empty($errors['year']) ? ' is-invalid' : ''); ?>" 
                name="year" placeholder="Rok vydání originálu" value="<?php echo htmlspecialchars(@$year);?>"/>
                <?php
                    echo (!empty($errors['year'])?'<div class="invalid-feedback">'.$errors['year'].'</div>':'');
                ?>			
            </div>
        </div>
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-calculator"></i></span>
                <input id="max_stock" type="text" class="form-control col w-75<?php echo(!empty($errors['max_stock']) ? ' is-invalid' : ''); ?>" name="max_stock" placeholder="Počet kusů" value="<?php echo htmlspecialchars(@$max_stock);?>"/>
                <?php
                    echo (!empty($errors['max_stock'])?'<div class="invalid-feedback">'.$errors['max_stock'].'</div>':'');
                ?>			
            </div>
        </div>
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-file-text-o"></i></span>
                <textarea id="description" type="text-area" rows="5" class="form-control col w-75<?php echo(!empty($errors['description']) ? ' is-invalid' : ''); ?>" name="description" placeholder="Popis"><?php echo htmlspecialchars(@$description);?></textarea>
                <?php
                    echo (!empty($errors['description'])?'<div class="invalid-feedback">'.$errors['description'].'</div>':'');
                ?>				
            </div>
        </div>      
        <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon col w-50"></span>
                        <button type="submit" class="btn btn-dark col w-75 form-control">Přidat</button>
                    </div>
                </div>
        </div>
    </form>
</div> 

<script>
$(document).ready(function(){
  $("#author_picker").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $(".author_picker option").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>

<?php
    include 'include/footer.php';
?>