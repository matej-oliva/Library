<?php

    require_once 'include/user.php';
    require 'librarian_required.php';

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

    $roles_query = $db->prepare( 
        'SELECT DISTINCT library_roles.name AS roleName, library_roles.role_id AS roleID  
        FROM library_roles
        ORDER BY name ASC');

    $roles_query->execute();
    
    $roles_list = $roles_query->fetchAll(PDO::FETCH_ASSOC);
    if(empty($roles_list)){
        echo '<div class="alert alert-info">Nebyly nalezeny žádné role.</div>';
    };

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
    <h2 class="col mx-3 my-3">Změna role uživatele</h2>
</div>

    <div class="new_book-form pt-5 w-50">
        <form method="POST">
            <h2 class="text-center mb-4 pl-4">Změna role uživatele</h2>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon col"><i class="fa fa-user"></i></span>
                    <input id="name" type="text" disabled class="form-control text-center col w-75<?php echo(!empty($errors['name']) ? ' is-invalid' : ''); ?>" name="name" placeholder="název knihy" value="<?php echo htmlspecialchars(@$name);?>"/>
                    <?php
                        echo (!empty($errors['name'])?'<div class="invalid-feedback">'.$errors['name'].'</div>':'');
                    ?>			
                </div>
            </div>
            <div class="form-group">
                	<div class="input-group">
                        <span class="input-group-addon col"><i class="fa fa-user-tie"></i></span>
                        <select name="role_picker" id="role_picker" class="form-control custom-picker selectpicker col w-75" data-size="5" data-dropup-auto="false" required>
                                    <option value="<?php if(empty($_POST)){echo ''.htmlspecialchars($roleID).'';}else{echo htmlspecialchars(@$role_picker);}?>">
                                    ---Vyberte roli---
                                    </option>
                                    <?php
                                        if(!empty($roles_list)){
                                            foreach($roles_list as $role){
                                                echo '<option value="' . $role['roleID'] . '"';
                                                if ($role['roleID'] == @$_POST['roleID']) {
                                                    echo ' selected="selected" ';
                                                }
                                                echo '>' . htmlspecialchars($role['roleName']).'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                        <?php
                            echo (!empty($errors['role'])?'<div class="invalid-feedback">'.$errors['role'].'</div>':'');
                        ?>			
                    </div>
                </div>
            <div class="form-group">
                <div class="input-group">
                    <a href="users_mgmt.php" class="col btn btn-outline-secondary mr-2">Zrušit</a>
                    <button type="submit" class="btn btn-info col form-control ml-2">Změnit</button>
                </div>
            </div>
        </form>
    </div>
        
<?php
    include 'include/footer.php';
?>