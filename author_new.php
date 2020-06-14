<?php
    require_once 'include/user.php';
    require_once 'librarian_required.php';

    $author_query = $db->prepare( 
        'SELECT DISTINCT library_authors.name AS bookAuthor, library_authors.author_id AS authorID  
        FROM library_authors
        ORDER BY name ASC');

    $author_query->execute();
    $author_list = $author_query->fetchAll(PDO::FETCH_ASSOC);

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

        $name=trim(@$_POST['name']);
        if(empty($name)){
            $errors['name']='Pole je povinné';
        }

        foreach($author_list as $author){
            $authorCheck = preg_replace('/\s+/', '', $author['bookAuthor']);
            $nameCheck = preg_replace('/\s+/', '', $name);
            if($nameCheck === $authorCheck){
                $errors['name']='Autor již existuje!';
            }
        }

        #region nova kniha
        if(empty($errors)){
            
            $edit_query=$db->prepare('INSERT INTO library_authors (name) VALUES (:name);');
            $edit_query->execute([
                ':name'=>$name
            ]);

            header('Location: authors.php');
            exit();
        }
        #endregion nova kniha

        #endregion zpracovani formulare
    };
    $pageTitle="Nový autor";
    include 'include/header.php';
?>
<div class="new_book-form pt-5">
    <form method="post">
        <h2 class="text-center pl-5">Přidat autora</h2>   
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
                        <a href="authors.php" class="col-5 btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-success col-5 form-control ml-2">Přidat</button>
                    </div>
                </div>
    </form>
</div> 

<?php
    include 'include/footer.php';
?>