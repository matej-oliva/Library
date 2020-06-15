<?php

    require_once 'include/user.php';
    require 'admin_required.php';

    $userID = $_GET['userID'];
    
    $query = $db->prepare( 
        'SELECT library_users.name as userName, library_users.email as userEmail
        FROM library_users
        WHERE user_id = '.$userID.'');
    
    $query->execute();
    
    $userInfo = $query->fetch();
    if(empty($userInfo)){
        echo '<div class="alert alert-info">Uživatel nebyl nalezen.</div>';
    }else{
        $name = $userInfo['userName'];
        $email = $userInfo['userEmail'];
    };

    $allUsersQuery = $db->prepare( 
        'SELECT library_users.name as userName, library_users.email as email
        FROM library_users');

    $allUsersQuery->execute();
    $user_list = $allUsersQuery->fetchAll(PDO::FETCH_ASSOC);

    $errors=[];
    if(!empty($_POST)){
        #region zpracovani formulare

            $name=trim(@$_POST['name']);
            if(empty($name)){
                $errors['name']='Pole je povinné';
            }

            $email=trim(@$_POST['email']);
            if(empty($email)){
                $errors['email']='Pole je povinné';
            }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors['email']='Musíte zadat platný e-mail';
            }

            if($email !== $userInfo['userEmail']){
                foreach($user_list as $user){
                    $userCheck = preg_replace('/\s+/', '', $user['email']);
                    $emailCheck = preg_replace('/\s+/', '', $email);
                    if($userCheck === $emailCheck){
                        $errors['email']='Email již existuje!';
                    }
                }
            }

        if(empty($errors)){
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                #region uprava autora
            
                    $delQuery=$db->prepare('UPDATE library_users SET name=?, email=?  WHERE user_id=?;');
                    $delQuery->execute(array(
                        $name,
                        $email,
                        $userID
                    ));
                    header('Location: user_mgmt.php');
                    exit();
                #endregion uprava autora
            };
        }
    }

    $pageTitle="Úprava uživatele";
    include 'include/header.php';
?>      

<div class="row">
    <h2 class="col mx-3 my-3">Změna údajů uživatele</h2>
</div>

    <div class="new_book-form pt-5 w-50">
        <form method="POST">
            <h2 class="text-center mb-4 pl-4">Osobní údaje uživatele</h2>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon col"><i class="fa fa-user"></i></span>
                    <input id="name" type="text" class="form-control text-center col w-75<?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" name="name" placeholder="název knihy" value="<?php echo htmlspecialchars(@$name);?>"/>
                    <?php
                        echo (!empty($errors['name'])?'<div class="invalid-feedback">'.$errors['name'].'</div>':'');
                    ?>			
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon col"><i class="fa fa-envelope"></i></span>
                    <input id="email" type="email" class="form-control text-center col w-75<?php echo(!empty($errors['email']) ? ' is-invalid' : ''); ?>" name="email" placeholder="e-mail" value="<?php echo htmlspecialchars(@$email) ?>"/>			
                    <?php
                        echo (!empty($errors['email'])?'<div class="invalid-feedback">'.$errors['email'].'</div>':'');
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <a href="user_mgmt.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                    <button type="submit" class="btn btn-dark col form-control ml-2">Upravit</button>
                </div>
            </div>
        </form>
    </div>
        
<?php
    include 'include/footer.php';
?>