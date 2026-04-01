-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Apr 01, 2026 at 01:36 PM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alasnier_m2_gsb_param`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrateur`
--

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `id` char(3) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `mdp` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `administrateur`
--

INSERT INTO `administrateur` (`id`, `nom`, `mdp`) VALUES
('1', 'LeBoss', 'TheBest$147#'),
('2', 'LeChefProjet', 'NearlyTheBest$280@');

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` char(3) NOT NULL,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle`) VALUES
('CH', 'Cheveux'),
('FO', 'Forme'),
('PS', 'Protection Solaire');

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

DROP TABLE IF EXISTS `commande`;
CREATE TABLE IF NOT EXISTS `commande` (
  `id` varchar(32) NOT NULL,
  `dateCommande` date DEFAULT NULL,
  `nomPrenomClient` varchar(50) DEFAULT NULL,
  `adresseRueClient` varchar(50) DEFAULT NULL,
  `cpClient` char(5) DEFAULT NULL,
  `villeClient` varchar(50) DEFAULT NULL,
  `mailClient` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id`, `dateCommande`, `nomPrenomClient`, `adresseRueClient`, `cpClient`, `villeClient`, `mailClient`) VALUES
('1101461660', '2024-09-01', 'Dupont Jacques', '12, rue haute', '75001', 'Paris', 'dupont@wanadoo.fr'),
('1101461665', '2024-09-01', 'Durant Yves', '23, rue des ombres', '75012', 'Paris', 'durant@free.fr'),
('1101461666', '2026-03-16', 'thomas', 'jsp rue des marguoulette', '60000', 'orléans', 'patrique@proton.com');

-- --------------------------------------------------------

--
-- Table structure for table `contenir`
--

DROP TABLE IF EXISTS `contenir`;
CREATE TABLE IF NOT EXISTS `contenir` (
  `idCommande` varchar(32) NOT NULL,
  `idProduit` varchar(5) NOT NULL,
  `qt` int(11) NOT NULL,
  PRIMARY KEY (`idCommande`,`idProduit`),
  KEY `I_FK_CONTENIR_COMMANDE` (`idCommande`),
  KEY `I_FK_CONTENIR_Produit` (`idProduit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contenir`
--

INSERT INTO `contenir` (`idCommande`, `idProduit`, `qt`) VALUES
('1101461660', 'f03', 0),
('1101461660', 'p01', 0),
('1101461665', 'f05', 0),
('1101461665', 'p06', 0),
('1101461666', 'c03', 0);

-- --------------------------------------------------------

--
-- Table structure for table `marque`
--

DROP TABLE IF EXISTS `marque`;
CREATE TABLE IF NOT EXISTS `marque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `marque`
--

INSERT INTO `marque` (`id`, `nom`) VALUES
(1, 'dior'),
(2, 'caudalie'),
(3, 'klorane'),
(4, 'roc'),
(5, 'yves rocher');

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

DROP TABLE IF EXISTS `produit`;
CREATE TABLE IF NOT EXISTS `produit` (
  `id` varchar(5) NOT NULL,
  `description` char(50) DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `image` char(100) DEFAULT NULL,
  `idCategorie` char(3) DEFAULT NULL,
  `nom` varchar(50) NOT NULL,
  `marque` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `contenance` float NOT NULL,
  `unite` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `I_FK_Produit_CATEGORIE` (`idCategorie`),
  KEY `fk_unite` (`unite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id`, `description`, `prix`, `image`, `idCategorie`, `nom`, `marque`, `stock`, `contenance`, `unite`) VALUES
('c01', '          Laino Shampooing Douche au Thé Vert BIO', 4.00, 'assets/images/la-roche-posay-cicaplast-creme-pansement-40ml.jpg', 'CH', ' Laino Shampooing', 2, 18, 100, 1),
('c03', 'Weleda Kids 2in1 Shower & Shampoo Orange fruitée', 4.00, 'assets/images/weleda-kids-2in1-shower-shampoo-orange-fruitee-150-ml.jpg', 'CH', 'Weleda Kids', 1, 87, 100, 1),
('c05', 'Klorane Shampooing sec à l\'extrait d\'ortie', 6.10, 'assets/images/klorane-shampooing-sec-a-l-extrait-d-ortie-spray-150ml.png', 'CH', 'Klorane Shampooing', 1, 11, 100, 1),
('c06', 'Phytopulp mousse volume intense', 18.00, 'assets/images/phytopulp-mousse-volume-intense-200ml.jpg', 'CH', 'Phytopulp mousse', 3, 96, 100, 1),
('c07', 'Bio Beaute by Nuxe Shampooing nutritif', 8.00, 'assets/images/bio-beaute-by-nuxe-shampooing-nutritif-200ml.png', 'CH', 'Bio Beaute by Nuxe ', 1, 47, 100, 1),
('f01', 'Nuxe Men Contour des Yeux Multi-Fonctions', 12.05, 'assets/images/nuxe-men-contour-des-yeux-multi-fonctions-15ml.png', 'FO', 'Nuxe Men Contour ', 3, 46, 100, 1),
('f02', 'Tisane romon nature sommirel bio sachet 20', 5.50, 'assets/images/tisane-romon-nature-sommirel-bio-sachet-20.jpg', 'FO', 'Tisane romon nature ', 4, 88, 100, 1),
('f03', 'La Roche Posay Cicaplast crème pansement', 11.00, 'assets/images/la-roche-posay-cicaplast-creme-pansement-40ml.jpg', 'FO', 'La Roche Posay', 1, 6, 100, 1),
('f04', 'Futuro sport stabilisateur pour cheville', 26.50, 'assets/images/futuro-sport-stabilisateur-pour-cheville-deluxe-attelle-cheville.png', 'FO', 'Futuro sport stabilisateur', 2, 65, 100, 1),
('f05', 'Microlife pèse-personne électronique weegschaal', 63.00, 'assets/images/microlife-pese-personne-electronique-weegschaal-ws80.jpg', 'FO', 'Microlife pèse-personne ', 3, 7, 100, 1),
('f06', 'Melapi Miel Thym Liquide 500g', 6.50, 'assets/images/melapi-miel-thym-liquide-500g.jpg', 'FO', 'Melapi Miel Thym', 1, 42, 100, 1),
('f07', 'Meli Meliflor Pollen 200g', 8.60, 'assets/images/melapi-pollen-250g.jpg', 'FO', 'Meli Meliflor', 2, 89, 100, 1),
('p01', 'Avène solaire Spray très haute protection', 22.00, 'assets/images/avene-solaire-spray-tres-haute-protection-spf50200ml.png', 'PS', 'Avène solaire Spray', 2, 18, 100, 1),
('p02', 'Mustela Solaire Lait très haute Protection', 17.50, 'assets/images/mustela-solaire-lait-tres-haute-protection-spf50-100ml.jpg', 'PS', 'Mustela Solaire Lait ', 1, 25, 100, 1),
('p03', 'Isdin Eryfotona aAK fluid', 29.00, 'assets/images/isdin-eryfotona-aak-fluid-100-50ml.jpg', 'PS', 'Isdin Eryfotona', 4, 71, 100, 1),
('p04', 'La Roche Posay Anthélios 50+ Brume Visage', 8.75, 'assets/images/la-roche-posay-anthelios-50-brume-visage-toucher-sec-75ml.png', 'PS', 'La Roche Posay ', 3, 82, 100, 1),
('p05', 'Nuxe Sun Huile Lactée Capillaire Protectrice', 15.00, 'assets/images/nuxe-sun-huile-lactee-capillaire-protectrice-100ml.png', 'PS', 'Nuxe Sun Huile', 3, 95, 100, 1),
('p06', 'Uriage Bariésun stick lèvres SPF30 4g', 5.65, 'assets/images/uriage-bariesun-stick-levres-spf30-4g.jpg', 'PS', 'Uriage Bariésun stick', 5, 32, 100, 1),
('p07', 'Bioderma Cicabio creme SPF50+ 30ml', 13.70, 'assets/images/bioderma-cicabio-creme-spf50-30ml.png', 'PS', 'Bioderma Cicabio ', 4, 73, 100, 1);

-- --------------------------------------------------------

--
-- Table structure for table `unite`
--

DROP TABLE IF EXISTS `unite`;
CREATE TABLE IF NOT EXISTS `unite` (
  `id` int(11) NOT NULL,
  `libelle` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `unite`
--

INSERT INTO `unite` (`id`, `libelle`) VALUES
(1, 'ml'),
(2, 'cl'),
(3, 'l'),
(4, 'g'),
(5, 'kg');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contenir`
--
ALTER TABLE `contenir`
  ADD CONSTRAINT `contenir_ibfk_1` FOREIGN KEY (`idCommande`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `contenir_ibfk_2` FOREIGN KEY (`idProduit`) REFERENCES `produit` (`id`);

--
-- Constraints for table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `fk_unite` FOREIGN KEY (`unite`) REFERENCES `unite` (`id`),
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`idCategorie`) REFERENCES `categorie` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
