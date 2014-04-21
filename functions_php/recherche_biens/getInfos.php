<?php
	require_once(dirname(__FILE__).'/../settings/connexion.php');
	require_once(dirname(__FILE__).'/enum_type_biens.php');

	/* GET INFOS */
	function getInfosChauffage($id_bien_immobilier){
		$infos_vides=array(	"id_type_chauffage"=>NULL,
							"nom_type_chauffage"=>NULL);

		if($id_bien_immobilier != NULL){
			$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM type_chauffage WHERE id_type_chauffage = 
														(SELECT id_type_chauffage FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)");
			$infos = $stmt->fetch();
			$stmt->closeCursor();
			if($infos)
				return $infos;
		}
		return $infos_vides;
	}

	function getInfosAdresse($id_bien_immobilier){
		// recup: code postal, ville, nom rue, numero rue?, nomdepartement, code departement,nom region, ville chef region
		$infos_vides=array(	"ville"=>NULL,
							"code_postal"=>NULL,
							"rue"=>NULL,
							"numero_rue"=>NULL,
							"code_departement"=>NULL,
							"nom_departement"=>NULL,
							"nom_region"=>NULL,
							"ville_chef"=>NULL);

		if($id_bien_immobilier != NULL){
			$query="SELECT 	adr.ville AS ville, 
					adr.code_postal AS code_postal,
					adr.rue AS rue,
					adr.numero_rue AS numero_rue,
					dep.code_departement AS code_departement,
					dep.nom_departement AS nom_departement,
					reg.nom_region AS nom_region,
					reg.ville_chef AS ville_chef

			FROM adresse adr, departement dep, region reg 
			WHERE 		id_adresse = (SELECT id_adresse FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)
																	   AND	adr.id_departement = dep.id_departement
																	   AND  dep.id_region = reg.id_region";

			$stmt = myPDO::getSingletonPDO()->query($query);
			$infos = $stmt->fetch();
			$stmt->closeCursor();
			if($infos)
				return $infos;
		}
		return $infos_vides;
	}

	function getInfosGaz($id_bien_immobilier){
		$infos_vides=array(	"id_gaz"=>NULL,
							"nom_gaz"=>NULL,
							"emission_kilo_co2_an_mcarre_mini"=>NULL,
							"emission_kilo_co2_an_mcarre_maxi"=>NULL);

		if($id_bien_immobilier != NULL){
			$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM gaz_a_effet_de_serre_classe WHERE id_gaz = 
														(SELECT id_gaz FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)");
			$infos = $stmt->fetch();
			$stmt->closeCursor();
			if($infos)
				return $infos;
		}
		return $infos_vides;
	}

	function getInfosConsoEnergetique($id_bien_immobilier){
		$infos_vides = array(	"id_consommation_energetique"=>NULL,
								"nom_consommation_energetique"=>NULL,
								"conso_kilowatt_an_mcarre_mini"=>NULL,
								"conso_kilowatt_an_mcarre_maxi"=>NULL);

		if($id_bien_immobilier != NULL){
			$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM consommation_energetique_classe WHERE id_consommation_energetique = 
														(SELECT id_consommation_energetique FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)");
			$infos = $stmt->fetch();
			$stmt->closeCursor();
			if($infos)
				return $infos;
		}
		return $infos_vides;
	}

	function getInfosLocataire($id_bien_immobilier){
		$infos_vides = array(	"id_personne_locataire"=>NULL,
								"nom_personne_locataire"=>NULL,
								"prenom_personne_locataire"=>NULL,
								"mail_personne_locataire"=>NULL);

		if($id_bien_immobilier != NULL){
			$query="SELECT 	id_personne AS id_personne_locataire, 
							nom_personne AS nom_personne_locataire, 
							prenom_personne AS prenom_personne_locataire, 
							mail AS mail_personne_locataire 
					FROM personne
					WHERE id_personne = (SELECT id_personne_locataire FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)";

			$stmt = myPDO::getSingletonPDO()->query($query);
			$infos = $stmt->fetch();
			$stmt->closeCursor();
			if($infos)
				return $infos;
		}
		return $infos_vides;
	}

	function getInfosLoueur($id_bien_immobilier){
		$infos_vides = array(	"id_personne_loueur"=>NULL,
								"nom_personne_loueur"=>NULL,
								"prenom_personne_loueur"=>NULL,
								"mail_personne_loueur"=>NULL);
		
		if($id_bien_immobilier != NULL){
			$query_proprio="SELECT 	id_personne AS id_personne_loueur, 
									nom_personne AS nom_personne_loueur, 
									prenom_personne AS prenom_personne_loueur, 
									mail AS mail_personne_loueur 
							FROM personne
							WHERE id_personne = (SELECT id_personne_loueur FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)
							UNION
							SELECT 	id_agence_immobiliere AS id_personne_loueur, 
									nom_agence_immobiliere AS nom_personne_loueur, 
									'' AS prenom_personne_loueur, 
									mail_agence_immobiliere AS mail_personne_loueur 
							FROM agence_immobiliere
							WHERE id_agence_immobiliere = (SELECT id_agence_loueur FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier";

			$stmt = myPDO::getSingletonPDO()->query($query_proprio);
			$infos = $stmt->fetch();
			$stmt->closeCursor();
			if($infos)
				return $infos;

		}
		return $infos_vides;
	}

	function getInfosVendeur($id_bien_immobilier){
		$infos_vides = array(	"id_agence_immobiliere_vendeur"=>NULL,
								"nom_agence_immobiliere_vendeur"=>NULL,
								"capital_agence_immobiliere_vendeur"=>NULL,
								"mail_agence_immobiliere"=>NULL);

		if($id_bien_immobilier != NULL){
			$query="SELECT 	id_agence_immobiliere AS id_agence_immobiliere_vendeur, 
							nom_agence_immobiliere AS nom_agence_immobiliere_vendeur, 
							capital AS capital_agence_immobiliere_vendeur,
							mail_agence_immobiliere as mail_agence_immobiliere_vendeur 
					FROM agence_immobiliere
					WHERE id_agence_immobiliere = (SELECT id_agence_vendeur FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)";

			$stmt = myPDO::getSingletonPDO()->query($query);
			$infos = $stmt->fetch();
			$stmt->closeCursor();
			if($infos)
				return $infos;
		}
		return $infos_vides;

	}

	function getInfosGestionnaire($id_bien_immobilier){
		$infos_vides = array(	"id_personne_gest"=>NULL,
								"nom_personne_gest"=>NULL,
								"prenom_personne_gest"=>NULL,
								"mail_personne_gest"=>NULL);
		
		if($id_bien_immobilier != NULL){
			$query="SELECT 	id_personne AS id_personne_gest, 
							nom_personne AS nom_personne_gest, 
							prenom_personne AS prenom_personne_gest, 
							mail AS mail_personne_gest 
					FROM personne
					WHERE id_personne = (SELECT id_personne_gest FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier)";

			$stmt = myPDO::getSingletonPDO()->query($query);
			$infos = $stmt->fetch();
			$stmt->closeCursor();

			if($infos)
				return $infos;
		}
		return $infos_vides;
	}

	function getInfosPhotos($id_bien_immobilier){
		//$infos_vides = array("chemins_photo"=>NULL);
		$array_info = array();

		if($id_bien_immobilier != NULL){
			$query="SELECT chemin_photo 
					FROM photo
					WHERE id_photo = (SELECT DISTINCT id_photo FROM illustrer WHERE id_bien_immobilier = $id_bien_immobilier)";
			$stmt = myPDO::getSingletonPDO()->query($query);

			while($infos = $stmt->fetch()){
				$array_info[]=$infos['chemin_photo'];
			}
			$stmt->closeCursor();
		}
		return $array_info;
	}

	function getInfosAchatLocation($id_bien_immobilier){
		$type_bien_vente_location = NULL;
		$stmt = myPDO::getSingletonPDO()->query("SELECT id_personne_loueur, id_agence_vendeur, id_agence_loueur");
		$infos = $stmt->fetch();
		$stmt->closeCursor();

		if(!$infos)
			return $type_bien_vente_location;
		if($infos['id_personne_loueur'] != NULL ||$infos['id_agence_loueur']!= NULL)
			return "Location";
		if($infos["id_agence_vendeur"] != NULL)
			return "Vente";
		return NULL;

	}