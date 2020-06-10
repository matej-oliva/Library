<?php
//načteme připojení k databázi a inicializujeme session
require_once 'include/user.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require './facebook/vendor/autoload.php';

if (!empty($_SESSION['user_id'])) {
    //uživatel už je přihlášený, nemá smysl, aby se přihlašoval znovu
    header('Location: index.php');
    exit();
}

$errors = false;
if (!empty($_POST) && !empty($_POST['email'])) {
    #region zpracování formuláře
    $userQuery = $db->prepare('SELECT * FROM library_users WHERE email=:email LIMIT 1;');
    $userQuery->execute([
        ':email' => trim($_POST['email'])
    ]);
    if ($user = $userQuery->fetch(PDO::FETCH_ASSOC)) {
        //zadaný e-mail byl nalezen

        #region vygenerování kódu pro obnovu hesla
        $code = 'xx' . rand(100000, 993952); //rozhodně by tu mohlo být i kreativnější generování náhodného kódu :)

        //uložíme kód do databáze
        $saveQuery = $db->prepare('INSERT INTO forgotten_passwords (user_id, code) VALUES (:user, :code)');
        $saveQuery->execute([
            ':user' => $user['user_id'],
            ':code' => $code
        ]);

        //načteme uložený záznam z databáze
        $requestQuery = $db->prepare('SELECT * FROM forgotten_passwords WHERE user_id=:user AND code=:code ORDER BY forgotten_password_id DESC LIMIT 1;');
        $requestQuery->execute([
            ':user' => $user['user_id'],
            ':code' => $code
        ]);
        $request = $requestQuery->fetch(PDO::FETCH_ASSOC);

        //sestavíme odkaz pro mail
        $link = 'https://eso.vse.cz/~olim02/Knihovna/renew-password.php'; 
        $link = $link.'?user=' . $request['user_id'] . '&code=' . $request['code'] . '&request=' . $request['forgotten_password_id'];
        #endregion vygenerování kódu pro obnovu hesla

        #region poslání mailu pro obnovu hesla
        //inicializujeme PHPMailer pro poslání mailu přes sendmail
        $mailer = new PHPMailer(false);
        $mailer->isSendmail();

        //nastavení adresy příjemce a odesílatele
        $mailer->addAddress($user['email'], $user['name']); //příjemce mailu; POZOR: server eso.vse.cz umí posílat maily jen na školní e-maily!
        $mailer->setFrom('olim02@vse.cz'); 

        //nastavíme kódování a předmět e-mailu
        $mailer->CharSet = 'utf-8';
        $mailer->Subject = 'Obnova zapomenutého hesla';

        $mailer->isHTML(true);
        $mailer->Body = '<html>
                        <head><meta charset="utf-8" /></head>
                        <body>Pro obnovu hesla do Knihovny klikněte na následující odkaz: <a href="' . htmlspecialchars($link) . '">' . htmlspecialchars($link) . '</a></body>
                      </html>';
        $mailer->AltBody = 'Pro obnovu hesla do Knihovny klikněte na následující odkaz: ' . $link;

        $mailer->send();
        #endregion poslání mailu pro obnovu hesla

        //přesměrování pro potvrzení
        header('Location: forgotten-password.php?mailed=ok');
    } else {
        //zadaný e-mail nebyl nalezen
        $errors = true;
    }
    #endregion zpracování formuláře
}

//vložíme do stránek hlavičku
include 'include/header.php';

if (@$_GET['mailed'] == 'ok') {

    echo '<p>Zkontrolujte svoji e-mailovou schránku a klikněte na odkaz, který vám byl zaslán mailem.</p>';
    echo '<a href="login.php" class="btn btn-dark text-white">Zpět na přihlášení</a>';
} else {
?>

    <div class="box">
        <div class="btop">
            <p class="topic">Zapomenuté heslo</p>
        </div>
        <div class="bbot">
            <form method="post">
                <label for="email">E-mail:</label>
                <input type="email" class="py-1" name="email" id="email" required value="<?php echo htmlspecialchars(@$_POST['email']) ?>" />
                <?php
                echo ($errors ? '<div class="error">Neplatný e-mail.</div>' : '');
                ?>
                <button type="submit" class="btn btn-dark">Zaslat e-mail k obnově hesla</button>
                <a href="login.php" class="btn btn-secondary">Zrušit</a><br>
            </form>

        </div>
    </div>
<?php
}

//vložíme do stránek patičku
include 'include/footer.php';
