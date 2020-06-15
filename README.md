# Library

Aplikace vznikla jako semestrální práce kurzu 4IZ278.
Cílem bylo vytvořit webovou aplikaci používající pro ukládání dat databázový server. Aplikace běží na serveru eso.vse.cz a pro ukládání a správu dat využívá MariaDB databáze a MYSQL jazyka.

Knihovna nejdříve vyžaduje přihlášení uživatele. To je zajištěno registrací přes email nebo Facebook. Po registraci je uživateli přiřazena role uživatel. Administrátor má práva na změnu rolí u uživatelů, tedy ještě na knihovníka nebo dalšího administrátora. Přiřazování je řešeno na kartě Správa rolí. Administrátor má přístup do všech sekcí a má povoleny veškeré možné úpravy.

Knihovník ztrácí právo na správu rolí. Mimo to, má přístup do všech sekcí, jako administrátor. Narozdíl od uživatele tak může přidávat, upravovat a odebírat knihy, autory a žánry.

Všichni uživatelé si mohou knihy půjčovat, prodlužovat jejich výpůjční lhůtu a případně vracet. Pro půjčování knih byla zavedena následující pravidla:

Vypůjčit si knihu lze pouze, pokud je na skladě knihovny.
Prodloužit výpůjční lhůtu lze pouze, pokud nebyla výpůjční lhůta překročena.
Prodloužit výpůjční lhůtu lze pouze jednou.
Nezávisle na tom, zda je někdo přihlášen je možné si prohlédnout seznam knih.
