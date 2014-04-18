-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 17 Avril 2014 à 22:50
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `agence_immo`
--
CREATE DATABASE IF NOT EXISTS `agence_immo` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `agence_immo`;

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE IF NOT EXISTS `adresse` (
  `id_adresse` int(11) NOT NULL AUTO_INCREMENT,
  `code_postal` int(11) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `rue` varchar(255) DEFAULT NULL,
  `numero_rue` int(11) DEFAULT NULL,
  `id_departement` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_adresse`),
  KEY `FK_Adresse_id_departement` (`id_departement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `agence_immobiliere`
--

CREATE TABLE IF NOT EXISTS `agence_immobiliere` (
  `id_agence_immobiliere` int(11) NOT NULL AUTO_INCREMENT,
  `nom_agence_immobiliere` varchar(255) DEFAULT NULL,
  `capital_agence_immobiliere` double DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  `id_photo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_agence_immobiliere`),
  KEY `FK_Agence_immobiliere_id_adresse` (`id_adresse`),
  KEY `FK_Agence_immobiliere_id_photo` (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `id_personne_loueur` int(11) DEFAULT NULL,
  `id_personne_proprio` int(11) DEFAULT NULL,
  `id_personne_gest` int(11) DEFAULT NULL,
  `id_agence_vendeur` int(11) DEFAULT NULL,
  `id_type_chauffage` int(11) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  `id_gaz` int(11) DEFAULT NULL,
  `id_consommation_energetique` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_bien_immobilier`),
  KEY `FK_Bien_immobilier_id_personne_loueur` (`id_personne_loueur`),
  KEY `FK_Bien_immobilier_id_personne_proprio` (`id_personne_proprio`),
  KEY `FK_Bien_immobilier_id_personne_gest` (`id_personne_gest`),
  KEY `FK_Bien_immobilier_id_agence_vendeur` (`id_agence_vendeur`),
  KEY `FK_Bien_immobilier_id_type_chauffage` (`id_type_chauffage`),
  KEY `FK_Bien_immobilier_id_adresse` (`id_adresse`),
  KEY `FK_Bien_immobilier_id_gaz` (`id_gaz`),
  KEY `FK_Bien_immobilier_id_consommation_energetique` (`id_consommation_energetique`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=97 ;

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

-- --------------------------------------------------------

--
-- Structure de la table `garage`
--

CREATE TABLE IF NOT EXISTS `garage` (
  `id_bien_immobilier` int(11) NOT NULL,
  PRIMARY KEY (`id_bien_immobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

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
-- Structure de la table `habiter`
--

CREATE TABLE IF NOT EXISTS `habiter` (
  `id_personne` int(11) NOT NULL,
  `id_adresse` int(11) NOT NULL,
  PRIMARY KEY (`id_personne`,`id_adresse`),
  KEY `FK_habiter_id_adresse` (`id_adresse`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE IF NOT EXISTS `historique` (
  `id_historique` int(11) NOT NULL AUTO_INCREMENT,
  `nom_action` varchar(255) NOT NULL,
  `prix_action` double DEFAULT NULL,
  `descriptif_action` varchar(25) DEFAULT NULL,
  `id_bien_immobilier` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_historique`),
  KEY `FK_Historique_id_bien_immobilier` (`id_bien_immobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `historique_depense`
--

CREATE TABLE IF NOT EXISTS `historique_depense` (
  `id_historique` int(11) NOT NULL,
  `id_personne` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_historique`),
  KEY `FK_Historique_depense_id_personne` (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Structure de la table `locataire`
--

CREATE TABLE IF NOT EXISTS `locataire` (
  `id_personne` int(11) NOT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `maison`
--

CREATE TABLE IF NOT EXISTS `maison` (
  `superficie_jardin` int(11) DEFAULT NULL,
  `id_bien_immobilier` int(11) NOT NULL,
  PRIMARY KEY (`id_bien_immobilier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id_message` int(11) NOT NULL AUTO_INCREMENT,
  `date_message` date DEFAULT NULL,
  `contenu_message` varchar(255) DEFAULT NULL,
  `traite` tinyint(1) DEFAULT NULL,
  `id_auteur` int(11) DEFAULT NULL,
  `id_destinataire` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_message`),
  KEY `FK_Message_id_auteur` (`id_auteur`),
  KEY `FK_Message_id_destinataire` (`id_destinataire`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE IF NOT EXISTS `paiement` (
  `id_paiement` int(11) NOT NULL AUTO_INCREMENT,
  `date_paiement` date DEFAULT NULL,
  `montant_paiement` double DEFAULT NULL,
  `motif_paiement` char(255) DEFAULT NULL,
  `id_personne` int(11) DEFAULT NULL,
  `id_historique` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_paiement`),
  KEY `FK_Paiement_id_personne` (`id_personne`),
  KEY `FK_Paiement_id_historique` (`id_historique`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE IF NOT EXISTS `personne` (
  `id_personne` int(11) NOT NULL AUTO_INCREMENT,
  `nom_personne` varchar(255) NOT NULL,
  `prenom_personne` varchar(255) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_photo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_personne`),
  KEY `FK_Personne_id_photo` (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id_photo` int(11) NOT NULL AUTO_INCREMENT,
  `chemin_photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_photo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `proprietaire`
--

CREATE TABLE IF NOT EXISTS `proprietaire` (
  `id_personne` int(11) NOT NULL,
  PRIMARY KEY (`id_personne`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `id_region` int(11) NOT NULL AUTO_INCREMENT,
  `nom_region` varchar(255) DEFAULT NULL,
  `ville_chef` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  ADD CONSTRAINT `FK_Agence_immobiliere_id_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`),
  ADD CONSTRAINT `FK_Agence_immobiliere_id_adresse` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`);

--
-- Contraintes pour la table `appartement`
--
ALTER TABLE `appartement`
  ADD CONSTRAINT `FK_Appartement_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `bien_immobilier`
--
ALTER TABLE `bien_immobilier`
  ADD CONSTRAINT `FK_Bien_immobilier_id_consommation_energetique` FOREIGN KEY (`id_consommation_energetique`) REFERENCES `consommation_energetique_classe` (`id_consommation_energetique`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_adresse` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_agence_vendeur` FOREIGN KEY (`id_agence_vendeur`) REFERENCES `agence_immobiliere` (`id_agence_immobiliere`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_gaz` FOREIGN KEY (`id_gaz`) REFERENCES `gaz_a_effet_de_serre_classe` (`id_gaz`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_personne_gest` FOREIGN KEY (`id_personne_gest`) REFERENCES `personne` (`id_personne`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_personne_loueur` FOREIGN KEY (`id_personne_loueur`) REFERENCES `personne` (`id_personne`),
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
  ADD CONSTRAINT `FK_Employe_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `garage`
--
ALTER TABLE `garage`
  ADD CONSTRAINT `FK_Garage_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `habiter`
--
ALTER TABLE `habiter`
  ADD CONSTRAINT `FK_habiter_id_adresse` FOREIGN KEY (`id_adresse`) REFERENCES `adresse` (`id_adresse`),
  ADD CONSTRAINT `FK_habiter_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `FK_Historique_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `historique_depense`
--
ALTER TABLE `historique_depense`
  ADD CONSTRAINT `FK_Historique_depense_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`),
  ADD CONSTRAINT `FK_Historique_depense_id_historique` FOREIGN KEY (`id_historique`) REFERENCES `historique` (`id_historique`);

--
-- Contraintes pour la table `historique_entree`
--
ALTER TABLE `historique_entree`
  ADD CONSTRAINT `FK_Historique_entree_id_paiement` FOREIGN KEY (`id_paiement`) REFERENCES `paiement` (`id_paiement`),
  ADD CONSTRAINT `FK_Historique_entree_id_historique` FOREIGN KEY (`id_historique`) REFERENCES `historique` (`id_historique`);

--
-- Contraintes pour la table `illustrer`
--
ALTER TABLE `illustrer`
  ADD CONSTRAINT `FK_illustrer_id_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`),
  ADD CONSTRAINT `FK_illustrer_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `immeuble`
--
ALTER TABLE `immeuble`
  ADD CONSTRAINT `FK_Immeuble_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `locataire`
--
ALTER TABLE `locataire`
  ADD CONSTRAINT `FK_Locataire_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `maison`
--
ALTER TABLE `maison`
  ADD CONSTRAINT `FK_Maison_id_bien_immobilier` FOREIGN KEY (`id_bien_immobilier`) REFERENCES `bien_immobilier` (`id_bien_immobilier`);

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_Message_id_destinataire` FOREIGN KEY (`id_destinataire`) REFERENCES `personne` (`id_personne`),
  ADD CONSTRAINT `FK_Message_id_auteur` FOREIGN KEY (`id_auteur`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `FK_Paiement_id_historique` FOREIGN KEY (`id_historique`) REFERENCES `historique` (`id_historique`),
  ADD CONSTRAINT `FK_Paiement_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`);

--
-- Contraintes pour la table `personne`
--
ALTER TABLE `personne`
  ADD CONSTRAINT `FK_Personne_id_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`);

--
-- Contraintes pour la table `proprietaire`
--
ALTER TABLE `proprietaire`
  ADD CONSTRAINT `FK_Proprietaire_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
