<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $authorID = $_GET['authorID'];

    $query = $db->prepare( 
        'SELECT library_authors.author_id AS authorID, library_authors.name AS authorName
        FROM library_authors
        WHERE author_id = '.$authorID.'');
    
    $query->execute();

    $allAuthorsQuery = $db->prepare( 
        'SELECT DISTINCT library_authors.name AS bookAuthor, library_authors.author_id AS authorID  
        FROM library_authors
        ORDER BY name ASC');

    $allAuthorsQuery->execute();
    $author_list = $allAuthorsQuery->fetchAll(PDO::FETCH_ASSOC);
    
    $author = $query->fetch();
    if(empty($author)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádní autoři.</div>';
    }else{
        $authorID = $author['authorID'];
        $authorName = $author['authorName'];
    };
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

        if(empty($errors)){
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                #region uprava autora
            
                    $delQuery=$db->prepare('UPDATE library_authors SET name=?  WHERE author_id=?;');
                    $delQuery->execute(array(
                        $name,
                        $authorID
                    ));
                
                    header('Location: authors.php');
                    exit();
                #endregion uprava autora
            };
        }
    }

    $pageTitle="Úprava autora";
    include 'include/header.php';
?>      

<div class="row">
    <h2 class="col">Úprava autora</h2>
</div>
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Upravit jméno autora</h2>
                <div class="form-group">
                    <input id="name" type="text" class="form-control text-center font-weight-bold <?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" 
                        name="name" value="<?php echo ''.htmlspecialchars($authorName).'';?>"/>
                        <?php
                            echo (!empty($errors['name'])?'<div class="invalid-feedback">'.$errors['name'].'</div>':'');
                        ?>		
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <a href="authors.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-dark col form-control ml-2">Upravit</button>
                    </div>
                </div>
            </form>
        </div>
<?php
    include 'include/footer.php';
?>