<?php
    require_once 'include/user.php';
    include 'include/header.php';
    require_once 'librarian_required.php';
?>

<div class="login-form">
    <form method="post">
        <h2 class="text-center">Přidat knihu</h2>   
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-book"></i></span>
                <input id="name" type="text" class="form-control col w-75" name="name" placeholder="název knihy" required="required"/>			
            </div>
        </div>
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-user"></i></span>
                <input id="author" type="text" class="form-control col w-75" name="author" placeholder="autor knihy" required="required"/>			
            </div>
        </div>
        <div class="form-group">
        	<div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-calculator"></i></span>
                <input id="email" type="text" class="form-control col w-75" name="email" placeholder="e-mail" required="required"/>			
            </div>
        </div>
    	<div class="form-group">
            <div class="input-group">
                <span class="input-group-addon col"><i class="fa fa-file-text-o"></i></span>
                <input type="text" class="form-control col w-75" name="password" placeholder="Heslo" required="required">				
            </div>
        </div>        
        <div class="form-group">
            <button type="submit" class="btn btn-primary login-btn btn-block">Přihlásit se</button>
        </div>
        </div>
    </form>
</div> 

<?php
    include 'include/footer.php';
?>