<?php

    require_once 'include/user.php';
    require_once 'user_required.php';

    if ($loggedUser) {
        $role = (int) $loggedUser['role_id'];
    }

    include 'include/header.php';
?>
        <h2 class="mx-4 py-3">Domovská stránka</h2>
        <div class="col w-100">
            <article class="col border border-dark my-1 pb-5 pt-2 mx-auto w-75 bg-secondary text-white">
              <div class="pl-2">
                <h6>Vítejte v aplikaci Knihovna,</h6>
                <p>Aplikace vznikla jako semestrální práce kurzu 4IZ278.</br>
                Cílem bylo vytvořit webovou aplikaci používající pro ukládání dat databázový server. 
                Aplikace běží na serveru eso.vse.cz a pro ukládání a správu dat využívá MariaDB databáze a MYSQL jazyka.
                </p>
                <p>
                    Knihovna nejdříve vyžaduje přihlášení uživatele. To je zajištěno registrací přes email nebo Facebook.
                    Po registraci je uživateli přiřazena role uživatel. Administrátor má práva na změnu rolí u uživatelů, tedy
                    ještě na knihovníka nebo dalšího administrátora. Přiřazování je řešeno na kartě Správa rolí. Administrátor 
                    má přístup do všech sekcí a má povoleny veškeré možné úpravy.
                </p>
                <p>
                    Knihovník ztrácí právo na správu rolí. Mimo to, má přístup do všech sekcí, jako administrátor. Narozdíl od 
                    uživatele tak může přidávat, upravovat a odebírat knihy, autory a žánry.
                </p>
                <p>
                    Všichni uživatelé si mohou knihy půjčovat, prodlužovat jejich výpůjční lhůtu a případně vracet. Pro půjčování knih 
                    byla zavedena následující pravidla: 
                    <ul class="list-group text-dark font-weight-bold list-group-flush">
                        <li class="list-group-item list-group-item-secondary">Vypůjčit si knihu lze pouze, pokud je na skladě knihovny.</li>
                        <li class="list-group-item list-group-item-secondary">Prodloužit výpůjční lhůtu lze pouze, pokud nebyla výpůjční lhůta překročena.</li>
                        <li class="list-group-item list-group-item-secondary">Prodloužit výpůjční lhůtu lze pouze jednou.</li>
                    </ul>
                </p>
                <p>
                    Nezávisle na tom, zda je někdo přihlášen je možné si prohlédnout seznam knih.
                </p>
                <p>
                    Aplikaci jsem dále verzoval pomocí Gitu.</br>Veřejně přístupný kód je možné si prohlédnout v mém repozitáři na <a class="btn btn-outline-light px-1 mb-2" href="https://github.com/Mr-Hyde/Library/" target="_blank">GitHubu <span class="fa fa-github"></span></a>
                </p>
              </div>

            </article>
        </div>

<?php
    include 'include/footer.php';
?>