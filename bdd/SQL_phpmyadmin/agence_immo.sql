-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 22 Mai 2014 à 17:34
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `agence_immo`
--
CREATE DATABASE IF NOT EXISTS `agence_immo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `agence_immo`;

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE IF NOT EXISTS `adresse` (
  `id_adresse` int(11) NOT NULL AUTO_INCREMENT,
  `code_postal` varchar(25) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `rue` varchar(255) DEFAULT NULL,
  `numero_rue` int(11) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_adresse`),
  KEY `FK_Adresse_id_departement` (`id_departement`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Contenu de la table `adresse`
--

INSERT INTO `adresse` (`id_adresse`, `code_postal`, `ville`, `rue`, `numero_rue`, `id_departement`) VALUES
(1, '02600', 'Villers cotterets', 'rue Alexandre Dumas', 22, 2),
(2, '75015', 'Paris', 'rue de la Vilette', 23, 74),
(3, '75018', 'Paris', 'Rue de barbès', 17, 74),
(4, '02600', 'Soissons', 'rue de l''église', 10, 2),
(5, '94300', 'Vincennes', 'rue de Paris', 2, 93),
(6, '59300', 'Lille', 'rue de la liberté', 75, 58),
(7, '06200', 'Nice', 'rue de la Joconde', 3, 6),
(8, '93100', 'Montreuil', 'rue de la Soif', 65, 92),
(9, '35200', 'Rennes', 'rue de Mitterand', 6, 34),
(10, '94300', 'Vincennes', 'Rue Joseph Gaillard', 67, 93),
(11, '75000', 'Paris', 'rue du php', 20, 74),
(12, '75015', 'Paris', 'rue du php', 20, 74),
(13, '94300', 'Paris', 'rue du php', 15, 16),
(14, '75000', 'vincennes', 'rue de la joconde', 20, 4),
(15, '94300', 'vincennes', 'rue de la soif', 3, 3),
(16, '94300', 'Paris', 'rue de la soif', 15, 4),
(17, '06200', 'vincennes', 'rue de la soif', 20, 15);

-- --------------------------------------------------------

--
-- Structure de la table `agence_immobiliere`
--

CREATE TABLE IF NOT EXISTS `agence_immobiliere` (
  `id_agence_immobiliere` int(11) NOT NULL AUTO_INCREMENT,
  `nom_agence_immobiliere` varchar(255) DEFAULT NULL,
  `capital_agence_immobiliere` double DEFAULT NULL,
  `mail_agence_immobiliere` varchar(255) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  `id_photo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_agence_immobiliere`),
  KEY `FK_Agence_immobiliere_id_adresse` (`id_adresse`),
  KEY `FK_Agence_immobiliere_id_photo` (`id_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `agence_immobiliere`
--

INSERT INTO `agence_immobiliere` (`id_agence_immobiliere`, `nom_agence_immobiliere`, `capital_agence_immobiliere`, `mail_agence_immobiliere`, `id_adresse`, `id_photo`) VALUES
(1, 'Fake Agency', 300000, 'contact@agence.com', 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `appartement`
--

CREATE TABLE IF NOT EXISTS `appartement` (
  `etage` int(11) DEFAULT NULL,
  `ascenseur` tinyint(1) DEFAULT NULL,
  `numero_appartement` int(11) DEFAULT NULL,
  `id_bien_immobilier` int(11) NOT NULL,
  PRIMARY KEY (`id_bien_immobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `appartement`
--

INSERT INTO `appartement` (`etage`, `ascenseur`, `numero_appartement`, `id_bien_immobilier`) VALUES
(3, 1, 2, 2),
(1, 0, 6, 6),
(1, NULL, NULL, 35);

-- --------------------------------------------------------

--
-- Structure de la table `bien_immobilier`
--

CREATE TABLE IF NOT EXISTS `bien_immobilier` (
  `id_bien_immobilier` int(11) NOT NULL AUTO_INCREMENT,
  `prix` double DEFAULT NULL,
  `superficie` int(11) DEFAULT NULL,
  `nb_pieces` int(11) DEFAULT NULL,
  `descriptif` varchar(255) DEFAULT NULL,
  `parking` tinyint(1) DEFAULT NULL,
  `nb_etages` int(11) DEFAULT NULL,
  `id_personne_locataire` int(11) DEFAULT NULL,
  `id_personne_proprio` int(11) DEFAULT NULL,
  `id_personne_gest` int(11) DEFAULT NULL,
  `id_agence_vendeur` int(11) DEFAULT NULL,
  `id_agence_loueur` int(11) DEFAULT NULL,
  `id_type_chauffage` int(11) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  `id_gaz` int(11) DEFAULT NULL,
  `id_consommation_energetique` int(11) DEFAULT NULL,
  `date_parution` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_bien_immobilier`),
  KEY `FK_Bien_immobilier_id_personne_locataire` (`id_personne_locataire`),
  KEY `FK_Bien_immobilier_id_personne_proprio` (`id_personne_proprio`),
  KEY `FK_Bien_immobilier_id_personne_gest` (`id_personne_gest`),
  KEY `FK_Bien_immobilier_id_agence_vendeur` (`id_agence_vendeur`),
  KEY `FK_Bien_immobilier_id_agence_loueur` (`id_agence_loueur`),
  KEY `FK_Bien_immobilier_id_type_chauffage` (`id_type_chauffage`),
  KEY `FK_Bien_immobilier_id_adresse` (`id_adresse`),
  KEY `FK_Bien_immobilier_id_gaz` (`id_gaz`),
  KEY `FK_Bien_immobilier_id_consommation_energetique` (`id_consommation_energetique`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Contenu de la table `bien_immobilier`
--

INSERT INTO `bien_immobilier` (`id_bien_immobilier`, `prix`, `superficie`, `nb_pieces`, `descriptif`, `parking`, `nb_etages`, `id_personne_locataire`, `id_personne_proprio`, `id_personne_gest`, `id_agence_vendeur`, `id_agence_loueur`, `id_type_chauffage`, `id_adresse`, `id_gaz`, `id_consommation_energetique`, `date_parution`) VALUES
(1, 450000, 450, 6, 'Belle demeure :)', 0, 6, 10, 11, 8, NULL, NULL, 3, 1, 3, 5, '2014-04-07 22:00:00'),
(2, 850, 26, 2, 'Location donnant sur un jardin. Proche du métro barbès et de salles de sports. Voisins conviviaux. Bien non fumeurs. Résidence très propre et protégé par vidéo surveillance.', 0, 0, NULL, 11, 12, NULL, NULL, 3, 3, 4, 2, '2014-04-24 22:49:44'),
(3, 3000, 120, 8, 'Petite maison situé dans l''Aisne, elle vous apportera tout le bonheur qu''il vous faut. Idéal pour une petite famille.', 1, 1, NULL, NULL, 12, NULL, 1, 3, 4, 2, 2, '2014-04-01 18:34:11'),
(4, 200000, 1500, 23, 'Immeuble idéal pour l''implentation d''une petite entreprise.', 1, 4, NULL, NULL, 12, 1, NULL, 2, 5, 6, 4, '2014-04-01 18:38:03'),
(5, 300, 12, 1, 'Garage à la location. Idéal pour les berlines, motos, et quads.', 0, 0, NULL, NULL, 12, NULL, 1, 5, 4, 5, NULL, '2014-04-22 18:38:03'),
(6, 450, 21, 3, 'Petit studio lumineux, très agréable pour les étudiants', 0, 0, NULL, 11, 12, NULL, NULL, 4, 6, 6, 7, '2014-04-22 18:40:20'),
(7, 1600, 162, 9, 'Maison très agréable à vivre en communauté.', 1, 2, NULL, 11, 12, NULL, NULL, 1, 7, 4, 1, '2014-02-22 19:45:31'),
(8, 5000, 500, 12, 'Immeuble en location parfait pour le début d''une TPE en région parisienne.', 1, 3, NULL, NULL, 12, NULL, 1, 1, 8, 5, 5, '2014-04-22 18:47:45'),
(9, 7000, 22, 1, 'Grand garage utile pour tout le monde. Pratique pour les grosses bérlines.', 0, 0, NULL, NULL, 12, 1, NULL, NULL, 9, 7, 1, '2013-09-22 18:49:54'),
(34, 2555555555, 120000, 5, '#test1', 1, NULL, NULL, NULL, 12, NULL, 1, NULL, 14, NULL, NULL, '2014-05-22 17:21:22'),
(35, 2555555555, 2147483647, 65423, '#test 2', 1, NULL, NULL, 11, 12, NULL, NULL, 3, 15, 3, 3, '2014-05-22 17:27:47'),
(36, 100000, 1000, 2, '#test3', 1, NULL, NULL, NULL, 12, 1, NULL, 3, 16, 4, NULL, '2014-05-22 17:30:59'),
(37, 2, 5, 3, '#test alex', 1, NULL, NULL, 11, 8, NULL, NULL, NULL, 17, 1, 2, '2014-05-22 17:32:56');

-- --------------------------------------------------------

--
-- Structure de la table `consommation_energetique_classe`
--

CREATE TABLE IF NOT EXISTS `consommation_energetique_classe` (
  `id_consommation_energetique` int(11) NOT NULL AUTO_INCREMENT,
  `nom_consommation_energetique` varchar(25) DEFAULT NULL,
  `conso_kilowatt_an_mcarre_mini` int(11) DEFAULT NULL,
  `conso_kilowatt_an_mcarre_maxi` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_consommation_energetique`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `consommation_energetique_classe`
--

INSERT INTO `consommation_energetique_classe` (`id_consommation_energetique`, `nom_consommation_energetique`, `conso_kilowatt_an_mcarre_mini`, `conso_kilowatt_an_mcarre_maxi`) VALUES
(1, 'A', 0, 50),
(2, 'B', 51, 90),
(3, 'C', 91, 151),
(4, 'D', 151, 230),
(5, 'E', 231, 330),
(6, 'F', 331, 451),
(7, 'G', 451, 1000);

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE IF NOT EXISTS `departement` (
  `id_departement` int(11) NOT NULL AUTO_INCREMENT,
  `code_departement` char(2) DEFAULT NULL,
  `nom_departement` varchar(255) DEFAULT NULL,
  `id_region` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_departement`),
  KEY `FK_Departement_id_region` (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=97 ;

--
-- Contenu de la table `departement`
--

INSERT INTO `departement` (`id_departement`, `code_departement`, `nom_departement`, `id_region`) VALUES
(1, '01', 'Ain', 22),
(2, '02', 'Aisne', 20),
(3, '03', 'Allier', 3),
(4, '04', 'Alpes de haute provence', 18),
(5, '05', 'Hautes alpes', 18),
(6, '06', 'Alpes maritimes', 18),
(7, '07', 'Ardèche', 22),
(8, '08', 'Ardennes', 8),
(9, '09', 'Ariège', 16),
(10, '10', 'Aube', 8),
(11, '11', 'Aude', 13),
(12, '12', 'Aveyron', 16),
(13, '13', 'Bouches du rhône', 18),
(14, '14', 'Calvados', 4),
(15, '15', 'Cantal', 3),
(16, '16', 'Charente', 21),
(17, '17', 'Charente maritime', 21),
(18, '18', 'Cher', 7),
(19, '19', 'Corrèze', 14),
(20, '21', 'Côte d''or', 5),
(21, '22', 'Côtes d''Armor', 6),
(22, '23', 'Creuse', 14),
(23, '24', 'Dordogne', 2),
(24, '25', 'Doubs', 10),
(25, '26', 'Drôme', 22),
(26, '27', 'Eure', 11),
(27, '28', 'Eure et Loir', 7),
(28, '29', 'Finistère', 6),
(29, '30', 'Gard', 13),
(30, '31', 'Haute garonne', 16),
(31, '32', 'Gers', 16),
(32, '33', 'Gironde', 2),
(33, '34', 'Hérault', 13),
(34, '35', 'Ile et Vilaine', 6),
(35, '36', 'Indre', 7),
(36, '37', 'Indre et Loire', 7),
(37, '38', 'Isère', 22),
(38, '39', 'Jura', 10),
(39, '40', 'Landes', 2),
(40, '41', 'Loir et Cher', 7),
(41, '42', 'Loire', 22),
(42, '43', 'Haute Loire', 3),
(43, '44', 'Loire Atlantique', 19),
(44, '45', 'Loiret', 7),
(45, '46', 'Lot', 16),
(46, '47', 'Lot et Garonne', 2),
(47, '48', 'Lozère', 13),
(48, '49', 'Maine et Loire', 19),
(49, '50', 'Manche', 4),
(50, '51', 'Marne', 8),
(51, '52', 'Haute Marne', 8),
(52, '53', 'Mayenne', 19),
(53, '54', 'Meurthe et Moselle', 15),
(54, '55', 'Meuse', 15),
(55, '56', 'Morbihan', 6),
(56, '57', 'Moselle', 15),
(57, '58', 'Nièvre', 5),
(58, '59', 'Nord', 17),
(59, '60', 'Oise', 20),
(60, '61', 'Orne', 4),
(61, '62', 'Pas de Calais', 17),
(62, '63', 'Puy de Dôme', 3),
(63, '64', 'Pyrénées Atlantiques', 2),
(64, '65', 'Hautes Pyrénées', 16),
(65, '66', 'Pyrénées Orientales', 13),
(66, '67', 'Bas Rhin', 1),
(67, '68', 'Haut Rhin', 1),
(68, '69', 'Rhône', 22),
(69, '70', 'Haute Saône', 10),
(70, '71', 'Saône et Loire', 5),
(71, '72', 'Sarthe', 19),
(72, '73', 'Savoie', 22),
(73, '74', 'Haute Savoie', 22),
(74, '75', 'Paris', 12),
(75, '76', 'Seine Maritime', 11),
(76, '77', 'Seine et Marne', 12),
(77, '78', 'Yvelines', 12),
(78, '79', 'Deux Sèvres', 21),
(79, '80', 'Somme', 20),
(80, '81', 'Tarn', 16),
(81, '82', 'Tarn et Garonne', 16),
(82, '83', 'Var', 18),
(83, '84', 'Vaucluse', 18),
(84, '85', 'Vendée', 19),
(85, '86', 'Vienne', 21),
(86, '87', 'Haute Vienne', 14),
(87, '88', 'Vosges', 15),
(88, '89', 'Yonne', 5),
(89, '90', 'Territoire de Belfort', 10),
(90, '91', 'Essonne', 12),
(91, '92', 'Hauts de Seine', 12),
(92, '93', 'Seine Saint Denis', 12),
(93, '94', 'Val de Marne', 12),
(94, '95', 'Val d''Oise', 12),
(95, '2A', 'Corse du Sud', 9),
(96, '2B', 'Haute Corse', 9);

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE IF NOT EXISTS `employe` (
  `id_personne` int(11) NOT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `employe`
--

INSERT INTO `employe` (`id_personne`) VALUES
(2),
(7),
(8),
(12);

-- --------------------------------------------------------

--
-- Structure de la table `garage`
--

CREATE TABLE IF NOT EXISTS `garage` (
  `id_bien_immobilier` int(11) NOT NULL,
  PRIMARY KEY (`id_bien_immobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `garage`
--

INSERT INTO `garage` (`id_bien_immobilier`) VALUES
(5),
(9);

-- --------------------------------------------------------

--
-- Structure de la table `gaz_a_effet_de_serre_classe`
--

CREATE TABLE IF NOT EXISTS `gaz_a_effet_de_serre_classe` (
  `id_gaz` int(11) NOT NULL AUTO_INCREMENT,
  `nom_gaz` varchar(255) DEFAULT NULL,
  `emission_kilo_co2_an_mcarre_mini` int(11) DEFAULT NULL,
  `emission_kilo_co2_an_mcarre_maxi` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_gaz`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `gaz_a_effet_de_serre_classe`
--

INSERT INTO `gaz_a_effet_de_serre_classe` (`id_gaz`, `nom_gaz`, `emission_kilo_co2_an_mcarre_mini`, `emission_kilo_co2_an_mcarre_maxi`) VALUES
(1, 'A', 0, 6),
(2, 'B', 6, 10),
(3, 'C', 11, 20),
(4, 'D', 21, 35),
(5, 'E', 36, 55),
(6, 'F', 56, 80),
(7, 'G', 80, 1000);

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE IF NOT EXISTS `historique` (
  `id_historique` int(11) NOT NULL AUTO_INCREMENT,
  `nom_action` varchar(255) NOT NULL,
  `prix_action` double DEFAULT NULL,
  `descriptif_action` varchar(255) DEFAULT NULL,
  `id_bien_immobilier` int(11) DEFAULT NULL,
  `date_historique` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_historique`),
  KEY `FK_Historique_id_bien_immobilier` (`id_bien_immobilier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `historique`
--

INSERT INTO `historique` (`id_historique`, `nom_action`, `prix_action`, `descriptif_action`, `id_bien_immobilier`, `date_historique`) VALUES
(1, 'achat fournitures peintures', 3000, 'couches d''impressions satinées pour le salon', 2, '2014-04-29 22:00:00'),
(2, 'achat ciment pour la terrasse', 460, 'remise à neuf des joints pour la terasse', 2, '2014-04-25 16:17:12'),
(3, 'paiement intervention chauffagiste', 1000, 'remise à jour de la chaudière', 2, '2014-04-25 16:31:16'),
(4, 'changement de fenêtre', 150, 'suite à une fusillade avec la police, la fenêtre du salon a été détruite', 2, '2014-04-25 16:31:16'),
(5, 'aide au logement', 500, 'bourse versée par l''état', 2, '2014-04-28 22:55:24'),
(6, 'aide régional', 6000, 'aide aux nouveaux proprietaires', 2, '2014-04-29 16:50:09'),
(7, 'assurance de la mairie', 700, 'accident causé par le mairie', 2, '2014-04-18 22:00:00'),
(8, 'achat fourniture papier peint', 150, 'rafraichissement salon ', 2, '2014-05-01 21:50:12'),
(9, 'achat parquet', 2000, 'parquet pour le salon', 2, '2014-05-01 21:50:12'),
(10, 'achat luminaire', 160, 'luminaires pour la cuisine', 2, '2014-05-01 21:51:18'),
(11, 'achat mobilier', 6000, 'mobilier pour la cuisine', 1, '2014-05-01 21:51:18'),
(12, 'loyer mai', 1500, 'loyer mai', 1, '2014-05-21 14:10:33'),
(13, 'loyer aout', 2000, 'loyer aout', 1, '2014-05-21 15:48:25');

-- --------------------------------------------------------

--
-- Structure de la table `historique_depense`
--

CREATE TABLE IF NOT EXISTS `historique_depense` (
  `id_historique` int(11) NOT NULL,
  `id_personne_impute` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_historique`),
  KEY `FK_Historique_depense_id_personne` (`id_personne_impute`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `historique_depense`
--

INSERT INTO `historique_depense` (`id_historique`, `id_personne_impute`) VALUES
(1, NULL),
(2, NULL),
(3, NULL),
(4, NULL),
(8, NULL),
(9, NULL),
(10, NULL),
(11, 10);

-- --------------------------------------------------------

--
-- Structure de la table `historique_entree`
--

CREATE TABLE IF NOT EXISTS `historique_entree` (
  `id_historique` int(11) NOT NULL,
  `id_paiement` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_historique`),
  KEY `FK_Historique_entree_id_paiement` (`id_paiement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `historique_entree`
--

INSERT INTO `historique_entree` (`id_historique`, `id_paiement`) VALUES
(5, NULL),
(6, NULL),
(7, NULL),
(12, 1),
(13, 1);

-- --------------------------------------------------------

--
-- Structure de la table `illustrer`
--

CREATE TABLE IF NOT EXISTS `illustrer` (
  `id_bien_immobilier` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL,
  PRIMARY KEY (`id_bien_immobilier`,`id_photo`),
  KEY `FK_illustrer_id_photo` (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `immeuble`
--

CREATE TABLE IF NOT EXISTS `immeuble` (
  `id_bien_immobilier` int(11) NOT NULL,
  PRIMARY KEY (`id_bien_immobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `immeuble`
--

INSERT INTO `immeuble` (`id_bien_immobilier`) VALUES
(4),
(8),
(36);

-- --------------------------------------------------------

--
-- Structure de la table `locataire`
--

CREATE TABLE IF NOT EXISTS `locataire` (
  `id_personne` int(11) NOT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `locataire`
--

INSERT INTO `locataire` (`id_personne`) VALUES
(10);

-- --------------------------------------------------------

--
-- Structure de la table `maison`
--

CREATE TABLE IF NOT EXISTS `maison` (
  `superficie_jardin` int(11) DEFAULT NULL,
  `id_bien_immobilier` int(11) NOT NULL,
  PRIMARY KEY (`id_bien_immobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `maison`
--

INSERT INTO `maison` (`superficie_jardin`, `id_bien_immobilier`) VALUES
(25, 1),
(60, 3),
(40, 7),
(4, 34),
(1, 37);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `date_message` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `contenu_message` varchar(5000) DEFAULT NULL,
  `traite` tinyint(1) DEFAULT NULL,
  `id_auteur` int(11) DEFAULT NULL,
  `id_destinataire` int(11) DEFAULT NULL,
  `id_bien_immobilier` int(11) NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `FK_Message_id_auteur` (`id_auteur`),
  KEY `FK_Message_id_destinataire` (`id_destinataire`),
  KEY `FK_Message_id_bien_immobilier` (`id_bien_immobilier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id_message`, `date_message`, `contenu_message`, `traite`, `id_auteur`, `id_destinataire`, `id_bien_immobilier`) VALUES
(1, '2014-05-18 01:10:47', 'Test bonjour!', 1, 12, 11, 2),
(2, '2014-05-18 17:36:37', 'Réponse du test bonjour :)', 1, 11, 12, 2),
(4, '2014-05-15 18:12:49', 'Bonjour Bill, pour visualiser tes dépenses, il te suffit de cliquer sur le lien prévu à cet effet, dans le menu situé à gauche de la page : "historiques des dépenses". Pour toutes informations supplémentaires, n''hésite pas à me recontacter ;)', 1, 12, 11, 2),
(5, '2014-05-18 20:22:07', 'Hey je teste pour la première fois l''interface de message avec toi Matthieu :)', 1, 11, 12, 2),
(6, '2014-05-18 20:22:31', 'C''est cool ça marche du tonnerre :)', 1, 11, 12, 2),
(9, '2014-05-18 20:45:30', 'Hello les amis :)', 1, 10, 8, 1),
(10, '2014-05-18 20:48:38', 'Votre service de messagerie fonctionne t-il avec des injections SQL?'' , 1);\r\nSELECT * FROM bien_immobilier;#', 1, 10, 8, 1),
(11, '2014-05-18 20:48:53', 'Effectivement.. Bien joué!', 1, 10, 8, 1),
(12, '2014-05-18 22:13:59', 'Bonjour je teste le raccourci jquery Ctrl+entree ..', 1, 11, 8, 1),
(13, '2014-05-18 22:15:23', 'Cela fonctionne à merveille!', 1, 11, 8, 1),
(14, '2014-05-18 22:17:00', 'L''ancre est peut être trop basse ?', 1, 11, 8, 1),
(15, '2014-05-18 22:22:22', 'Ressayons !', 1, 11, 8, 1),
(17, '2014-05-18 22:22:39', 'Oui ça marche bien :)', 1, 11, 8, 1),
(20, '2014-05-18 22:21:22', 'Nous avons pris note de tes remarques Bill. Merci beaucoup pour ce retour.\r\nCordialement.', 1, 8, 11, 1),
(22, '2014-05-19 00:06:11', 'Bonjour, pourrais-je vous demander si les messages sont gardés dans votre base de données à vie ?', 1, 11, 12, 6),
(23, '2014-05-19 00:09:57', '?', 1, 11, 12, 6),
(27, '2014-05-19 14:11:42', 'Petit test traite ou non', 1, 12, 11, 2),
(28, '2014-05-20 19:40:28', 'Bonjour Monsieur, avez vous des soucis avec l''intérface web?\r\nCordialement.\r\nVotre gestionnaire.', 1, 8, 11, 1),
(31, '2014-05-20 22:58:15', '&lt;script&gt;alert()&lt;/script&gt;', 1, 11, 12, 7),
(34, '2014-05-21 22:55:15', 'Nope :)', 1, 12, 11, 6),
(35, '2014-05-21 22:57:03', 'Parfait, merci beaucoup.', 1, 8, 11, 1),
(36, '2014-05-21 22:57:26', 'Petit test #2', 1, 12, 11, 2),
(37, '2014-05-21 22:57:45', 'Petit test #3', 1, 12, 11, 2),
(38, '2014-05-21 23:50:02', 'Et non les attaques javascript ne fonctionnent pas, nous sommes soucieux de la sécurité de notre site.\r\nCordialement.', 1, 12, 11, 7),
(39, '2014-05-21 23:50:48', 'Bien vu. J''en prend note. Merci.\r\nCordialement.', 1, 11, 12, 7),
(40, '2014-05-22 00:35:22', 'Ok.', 1, 12, 11, 7),
(41, '2014-05-22 00:35:57', 'Ok', 1, 12, 11, 7),
(42, '2014-05-22 01:50:50', 'Il faut voir avec les développeurs de l''agence!', 1, 12, 11, 6),
(43, '2014-05-22 01:52:34', ';)', 1, 11, 8, 1),
(44, '2014-05-22 01:57:43', 'é', 1, 8, 10, 1),
(45, '2014-05-22 01:58:36', 'Effectivement.. Bien joué!', 1, 10, 8, 1),
(46, '2014-05-22 02:18:42', 'ééééèèèèàâ ~é', 0, 8, 10, 1);

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE IF NOT EXISTS `paiement` (
  `id_paiement` int(11) NOT NULL AUTO_INCREMENT,
  `date_paiement` timestamp NULL DEFAULT NULL,
  `montant_paiement` double DEFAULT NULL,
  `motif_paiement` char(255) DEFAULT NULL,
  `id_personne_payeur` int(11) DEFAULT NULL,
  `id_historique` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_paiement`),
  KEY `FK_Paiement_id_personne` (`id_personne_payeur`),
  KEY `FK_Paiement_id_historique` (`id_historique`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `date_paiement`, `montant_paiement`, `motif_paiement`, `id_personne_payeur`, `id_historique`) VALUES
(1, NULL, 1500, 'loyer mai', 10, 12),
(2, NULL, 2000, 'loyer aout', 12, 13);

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE IF NOT EXISTS `personne` (
  `id_personne` int(11) NOT NULL AUTO_INCREMENT,
  `nom_personne` varchar(255) NOT NULL,
  `prenom_personne` varchar(255) NOT NULL,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mail` varchar(25) DEFAULT NULL,
  `id_photo` int(11) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_personne`),
  KEY `FK_Personne_id_photo` (`id_photo`),
  KEY `FK_Personne_id_adresse` (`id_adresse`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `personne`
--

INSERT INTO `personne` (`id_personne`, `nom_personne`, `prenom_personne`, `login`, `password`, `mail`, `id_photo`, `id_adresse`) VALUES
(1, 'test', 'test', 'test', 'test', 'test', NULL, NULL),
(2, 'alexis', 'admin', 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', NULL, NULL, NULL),
(7, 'alexis', 'admin', 'admin2', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', NULL, 1, NULL),
(8, 'Oblet', 'Alexis', NULL, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'alexdeoiny@gmail.com', 4, NULL),
(9, 'Biteau', 'Armand', NULL, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'armandbiteau@gmail.com', 5, NULL),
(10, 'Locataire_nom', 'Locataire_prenom', NULL, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'locataire@gmail.com', 1, 10),
(11, 'Gates', 'Bill', NULL, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'proprietaire@gmail.com', 2, 10),
(12, 'Constant', 'Matthieu', NULL, '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'employe@gmail.com', 3, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id_photo` int(11) NOT NULL AUTO_INCREMENT,
  `chemin_photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id_photo`, `chemin_photo`) VALUES
(1, 'img/avatar.png'),
(2, 'img/personnes/11/gates.jpg'),
(3, 'img/personnes/12/constant.jpg'),
(4, 'img/personnes/8/alex.jpg'),
(5, 'img/personnes/9/armand.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `proprietaire`
--

CREATE TABLE IF NOT EXISTS `proprietaire` (
  `id_personne` int(11) NOT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `proprietaire`
--

INSERT INTO `proprietaire` (`id_personne`) VALUES
(11);

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id_region` int(11) NOT NULL AUTO_INCREMENT,
  `nom_region` varchar(255) DEFAULT NULL,
  `ville_chef` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `region`
--

INSERT INTO `region` (`id_region`, `nom_region`, `ville_chef`) VALUES
(1, 'Alsace', 'Strasbourg'),
(2, 'Aquitaine', 'Bordeaux'),
(3, 'Auvergne', 'Clermont-Ferrand'),
(4, 'Basse Normandie', 'Caen'),
(5, 'Bourgogne', 'Dijon'),
(6, 'Bretagne', 'Rennes'),
(7, 'Centre', 'Orléans'),
(8, 'Champagne Ardenne', 'Châlons-en-Champagne'),
(9, 'Corse', 'Ajaccio'),
(10, 'Franche Comte', 'Besançon'),
(11, 'Haute Normandie', 'Rouen'),
(12, 'Ile de France', 'Paris'),
(13, 'Languedoc Roussillon', 'Montpellier'),
(14, 'Limousin', 'Limoges'),
(15, 'Lorraine', 'Metz'),
(16, 'Midi-Pyrénées', 'Toulouse'),
(17, 'Nord Pas de Calais', 'Lille'),
(18, 'Provence Alpes Côte d''Azur', 'Marseille'),
(19, 'Pays de la Loire', 'Nantes'),
(20, 'Picardie', 'Amiens'),
(21, 'Poitou Charente', 'Poitiers'),
(22, 'Rhone Alpes', 'Lyon');

-- --------------------------------------------------------

--
-- Structure de la table `type_chauffage`
--

CREATE TABLE IF NOT EXISTS `type_chauffage` (
  `id_type_chauffage` int(11) NOT NULL AUTO_INCREMENT,
  `nom_type_chauffage` varchar(255) NOT NULL,
  PRIMARY KEY (`id_type_chauffage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `type_chauffage`
--

INSERT INTO `type_chauffage` (`id_type_chauffage`, `nom_type_chauffage`) VALUES
(1, 'Gaz'),
(2, 'Fioul'),
(3, 'Bois'),
(4, 'Solaire'),
(5, 'Biomasse'),
(6, 'Pompe à chaleur'),
(7, 'Electrique');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD CONSTRAINT `FK_Adresse_id_departement` FOREIGN KEY (`id_departement`) REFERENCES `departement` (`id_departement`);

--
-- Contraintes pour la table `agence_immobiliere`
--
ALTER TABLE `agence_immobiliere`
  ADD CONSTRAINT `FK_Agence_immobiliere_id_adresse` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`),
  ADD CONSTRAINT `FK_Agence_immobiliere_id_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`);

--
-- Contraintes pour la table `appartement`
--
ALTER TABLE `appartement`
  ADD CONSTRAINT `FK_Appartement_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `bien_immobilier`
--
ALTER TABLE `bien_immobilier`
  ADD CONSTRAINT `FK_Bien_immobilier_id_adresse` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_agence_loueur` FOREIGN KEY (`id_agence_loueur`) REFERENCES `agence_immobiliere` (`id_agence_immobiliere`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_agence_vendeur` FOREIGN KEY (`id_agence_vendeur`) REFERENCES `agence_immobiliere` (`id_agence_immobiliere`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_consommation_energetique` FOREIGN KEY (`id_consommation_energetique`) REFERENCES `consommation_energetique_classe` (`id_consommation_energetique`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_gaz` FOREIGN KEY (`id_gaz`) REFERENCES `gaz_a_effet_de_serre_classe` (`id_gaz`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_personne_gest` FOREIGN KEY (`id_personne_gest`) REFERENCES `personne` (`id_personne`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_personne_locataire` FOREIGN KEY (`id_personne_locataire`) REFERENCES `personne` (`id_personne`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_personne_proprio` FOREIGN KEY (`id_personne_proprio`) REFERENCES `personne` (`id_personne`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_type_chauffage` FOREIGN KEY (`id_type_chauffage`) REFERENCES `type_chauffage` (`id_type_chauffage`);

--
-- Contraintes pour la table `departement`
--
ALTER TABLE `departement`
  ADD CONSTRAINT `FK_Departement_id_region` FOREIGN KEY (`id_region`) REFERENCES `region` (`id_region`);

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `FK_Employe_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`) ON DELETE CASCADE;

--
-- Contraintes pour la table `garage`
--
ALTER TABLE `garage`
  ADD CONSTRAINT `FK_Garage_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `FK_Historique_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `historique_depense`
--
ALTER TABLE `historique_depense`
  ADD CONSTRAINT `FK_Historique_depense_id_historique` FOREIGN KEY (`id_historique`) REFERENCES `historique` (`id_historique`),
  ADD CONSTRAINT `FK_Historique_depense_id_personne` FOREIGN KEY (`id_personne_impute`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `historique_entree`
--
ALTER TABLE `historique_entree`
  ADD CONSTRAINT `FK_Historique_entree_id_historique` FOREIGN KEY (`id_historique`) REFERENCES `historique` (`id_historique`),
  ADD CONSTRAINT `FK_Historique_entree_id_paiement` FOREIGN KEY (`id_paiement`) REFERENCES `paiement` (`id_paiement`);

--
-- Contraintes pour la table `illustrer`
--
ALTER TABLE `illustrer`
  ADD CONSTRAINT `FK_illustrer_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`),
  ADD CONSTRAINT `FK_illustrer_id_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`);

--
-- Contraintes pour la table `immeuble`
--
ALTER TABLE `immeuble`
  ADD CONSTRAINT `FK_Immeuble_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `locataire`
--
ALTER TABLE `locataire`
  ADD CONSTRAINT `FK_Locataire_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`) ON DELETE CASCADE;

--
-- Contraintes pour la table `maison`
--
ALTER TABLE `maison`
  ADD CONSTRAINT `FK_Maison_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_Message_id_auteur` FOREIGN KEY (`id_auteur`) REFERENCES `personne` (`id_personne`),
  ADD CONSTRAINT `FK_Message_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`),
  ADD CONSTRAINT `FK_Message_id_destinataire` FOREIGN KEY (`id_destinataire`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `FK_Paiement_id_historique` FOREIGN KEY (`id_historique`) REFERENCES `historique` (`id_historique`),
  ADD CONSTRAINT `FK_Paiement_id_personne` FOREIGN KEY (`id_personne_payeur`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `personne`
--
ALTER TABLE `personne`
  ADD CONSTRAINT `FK_Personne_id_adresse` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`),
  ADD CONSTRAINT `FK_Personne_id_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`);

--
-- Contraintes pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD CONSTRAINT `FK_Proprietaire_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
