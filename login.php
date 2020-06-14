<?php
    require_once 'include/user.php';
    require_once 'include/facebook.php';

    if(!empty($_SESSION['user_id'])){
        header('Location: index.php');
        exit();
    }

    $errors = false;
    if(!empty($_POST)){
        $userQuery = $db->prepare('SELECT * FROM library_users WHERE email=:email LIMIT 1;');
        $userQuery -> execute([
            ':email'=>trim($_POST['email'])
        ]);
        if($user=$userQuery->fetch(PDO::FETCH_ASSOC)){
            if(password_verify($_POST['password'],$user['password'])){
                $_SESSION['user_id']=$user['user_id'];
                $_SESSION['user_name']=$user['name'];

                //smažeme požadavky na obnovu hesla
                $forgottenDeleteQuery = $db->prepare('DELETE FROM forgotten_passwords WHERE user_id=:user;');
                $forgottenDeleteQuery->execute([':user' => $user['user_id']]);

                header('Location: index.php');
                exit();
            }else{
                $errors=true;
            };
        }else{
            $errors=true;
        };
    
    }
    $fbHelper = $fb->getRedirectLoginHelper();
    $permissions = ['email'];
    $callbackUrl = htmlspecialchars('https://eso.vse.cz/~olim02/Knihovna/fb-callback.php');

    $fbLoginUrl = $fbHelper->getLoginUrl($callbackUrl, $permissions);

    $pageTitle='Přihlášení';
    include 'include/header.php';
?>

<h2>Přihlášení uživatele</h2>

<div class="login-form">
    <form method="post">
        <h2 class="text-center">Přihlásit se</h2>   
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input id="email" type="email" class="form-control<?php echo($errors ? ' is-invalid' : ''); ?>" name="email" placeholder="e-mail" required="required" value="<?php echo htmlspecialchars(@$_POST['email']) ?>"/>
                <?php
                    echo ($errors ? '<div class="invalid-feedback">Neplatná kombinace přihlašovacího e-mailu a hesla.</div>':'');
                ?>				
            </div>
        </div>
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" class="form-control" name="password" placeholder="Heslo" required="required">				
            </div>
        </div>        
        <div class="form-group">
            <button type="submit" class="btn btn-primary login-btn btn-block">Přihlásit se</button>
        </div>
        <div class="clearfix">
            <a href="forgotten-password.php" class="pull-right">Zapomenuté heslo?</a>
        </div>
    	<div class="or-seperator"><i>nebo se přihlaste přes</i></div>
        <div class="text-center social-btn">
            <a href="<?php echo $fbLoginUrl; ?>" class="btn btn-primary"><i class="fa fa-facebook"></i>&nbsp; Facebook</a>
        </div>
    </form>
    <p class="text-center text-muted small">Nemáte ještě účet? <a href="registration.php">Zaregistrujte se zde!</a></p>
</div> 

<?php

    include 'include/footer.php';