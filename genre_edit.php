<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

    $genreID = $_GET['genreID'];

    $query = $db->prepare( 
        'SELECT library_genres.genre_id AS genreID, library_genres.name AS genreName
        FROM library_genres
        WHERE genre_id = '.$genreID.'');
    
    $query->execute();

    $allgenresQuery = $db->prepare( 
        'SELECT DISTINCT library_genres.name AS bookgenre, library_genres.genre_id AS genreID  
        FROM library_genres
        ORDER BY name ASC');

    $allgenresQuery->execute();
    $genre_list = $allgenresQuery->fetchAll(PDO::FETCH_ASSOC);
    
    $genre = $query->fetch();
    if(empty($genre)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné žánry.</div>';
    }else{
        $genreID = $genre['genreID'];
        $genreName = $genre['genreName'];
    };
    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

            $name=trim(@$_POST['name']);
            if(empty($name)){
                $errors['name']='Pole je povinné';
            }
            if($name !== $genre['genreName']){
                foreach($genre_list as $genre){
                    $genreCheck = preg_replace('/\s+/', '', $genre['bookgenre']);
                    $nameCheck = preg_replace('/\s+/', '', $name);
                    if($nameCheck === $genreCheck){
                        $errors['name']='Žánr již existuje!';
                    }
                }
            }

        if(empty($errors)){
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                #region uprava autora
            
                    $delQuery=$db->prepare('UPDATE library_genres SET name=?  WHERE genre_id=?;');
                    $delQuery->execute(array(
                        $name,
                        $genreID
                    ));
                
                    header('Location: genres.php');
                    exit();
                #endregion uprava autora
            };
        }
    }

    $pageTitle="Úprava žánru";
    include 'include/header.php';
?>      

<div class="row">
    <h2 class="col mx-3">Úprava žánru</h2>
</div>
        <div class="new_book-form pt-5">
            <form method="POST">
                <h2 class="text-center mb-4 pl-4">Upravit jméno žánru</h2>
                <div class="form-group">
                    <input id="name" type="text" class="form-control text-center font-weight-bold <?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" 
                        name="name" value="<?php echo ''.htmlspecialchars($genreName).'';?>"/>
                        <?php
                            echo (!empty($errors['name'])?'<div class="invalid-feedback">'.$errors['name'].'</div>':'');
                        ?>		
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <a href="genres.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                        <button type="submit" class="btn btn-dark col form-control ml-2">Upravit</button>
                    </div>
                </div>
            </form>
        </div>
<?php
    include 'include/footer.php';
?>