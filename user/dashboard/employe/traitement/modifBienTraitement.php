<?php
	require_once(dirname(__FILE__).'/../../../../enum/enum_type_user.php'); 
	require_once(dirname(__FILE__).'/../../../../enum/enum_type_biens.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/dashboard/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/dashboard/getUtils.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/isExist.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/dashboard/employe/affichage_result.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/settings/connexion.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/recherche_biens/getUtils.php'); 
	session_start();

	if(empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != EMPLOYE || empty($_POST['id_bien_immobilier'])){
		header('Location: '.getPathRoot().'index.php',false,301);
		die();
	}

	if(!getLegitimite($_SESSION,$_POST['id_bien_immobilier'])){
		$url_redirect = '../modifBien.php?statut_reponse=fail&message='.urlencode(utf8_encode("Le bien en question ne vous concerne pas"));
		$end_url_redirect ='&id_bien_immobilier='.$_POST['id_bien_immobilier'];

		header('Location: '.$url_redirect.$end_url_redirect,false,301);
		die();
	}

	$end_url_redirect='';
	$end_url_id_bien = '&id_bien_immobilier='.$_POST['id_bien_immobilier'];

	if(    empty($_POST['prix']) || !is_numeric($_POST['prix']) || empty($_POST['proprietaire']) || empty($_POST['type_bien_radio']) || empty($_POST['rue']) 
		|| empty($_POST['nb_pieces']) || !is_numeric($_POST['nb_pieces']) || empty($_POST['numero_rue']) || !is_numeric($_POST['numero_rue'])
		|| empty($_POST['code_postal']) || !is_numeric($_POST['code_postal']) || empty($_POST['ville']) || empty($_POST['departement']) || empty($_POST['description']) 
		|| empty($_POST['vente_location']) || ($_POST['vente_location'] != 'vente' && $_POST['vente_location'] != 'location')){

		$message = urlencode(utf8_encode("Les champs obligatoires n'ont pas été correctement saisis"));
		$end_url_redirect='?statut_reponse=fail&message_reponse='.$message;
	}
	else{
		//proprietaire
		$id_proprietaire=NULL; $id_agence_vendeur=NULL;$id_agence_loueur=NULL;$type_chauffage=NULL;$gaz_effet_de_serre=NULL;$conso_energetique=NULL;
		$id_personne_locataire=NULL;

		$id_bien_immobilier = $_POST['id_bien_immobilier'];
		$id_agence = getIdAgence();

		if($_POST['vente_location'] == 'vente'){
			if($_POST['proprietaire'] != $id_agence){
				$end_url_redirect='?statut_reponse=fail&message_reponse='.urlencode(utf8_encode("La mofification a echoué: la vente est réservé à l'agence"));
				header('Location: ../modifBien.php'.$end_url_redirect.$end_url_id_bien);
				die();
			}
			$id_agence_vendeur=$_POST['proprietaire'];
		}
		else{
			if($_POST['proprietaire'] ==$id_agence)
				$id_agence_loueur = $_POST['proprietaire'];
			else
				$id_proprietaire = $_POST['proprietaire'];
		}

		if(!empty($_POST['chauffage']))
			$type_chauffage = $_POST['chauffage'];
		if(!empty($_POST['energetique']))
			$conso_energetique = $_POST['energetique'];
		if(!empty($_POST['gaz']))
			$gaz_effet_de_serre = $_POST['gaz'];

		if(!empty($_POST['locataire']) && is_numeric($_POST['locataire']))
			$id_personne_locataire = $_POST['locataire'];

		$parking=NULL;
		if(isset($_POST['parking']) && ($_POST['parking']==0 || $_POST['parking']==1))
			$parking = $_POST['parking'];

		//on update le bien
		$query="UPDATE bien_immobilier SET 	prix = :prix, superficie = :superficie , nb_pieces = :nb_pieces, descriptif = :descriptif, id_personne_gest = :id_personne_gest
											,parking = :parking, id_personne_proprio=:id_personne_proprio,id_agence_loueur=:id_agence_loueur,
											id_agence_vendeur = :id_agence_vendeur, id_type_chauffage = :id_type_chauffage, id_gaz = :id_gaz, 
											id_consommation_energetique = :id_consommation_energetique, date_parution=NOW(), id_personne_locataire = :id_personne_locataire
				WHERE id_bien_immobilier = :id_bien_immobilier ";

		$update_ok = false;
		$stmt = myPDO::getSingletonPDO()->prepare($query);
		$array_requete  =array(':prix'=>$_POST['prix'],':superficie'=>$_POST['superficie'],':nb_pieces'=>$_POST['nb_pieces'],
										  ':descriptif'=>$_POST['description'],':id_personne_gest'=>$_SESSION['id_personne'],':parking'=>$parking,
										  ':id_personne_proprio'=>$id_proprietaire,':id_agence_loueur'=>$id_agence_loueur,
										  ':id_agence_vendeur'=>$id_agence_vendeur, ':id_type_chauffage'=>$type_chauffage, ':id_gaz'=>$gaz_effet_de_serre,
										  ':id_consommation_energetique'=>$conso_energetique,':id_bien_immobilier'=>$id_bien_immobilier,
										  ':id_personne_locataire'=>$id_personne_locataire);

		$update_ok = $stmt->execute($array_requete);
		$stmt->closeCursor();

		if($update_ok)
			$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("La modification s'est bien déroulée"));
		else{
			$end_url_redirect='?statut_reponse=fail&message_reponse='.urlencode(utf8_encode("La modification a echouée ..."));
			header('Location: ../modifBien.php'.$end_url_redirect.$end_url_id_bien);
			die();
		}

		//adresse
		$adress_is_exist = adresseIsExistInDataBase($_POST['numero_rue'], $_POST['rue'], $_POST['ville'], $_POST['code_postal']);
		if($adress_is_exist){
			$stmt = myPDO::getSingletonPDO()->query("UPDATE bien_immobilier SET id_adresse = {$adress_is_exist['id_adresse']} WHERE id_bien_immobilier=$id_bien_immobilier");
			$stmt->closeCursor();
		}
		else{
			$stmt =  myPDO::getSingletonPDO()->prepare("INSERT INTO adresse (numero_rue,rue,ville,code_postal,id_departement) 
														VALUES(:numero_rue,:rue,:ville,:code_postal,:id_departement)");

			$stmt->execute(array(	':numero_rue'=>$_POST['numero_rue'],':rue'=>$_POST['rue'],':ville'=>$_POST['ville'],':code_postal'=>$_POST['code_postal'],
									':id_departement'=>$_POST['departement']));

			$id_adresse = myPDO::getSingletonPDO()->lastInsertId();
			$stmt->closeCursor();

			$stmt=myPDO::getSingletonPDO()->query("UPDATE bien_immobilier SET id_adresse=$id_adresse WHERE id_bien_immobilier=$id_bien_immobilier ");
			$stmt->closeCursor();
		}

		$typeBienDepart = strtolower(getTypeBien($id_bien_immobilier));
		// on supprime son type...
		$stmt = myPDO::getSingletonPDO()->query("DELETE FROM {$typeBienDepart} WHERE id_bien_immobilier = $id_bien_immobilier");

		//specificités
		if($_POST['type_bien_radio'] == IMMEUBLE){
			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO immeuble (id_bien_immobilier) VALUES ($id_bien_immobilier)");
			$stmt->closeCursor();
		}
		elseif($_POST['type_bien_radio'] == GARAGE){
			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO garage (id_bien_immobilier) VALUES ($id_bien_immobilier)");
			$stmt->closeCursor();
		}
		elseif($_POST['type_bien_radio'] == MAISON){
			$superficie_jardin=0;
			if(!empty($_POST['superficie_jardin']) && is_numeric($_POST['superficie_jardin']) && $_POST['superficie_jardin'] >=0)
				$superficie_jardin = $_POST['superficie_jardin'];

			if(!empty($_POST['superficie_jardin']) && !is_numeric($_POST['superficie_jardin']))
				$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("La modification s'est bien déroulée! Mais la supérficie a été ignoré: Mauvaise donnée"));

			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO maison (id_bien_immobilier,superficie_jardin) VALUES ($id_bien_immobilier,$superficie_jardin)");
			$stmt->closeCursor();
		}
		elseif($_POST['type_bien_radio'] == APPARTEMENT){
			$etage='NULL'; $ascenseur='NULL'; $numero_appartement='NULL';

			if(!empty($_POST['etage'])){
				if(is_numeric($_POST['etage']) && $_POST['etage'] >=0)
					$etage = $_POST['etage'];
				else
					$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("La modification s'est bien déroulée! Mais l'étage a été ignoré: Mauvaise donnée"));
			}

			if(isset($_POST['ascenseur'])){
				if(is_numeric($_POST['ascenseur']) && ($_POST['ascenseur'] == 1 || $_POST['ascenseur'] == 0))
					$ascenseur = $_POST['ascenseur'];
				else
					$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("La modification s'est bien déroulée! Mais l'ascenseur a été ignoré: Mauvaise donnée"));
			}

			if(!empty($_POST['numero_appartement'])){
				if(is_numeric($_POST['numero_appartement']) && $_POST['numero_appartement'] > 0)
					$numero_appartement = $_POST['numero_appartement'];
				else{
					$end_url_redirect='?statut_reponse=ok&message_reponse=';
					$end_url_redirect.=urlencode(utf8_encode("La modification s'est bien déroulée! Mais le numero d'appartement a été ignoré: Mauvaise donnée"));
				}
			}

			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO appartement (id_bien_immobilier,etage,ascenseur, numero_appartement) 
												     VALUES ($id_bien_immobilier,$etage, $ascenseur, $numero_appartement)");
			$stmt->closeCursor();
		}
	}


	header('Location: ../modifBien.php'.$end_url_redirect.$end_url_id_bien);

