-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 10, 2020 at 10:29 AM
-- Server version: 10.3.22-MariaDB-log
-- PHP Version: 7.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `olim02`
--

-- --------------------------------------------------------

--
-- Table structure for table `forgotten_passwords`
--

CREATE TABLE `forgotten_passwords` (
  `forgotten_password_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Tabulka na obnovu hesla';

-- --------------------------------------------------------

--
-- Table structure for table `library_books`
--

CREATE TABLE `library_books` (
  `book_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `author` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `max_stock` int(11) NOT NULL,
  `borrowed` int(11) NOT NULL DEFAULT 0,
  `description` text COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Seznam knih';

--
-- Dumping data for table `library_books`
--

INSERT INTO `library_books` (`book_id`, `name`, `author`, `max_stock`, `borrowed`, `description`) VALUES
(1, 'Harry Potter a vězeň z Azkabanu', 'J. K. Rowling', 4, 2, 'Harry Potter a jeho kamarádi Ron a Hermiona jsou už ve třetím ročníku bradavické Školy čar a kouzel. Harry se nemohl dočkat začátku školního roku, když ale přijede do Bradavic, panuje tam napjatá atmosféra a strach. Proč? Na svobodu uprchl vězeň z Azkabanu, nebezpečný vrah Sirius Black – prý nástupce lorda Voldemorta, Pána zla. A stopa vede – kam jinam než do školy v Bradavicích.'),
(2, 'Stopařův průvodce po galaxii', 'Douglas Adams', 3, 2, 'Tato bravurní parodie na sci-fi je dnes již klasické dílo. Komplikovaný a originální příběh začíná zničením Země, která musí udělat místo nové galaktické dálnici. Hlavní hrdina příběhu, docela obyčejný pozemšťan jménem Arthur Dent, má to štěstí, že s pomocí svého přítele Forda Prefecta, údajně nezaměstnaného herce, z něhož se vyklube mimozemšťan, stopne kosmickou loď, a tak se mu podaří uniknout z místa katastrofy...'),
(3, 'Pán Prstenů: Návrat krále', 'J. R. R. Tolkien', 5, 0, 'Závěrečná část trilogie o kouzelném prstenu a osudech hobita Froda. Síly dobra se spojují a vítězí po mnoha bitvách nad zlem. Hobit Frodo plní své poslání za pomoci svého sluhy a přítele Sama. Ale i mezi hobity se vloudilo zlo a stateční malí hrdinové se s ním nakonec přes všechna úskalí dokážou vypořádat. Tento díl je doplněn letopisy, rodokmeny, a rejstříkem ke všem částem. Navazuje na titul Dvě věže.'),
(4, 'Enderova hra', 'Orson Scott Card', 6, 0, 'První kniha Enderovské série.\r\n\r\nVědeckofantastický román, který můžeme doporučit všem milovníkům dobré literatury. Román nejen o zápasu pozemské civilizace se smrtelným nebezpečím z kosmu, ale také o přátelství, bratrské lásce a nenávisti. Kniha je napsána strhujícím způsobem a patří k tomu nejlepšímu, co současná americká sci-fi produkce nabízí.');

-- --------------------------------------------------------

--
-- Table structure for table `library_loaned_books`
--

CREATE TABLE `library_loaned_books` (
  `loan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `loan_start_date` date NOT NULL,
  `loan_expire_date` date NOT NULL,
  `loan_overdue` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `library_loaned_books`
--

INSERT INTO `library_loaned_books` (`loan_id`, `user_id`, `book_id`, `loan_start_date`, `loan_expire_date`, `loan_overdue`) VALUES
(3, 14, 2, '2020-05-21', '2020-06-21', 0),
(4, 14, 2, '2020-06-08', '2020-07-08', 0),
(5, 14, 1, '2020-06-09', '2020-07-09', 0),
(6, 14, 1, '2020-06-01', '2020-07-01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `library_roles`
--

CREATE TABLE `library_roles` (
  `role_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `library_roles`
--

INSERT INTO `library_roles` (`role_id`, `name`) VALUES
(1, 'user'),
(2, 'librarian'),
(3, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `library_users`
--

CREATE TABLE `library_users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL,
  `librarian` tinyint(1) NOT NULL DEFAULT 0,
  `password` varchar(255) COLLATE utf8mb4_czech_ci NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `facebook_id` varchar(50) COLLATE utf8mb4_czech_ci NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci COMMENT='Uživatelská data';

--
-- Dumping data for table `library_users`
--

INSERT INTO `library_users` (`user_id`, `name`, `email`, `librarian`, `password`, `active`, `facebook_id`, `role_id`) VALUES
(14, 'Daniel Beran', 'dan.beran@hammerhead.com', 0, '$2y$10$wpwR/SnahgA.k0SUqb0nDuU4adrLdzxMDdgcdgiiQ63ncwMbMH2Be', 1, '', 2),
(16, 'Marie Doubková', 'mar.doubek@gmail.com', 1, '$2y$10$D2JYnJgHBnyBdd59K5XxL.d1/m/xODconACFyti.o0ljeLplEn5rq', 1, '', 1),
(18, 'Matěj Oliva', 'matej.oliva@outlook.com', 0, '', 1, '4418579778167016', 1),
(19, 'Petr Urban', 'petin262@seznam.cz', 0, '', 1, '3616546251705746', 1),
(20, 'Gabriel Pewterschmidt', 'gabe.pew@gmail.com', 0, '$2y$10$jn.u3U0ERA24Bs/ns94RaekHo7AKK00ag/03T.g3YB013CtbGFuG6', 1, '', 1),
(21, 'Eliška Baklová', 'elbak@email.cz', 0, '', 1, '1992660540866227', 1),
(22, 'Jakub Vysusil', 'jakubvysusil@seznam.cz', 0, '', 1, '4360566837294395', 1),
(23, 'Matěj Oliva', 'olim02@vse.cz', 1, '$2y$10$aX9Slhn3KQlu.i8XGOL4buk6nfudhX.qJfgPqcgvV0g9pVtXtFqs.', 1, '', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forgotten_passwords`
--
ALTER TABLE `forgotten_passwords`
  ADD PRIMARY KEY (`forgotten_password_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `library_books`
--
ALTER TABLE `library_books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `library_loaned_books`
--
ALTER TABLE `library_loaned_books`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `library_roles`
--
ALTER TABLE `library_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `library_users`
--
ALTER TABLE `library_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forgotten_passwords`
--
ALTER TABLE `forgotten_passwords`
  MODIFY `forgotten_password_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `library_books`
--
ALTER TABLE `library_books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `library_loaned_books`
--
ALTER TABLE `library_loaned_books`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `library_users`
--
ALTER TABLE `library_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `library_loaned_books`
--
ALTER TABLE `library_loaned_books`
  ADD CONSTRAINT `library_loaned_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `library_books` (`book_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `library_loaned_books_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `library_users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
