<?php
    $pageTitle='Přihlášení';
    include 'include/header.php';
?>

<div class="login-form">
    <form action="/examples/actions/confirmation.php" method="post">
        <h2 class="text-center">Přihlásit se</h2>   
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" class="form-control" name="username" placeholder="e-mail" required="required">				
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
            <a href="#" class="pull-right">Zapomenuté heslo?</a>
        </div>
    	<div class="or-seperator"><i>nebo</i></div>
        <div class="text-center social-btn">
            <a href="#" class="btn btn-primary"><i class="fa fa-facebook"></i>&nbsp; Facebook</a>
        </div>
    </form>
    <p class="text-center text-muted small">Nemáte ještě účet? <a href="#">Přihlaste se zde!</a></p>
</div> 

<?php

    include 'include/footer.php';
?>