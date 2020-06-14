<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Library Webapp" />
        <meta name="author" content="Matěj Oliva" />
        <meta name="keywords"
            content="knihovna, aplikace, správa výpůjček knih, knihy, dokumenty, půjčení" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo (!empty($pageTitle)?$pageTitle.' - ':'')?>Knihovna</title>
        <script src="https://kit.fontawesome.com/d4998154c5.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="shortcut icon" href="./assets/img/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./assets/css/main.css">
    </head>
    <body>
        <script>
          window.fbAsyncInit = function() {
            FB.init({
              appId      : '{553606428650853}',
              cookie     : true,
              xfbml      : true,
              version    : '{v7.0}'
            });

            FB.AppEvents.logPageView();   

          };
      
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = "https://connect.facebook.net/en_US/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
        </script>
        <header class="container bg-dark">
          <div class="row">
            <h1 class="col text-white py-4 px-2 ml-4"><a class="text-white no_underline" href="./index.php">Knihovna</a></h1>
            <?php
            echo '<div class="col py-4 px-2 text-right">';
            if(!empty($_SESSION['user_id'])){
              echo '<div>';
              echo '<strong class="text-white">'.htmlspecialchars($_SESSION['user_name']).'</strong>';
              echo '</div>';
              echo '<div>';
              echo '<a href="logout.php" class="text-white">odhlásit se</a>';
              echo '</div>';
            }else{
                echo '<a href="login.php" class="text-white">přihlásit se</a>';
            }
            echo '</div>';
            ?>
          </div>
          <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex justify-content-around">
                  <a id="nav-book-list" class="btn btn-light px-4" href="book_list.php">
                    <span class="fa fa-book"></span>
                    Knihy
                  </a>
                  <?php
                    if(!empty($_SESSION['user_id'])){
                      echo '<a id="nav-loans" class="btn btn-light px-4" href="./loaned_books.php">
                      <span class="fa fa-book-reader"></span>
                      Vypůjčené knihy
                    </a>
                    <a id="nav-authors" class="btn btn-light px-4" href="./authors.php">
                      <span class="fa fa-feather"></span>
                      Autoři
                    </a>
                    <a id="nav-genres" class="btn btn-light px-4" href="./genres.php">
                      <span class="fa fa-hat-cowboy"></span>
                      Žánry
                    </a>
                    <a id="nav-profile" class="btn btn-light px-4" href="./profile.php">
                      <span class="fa fa-user"></span>
                      Profil
                    </a>
                    ';
                    };
                  ?> 
          </nav>
        </header>
        <main class="container px-0">
