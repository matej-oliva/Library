<?php
    require_once 'include/user.php';
    require_once 'librarian_required.php';

    $genre_query = $db->prepare( 
        'SELECT DISTINCT library_genres.name AS bookGenre, library_genres.genre_id AS genreID  
        FROM library_genres
        ORDER BY name ASC');

    $genre_query->execute();
    $genre_list = $genre_query->fetchAll(PDO::FETCH_ASSOC);

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

        $name=trim(@$_POST['name']);
        if(empty($name)){
            $errors['name']='Pole je povinné';
        }

        foreach($genre_list as $genre){
            $genreCheck = preg_replace('/\s+/', '', $genre['bookGenre']);
            $nameCheck = preg_replace('/\s+/', '', $name);
            if($nameCheck === $genreCheck){
                $errors['name']='Žánr již existuje!';
            }
        }

        #region nova kniha
        if(empty($errors)){
            
            $edit_query=$db->prepare('INSERT INTO library_genres (name) VALUES (:name);');
            $edit_query->execute([
                ':name'=>$name
            ]);

            header('Location: genres.php');
            exit();
        }
        #endregion nova kniha

        #endregion zpracovani formulare
    };
    $pageTitle="Nový žánr";
    include 'include/header.php';
?>
<div class="new_book-form pt-5">
    <form method="post">
        <h2 class="text-center pl-5">Přidat žánr</h2>   
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-feather"></i></span>
                <input id="name" type="text" class="form-control col w-75<?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" name="name" placeholder="název knihy" value="<?php echo htmlspecialchars(@$name);?>"/>
                <?php
                    echo (!empty($errors['name'])?'<div class="invalid-feedback">'.$errors['name'].'</div>':'');
                ?>		
            </div>
        </div>
        <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon col-2"></span>
                        <a href="genres.php" class="col-5 btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-success col-5 form-control ml-2">Přidat</button>
                    </div>
                </div>
    </form>
</div> 

<?php
    include 'include/footer.php';
?>