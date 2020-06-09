<?php

    require_once 'include/user.php';

    if (!empty($_SESSION['user_id'])){
        header('Location: index.php');
        exit();
    };

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare
        #region kontrola jmena
        $firstName=trim(@$_POST['firstName']);
        if(empty($firstName)){
            $errors['firstName']='Musíte zadat jméno';
        }
        #endregion kontrola jmena

        #region kontrola prijmeni
        $lastName=trim(@$_POST['lastName']);
        if(empty($lastName)){
            $errors['lastName']='Musíte zadat příjmení';
        }
        #endregion kontrola prijmeni

        #region kontrola email
        $email=trim(@$_POST['email']);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email']='Musíte zadat platný e-mail';
        }else{
            $mailQuery=$db->prepare(
                'SELECT * 
                FROM library_users
                WHERE email=:email LIMIT 1;
            ');
            $mailQuery->execute([':email'=>$email]);
            if($mailQuery->rowCount()>0){
                $errors['email']='Uživatelský email s touto adresou již existuje.';
            };
        };
        #endregion kontrola email

        #region kontrola hesla
            $checkPass1 = trim(@$_POST['password']);
            $checkPass2 = trim(@$_POST['password2']);

            if (strlen($checkPass1)<8){
                $errors['password'] = 'Vaše heslo musí obsahovat alespoň 8 znaků!';
            }
            elseif(!preg_match("#[0-9]+#",$checkPass1)) {
                $errors['password'] = 'Vaše Heslo musí obsahovat alespoň 1 číslici!';
            }
            elseif(!preg_match("#[A-Z]+#",$checkPass1)) {
                $errors['password'] = 'Vaše Heslo musí obsahovat alespoň 1 velké písmeno!';
            }
            elseif(!preg_match("#[a-z]+#",$checkPass1)) {
                $errors['password'] = 'Vaše Heslo musí obsahovat alespoň 1 malé písmeno!';
            }
            
            if($checkPass1 !== $checkPass2){
                $errors['password2']='Hesla se neshodují';
            }
        #endregion kontrola hesla

        #region nastaveni knihovnika
        if(isset($_POST['librarian'])){
            $librarian = 1;
        }else{
            $librarian = 0;
        }

        #endregion nastaveni knihovnika


        #region registrace uzivatele
        if(empty($errors)){
            $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
            $name = $firstName.' '.$lastName;
            
            $query=$db->prepare('INSERT INTO library_users (name, email, password, librarian, active) VALUES (:name, :email, :password, :librarian, 1);');
            $query->execute([
                ':name'=>$name,
                ':email'=>$email,
                ':password'=>$password,
                ':librarian'=>$librarian
            ]);
            

            $_SESSION['user_id']=$db->lastInsertId();
            $_SESSION['user_name']=$firstName.' '.$lastName;

            header('Location: index.php');
            exit();
        }
        #endregion registrace uzivatele

        #endregion zpracovani formulare
    };

    $pageTitle="Registrace nového uživatele";

    include 'include/header.php';
?>

<h2>Registrace nového uživatele</h2>

<div class="login-form">
    <form method="post">
        <h2 class="text-center">Registrace</h2>   
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input id="firstName" type="text" class="form-control<?php echo(!empty($errors['firstName']) ? ' is-invalid' : ''); ?>" name="firstName" placeholder="Jméno" required="required" value="<?php echo htmlspecialchars(@$firstName);?>">				
                <input id="lastName" type="text" class="form-control<?php echo(!empty($errors['lastName']) ? ' is-invalid' : ''); ?>" name="lastName" placeholder="Příjmení" required="required" value="<?php echo htmlspecialchars(@$lastName);?>">
                <?php
                    echo (!empty($errors['firstName'])?'<div class="invalid-feedback">'.$errors['firstName'].'</div>':'');
                    echo (!empty($errors['lastName'])?'<div class="invalid-feedback">'.$errors['lastName'].'</div>':'');
                ?>				
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <span id="envelope" class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input id="email" type="email" class="form-control<?php echo(!empty($errors['email']) ? ' is-invalid' : ''); ?>" name="email" placeholder="Email" required="required" value="<?php echo htmlspecialchars(@$email);?>"/>
                <?php
                    echo (!empty($errors['email'])?'<div class="invalid-feedback">'.$errors['email'].'</div>':'');
                ?>				
            </div>
        </div>
		<div class="form-group">
            <div class="input-group">
                <span id="password" class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control<?php echo(!empty($errors['password']) ? ' is-invalid' : ''); ?>" name="password" placeholder="Heslo" required="required">
                <?php
                    echo (!empty($errors['password'])?'<div class="invalid-feedback">'.$errors['password'].'</div>':'');
                ?>				
            </div>
        </div>
		<div class="form-group">
            <div class="input-group">
                <span id="password2" class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control<?php echo(!empty($errors['password2']) ? ' is-invalid' : ''); ?>" name="password2" placeholder="Potvrzení hesla" required="required"/>				
                <?php
                    echo (!empty($errors['password2'])?'<div class="invalid-feedback">'.$errors['password2'].'</div>':'');
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="checkbox">
                <label>
                    <span class="input-group-addon"><i class="fa fa-book"></i></span>
                    <input id="librarian" type="checkbox" name="librarian" value="<?php echo htmlspecialchars(@$librarian);?>">
                    <span class="right">Jste knihovník?</span>
                </label>
            </div>
        </div>         
        <div class="form-group">
            <button type="submit" class="btn btn-primary login-btn btn-block">Registrovat se</button>
        </div>
		<div class="or-seperator"><i>nebo se registrovat přes</i></div>
        <div class="text-center social-btn">
            <a href="#" class="btn btn-primary"><i class="fa fa-facebook"></i>&nbsp; Facebook</a>
        </div>
        <div class="or-seperator"></div>
        <div class="row">
            <a href="login.php" class="col btn btn-light">Přihlásit se</a>
            <a href="index.php" class="col btn btn-light">Zrušit</a>
        </div>
    </form>
</div> 


<?php
    include 'include/footer.php';