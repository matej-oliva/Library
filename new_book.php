<?php
    require_once 'include/user.php';
    require_once 'librarian_required.php';

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

        $name=trim(@$_POST['name']);
        if(empty($name)){
            $errors['name']='Pole je povinné';
        }

        $author=trim(@$_POST['author']);
        if(empty($author)){
            $errors['author']='Pole je povinné';
        }

        $max_stock=trim(@$_POST['max_stock']);
        if(empty($max_stock)){
            $errors['max_stock']='Pole je povinné';
        }

        $description=trim(@$_POST['description']);
        if(empty($description)){
            $errors['description']='Pole je povinné';
        }

        #region nova kniha
        if(empty($errors)){
            
            $query=$db->prepare('INSERT INTO books (name, author, max_stock, description) VALUES (:name, :author, :max_stock, :description);');
            $query->execute([
                ':name'=>$name,
                ':author'=>$author,
                ':max_stock'=>$max_stock,
                ':description'=>$description
            ]);

            header('Location: index.php');
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
        <h2 class="text-center">Přidat knihu</h2>   
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
                <input id="author" type="text" class="form-control col w-75<?php echo(!empty($errors['author']) ? ' is-invalid' : ''); ?>" name="author" placeholder="autor knihy" value="<?php echo htmlspecialchars(@$author);?>"/>
                <?php
                    echo (!empty($errors['author'])?'<div class="invalid-feedback">'.$errors['author'].'</div>':'');
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
                <textarea id="description" type="text-area" rows="5" class="form-control col w-75<?php echo(!empty($errors['description']) ? ' is-invalid' : ''); ?>" name="description" placeholder="Popis" value="<?php echo htmlspecialchars(@$description);?>"></textarea>
                <?php
                    echo (!empty($errors['description'])?'<div class="invalid-feedback">'.$errors['description'].'</div>':'');
                ?>				
            </div>
        </div>        
        <div class="form-group">
            <button type="submit" class="btn btn-dark login-btn btn-block">Přidat</button>
        </div>
        </div>
    </form>
</div> 

<?php
    include 'include/footer.php';
?>