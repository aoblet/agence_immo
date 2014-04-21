-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Lun 21 Avril 2014 à 02:02
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
  `mail_agence_immobiliere` varchar(255) DEFAULT NULL,
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
  `id_personne_locataire` int(11) DEFAULT NULL,
  `id_personne_proprio` int(11) DEFAULT NULL,
  `id_personne_gest` int(11) DEFAULT NULL,
  `id_agence_vendeur` int(11) DEFAULT NULL,
  `id_agence_loueur` int(11) DEFAULT NULL,
  `id_type_chauffage` int(11) DEFAULT NULL,
  `id_adresse` int(11) DEFAULT NULL,
  `id_gaz` int(11) DEFAULT NULL,
  `id_consommation_energetique` int(11) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(7);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mail` varchar(25) DEFAULT NULL,
  `id_photo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_personne`),
  KEY `FK_Personne_id_photo` (`id_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `personne`
--

INSERT INTO `personne` (`id_personne`, `nom_personne`, `prenom_personne`, `login`, `password`, `mail`, `id_photo`) VALUES
(1, 'test', 'test', 'test', 'test', 'test', NULL),
(2, 'alexis', 'admin', 'admin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', NULL, NULL),
(7, 'alexis', 'admin', 'admin2', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `id_photo` int(11) NOT NULL AUTO_INCREMENT,
  `chemin_photo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_photo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `photo`
--

INSERT INTO `photo` (`id_photo`, `chemin_photo`) VALUES
(1, 'img/avatar.png');

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  ADD CONSTRAINT `FK_Bien_immobilier_id_agence_loueur` FOREIGN KEY (`id_agence_loueur`) REFERENCES `agence_immobiliere` (`id_agence_immobiliere`),
  ADD CONSTRAINT `FK_Bien_immobilier_id_agence_vendeur` FOREIGN KEY (`id_agence_vendeur`) REFERENCES `agence_immobiliere` (`id_agence_immobiliere`),
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
  ADD CONSTRAINT `FK_Proprietaire_id_personne` FOREIGN KEY (`id_personne`) REFERENCES `personne` (`id_personne`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
