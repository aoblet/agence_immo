#------------------------------------------------------------
#        Script MySQL. Modifié après génération par Jmerise
#        Utiliser celui ci plutôt que la génération auto
#------------------------------------------------------------


CREATE TABLE Personne(
        id_personne     int (11) Auto_increment  NOT NULL ,
        nom_personne    Varchar (255) NOT NULL ,
        prenom_personne Varchar (255) NOT NULL ,
        user_name       Varchar (255) ,
        password        Varchar (255) ,
        id_photo        Int ,
        PRIMARY KEY (id_personne )
)ENGINE=InnoDB;


CREATE TABLE Proprietaire(
        id_personne Int NOT NULL ,
        PRIMARY KEY (id_personne )
)ENGINE=InnoDB;


CREATE TABLE Locataire(
        id_personne Int NOT NULL ,
        PRIMARY KEY (id_personne )
)ENGINE=InnoDB;


CREATE TABLE Employe(
        id_personne Int NOT NULL ,
        PRIMARY KEY (id_personne )
)ENGINE=InnoDB;


CREATE TABLE Bien_immobilier(
        id_bien_immobilier     int (11) Auto_increment  NOT NULL ,
        prix                   Double ,
        superficie             Int ,
        nb_pieces              Int ,
        descriptif             Varchar (255) ,
        parking                Bool ,
        nb_etages              Int ,
        id_personne_loueur     Int ,
        id_personne_proprio    Int ,
        id_personne_gest       Int ,
        id_agence_vendeur      Int ,
        id_type_chauffage      Int ,
        id_adresse             Int ,
        id_type_gaz            Int ,
        PRIMARY KEY (id_bien_immobilier )
)ENGINE=InnoDB;


CREATE TABLE Agence_immobiliere(
        id_agence_immobiliere      int (11) Auto_increment  NOT NULL ,
        nom_agence_immobiliere     Varchar (255) ,
        capital_agence_immobiliere Double ,
        id_adresse                 Int ,
        id_photo                   Int ,
        PRIMARY KEY (id_agence_immobiliere )
)ENGINE=InnoDB;


CREATE TABLE Photo(
        id_photo     int (11) Auto_increment  NOT NULL ,
        chemin_photo Varchar (255) NOT NULL ,
        PRIMARY KEY (id_photo )
)ENGINE=InnoDB;


CREATE TABLE Type_chauffage(
        id_type_chauffage          int (11) Auto_increment  NOT NULL ,
        nom_type_chauffage         Varchar (255) NOT NULL ,
        classe_energetique         Char (25) ,
        consommation_kilowatt_mini Int ,
        consommation_kilowatt_maxi Int ,
        PRIMARY KEY (id_type_chauffage )
)ENGINE=InnoDB;


CREATE TABLE Adresse(
        id_adresse     int (11) Auto_increment  NOT NULL ,
        code_postal    Int ,
        ville          Varchar (255) ,
        rue            Varchar (255) ,
        numero_rue     Int ,
        id_departement Int ,
        PRIMARY KEY (id_adresse )
)ENGINE=InnoDB;


CREATE TABLE Appartement(
        etage              Int ,
        ascenseur          Bool ,
        numero_appartement Int ,
        id_bien_immobilier Int NOT NULL ,
        PRIMARY KEY (id_bien_immobilier )
)ENGINE=InnoDB;


CREATE TABLE Maison(
        superficie_jardin  Int ,
        id_bien_immobilier Int NOT NULL ,
        PRIMARY KEY (id_bien_immobilier )
)ENGINE=InnoDB;


CREATE TABLE Departement(
        id_departement   int (11) Auto_increment  NOT NULL ,
        code_departement Varchar (255) ,
        nom_departement  Varchar (255) ,
        id_region        Int ,
        PRIMARY KEY (id_departement )
)ENGINE=InnoDB;


CREATE TABLE Region(
        id_region  int (11) Auto_increment  NOT NULL ,
        nom_region Varchar (255) ,
        ville_chef Varchar (255) ,
        PRIMARY KEY (id_region )
)ENGINE=InnoDB;


CREATE TABLE Message(
        id_message           int (11) Auto_increment  NOT NULL ,
        date_message         Date ,
        contenu_message      Varchar (255) ,
        traite               Bool ,
        id_auteur            Int ,
        id_destinataire      Int ,
        PRIMARY KEY (id_message )
)ENGINE=InnoDB;


CREATE TABLE Type_emission_gaz(
        id_type_gaz                      int (11) Auto_increment  NOT NULL ,
        nom_type_gaz                     Varchar (255) ,
        classe_energetique               Char (255) ,
        consommation_kilogramme_co2_mini Int ,
        consommation_kilogramme_co2_maxi Int ,
        PRIMARY KEY (id_type_gaz )
)ENGINE=InnoDB;


CREATE TABLE Historique_depense(
        id_historique Int NOT NULL ,
        id_personne   Int ,
        PRIMARY KEY (id_historique )
)ENGINE=InnoDB;


CREATE TABLE Historique(
        id_historique      int (11) Auto_increment  NOT NULL ,
        nom_action         Varchar (255) NOT NULL ,
        prix_action        Double ,
        descriptif_action  Varchar (25) ,
        id_bien_immobilier Int ,
        PRIMARY KEY (id_historique )
)ENGINE=InnoDB;


CREATE TABLE Historique_entree(
        id_historique Int NOT NULL ,
        id_paiement   Int ,
        PRIMARY KEY (id_historique )
)ENGINE=InnoDB;


CREATE TABLE Paiement(
        id_paiement      int (11) Auto_increment  NOT NULL ,
        date_paiement    Date ,
        montant_paiement Double ,
        motif_paiement   Char (255) ,
        id_personne      Int ,
        id_historique    Int ,
        PRIMARY KEY (id_paiement )
)ENGINE=InnoDB;


CREATE TABLE habiter(
        id_personne Int NOT NULL ,
        id_adresse  Int NOT NULL ,
        PRIMARY KEY (id_personne ,id_adresse )
)ENGINE=InnoDB;


CREATE TABLE illustrer(
        id_bien_immobilier Int NOT NULL ,
        id_photo           Int NOT NULL ,
        PRIMARY KEY (id_bien_immobilier ,id_photo )
)ENGINE=InnoDB;

ALTER TABLE Personne ADD CONSTRAINT FK_Personne_id_photo FOREIGN KEY (id_photo) REFERENCES Photo(id_photo);
ALTER TABLE Proprietaire ADD CONSTRAINT FK_Proprietaire_id_personne FOREIGN KEY (id_personne) REFERENCES Personne(id_personne);
ALTER TABLE Locataire ADD CONSTRAINT FK_Locataire_id_personne FOREIGN KEY (id_personne) REFERENCES Personne(id_personne);
ALTER TABLE Employe ADD CONSTRAINT FK_Employe_id_personne FOREIGN KEY (id_personne) REFERENCES Personne(id_personne);

ALTER TABLE Bien_immobilier ADD CONSTRAINT FK_Bien_immobilier_id_personne_loueur FOREIGN KEY (id_personne_loueur) REFERENCES Personne(id_personne);
ALTER TABLE Bien_immobilier ADD CONSTRAINT FK_Bien_immobilier_id_personne_proprio FOREIGN KEY (id_personne_proprio) REFERENCES Personne(id_personne);
ALTER TABLE Bien_immobilier ADD CONSTRAINT FK_Bien_immobilier_id_personne_gest FOREIGN KEY (id_personne_gest) REFERENCES Personne(id_personne);
ALTER TABLE Bien_immobilier ADD CONSTRAINT FK_Bien_immobilier_id_agence_vendeur FOREIGN KEY (id_agence_vendeur) REFERENCES Agence_immobiliere(id_agence_immobiliere);
ALTER TABLE Bien_immobilier ADD CONSTRAINT FK_Bien_immobilier_id_type_chauffage FOREIGN KEY (id_type_chauffage) REFERENCES Type_chauffage(id_type_chauffage);
ALTER TABLE Bien_immobilier ADD CONSTRAINT FK_Bien_immobilier_id_adresse FOREIGN KEY (id_adresse) REFERENCES Adresse(id_adresse);
ALTER TABLE Bien_immobilier ADD CONSTRAINT FK_Bien_immobilier_id_type_gaz FOREIGN KEY (id_type_gaz) REFERENCES Type_emission_gaz(id_type_gaz);

ALTER TABLE Agence_immobiliere ADD CONSTRAINT FK_Agence_immobiliere_id_adresse FOREIGN KEY (id_adresse) REFERENCES Adresse(id_adresse);
ALTER TABLE Agence_immobiliere ADD CONSTRAINT FK_Agence_immobiliere_id_photo FOREIGN KEY (id_photo) REFERENCES Photo(id_photo);

ALTER TABLE Adresse ADD CONSTRAINT FK_Adresse_id_departement FOREIGN KEY (id_departement) REFERENCES Departement(id_departement);
ALTER TABLE Appartement ADD CONSTRAINT FK_Appartement_id_bien_immobilier FOREIGN KEY (id_bien_immobilier) REFERENCES Bien_immobilier(id_bien_immobilier);

ALTER TABLE Maison ADD CONSTRAINT FK_Maison_id_bien_immobilier FOREIGN KEY (id_bien_immobilier) REFERENCES Bien_immobilier(id_bien_immobilier);
ALTER TABLE Departement ADD CONSTRAINT FK_Departement_id_region FOREIGN KEY (id_region) REFERENCES Region(id_region);

ALTER TABLE Message ADD CONSTRAINT FK_Message_id_auteur FOREIGN KEY (id_auteur) REFERENCES Personne(id_personne);
ALTER TABLE Message ADD CONSTRAINT FK_Message_id_destinataire FOREIGN KEY (id_destinataire) REFERENCES Personne(id_personne);

ALTER TABLE Historique_depense ADD CONSTRAINT FK_Historique_depense_id_historique FOREIGN KEY (id_historique) REFERENCES Historique(id_historique);
ALTER TABLE Historique_depense ADD CONSTRAINT FK_Historique_depense_id_personne FOREIGN KEY (id_personne) REFERENCES Personne(id_personne);
ALTER TABLE Historique ADD CONSTRAINT FK_Historique_id_bien_immobilier FOREIGN KEY (id_bien_immobilier) REFERENCES Bien_immobilier(id_bien_immobilier);
ALTER TABLE Historique_entree ADD CONSTRAINT FK_Historique_entree_id_historique FOREIGN KEY (id_historique) REFERENCES Historique(id_historique);
ALTER TABLE Historique_entree ADD CONSTRAINT FK_Historique_entree_id_paiement FOREIGN KEY (id_paiement) REFERENCES Paiement(id_paiement);

ALTER TABLE Paiement ADD CONSTRAINT FK_Paiement_id_personne FOREIGN KEY (id_personne) REFERENCES Personne(id_personne);
ALTER TABLE Paiement ADD CONSTRAINT FK_Paiement_id_historique FOREIGN KEY (id_historique) REFERENCES Historique(id_historique);

ALTER TABLE habiter ADD CONSTRAINT FK_habiter_id_personne FOREIGN KEY (id_personne) REFERENCES Personne(id_personne);
ALTER TABLE habiter ADD CONSTRAINT FK_habiter_id_adresse FOREIGN KEY (id_adresse) REFERENCES Adresse(id_adresse);

ALTER TABLE illustrer ADD CONSTRAINT FK_illustrer_id_bien_immobilier FOREIGN KEY (id_bien_immobilier) REFERENCES Bien_immobilier(id_bien_immobilier);
ALTER TABLE illustrer ADD CONSTRAINT FK_illustrer_id_photo FOREIGN KEY (id_photo) REFERENCES Photo(id_photo);
