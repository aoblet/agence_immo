<?php
	require_once(dirname(__FILE__).'/../../../../enum/enum_type_user.php'); 
	require_once(dirname(__FILE__).'/../../../../enum/enum_type_biens.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/dashboard/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/dashboard/getUtils.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/user_utils/isExist.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/dashboard/employe/affichage_result.php'); 
	require_once(dirname(__FILE__).'/../../../../functions_php/settings/connexion.php'); 
	session_start();

	if(empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != EMPLOYE){
		header('Location: '.getPathRoot().'index.php',false,301);
		die();
	}


	$end_url_redirect='';

	if(    empty($_POST['prix']) || !is_numeric($_POST['prix']) || empty($_POST['proprietaire']) || empty($_POST['type_bien_radio']) || empty($_POST['rue']) 
		|| empty($_POST['nb_pieces']) || !is_numeric($_POST['nb_pieces']) || empty($_POST['numero_rue']) || !is_numeric($_POST['numero_rue'])
		|| empty($_POST['code_postal']) || empty($_POST['ville']) || empty($_POST['departement']) || empty($_POST['description'])){

		$message = urlencode(utf8_encode("Les champs obligatoires n'ont pas été correctement saisis"));
		$end_url_redirect='?statut_reponse=fail&message_reponse='.$message;
	}
	else{


		//on insère le bien
		$query="INSERT INTO bien_immobilier (prix, superficie, nb_pieces, descriptif, id_personne_gest,parking) 
				VALUES(:prix, :superficie, :nb_pieces, :descriptif, :id_personne_gest,:parking) ";

		$parking='NULL';
		if(!empty($_POST['parking']) && ($_POST['parking']==0 || $_POST['parking']==1))
			$parking = $_POST['parking'];

		$insert_ok = false;
		$stmt = myPDO::getSingletonPDO()->prepare($query);
		$insert_ok = $stmt->execute(array(':prix'=>$_POST['prix'],':superficie'=>$_POST['superficie'],':nb_pieces'=>$_POST['nb_pieces'],
										  ':descriptif'=>$_POST['description'],':id_personne_gest'=>$_SESSION['id_personne'],':parking'=>$parking));
		$id_bien = myPDO::getSingletonPDO()->lastInsertId();
		$stmt->closeCursor();

		if($insert_ok)
			$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("L'ajout s'est bien passé!"));
		else{
			$end_url_redirect='?statut_reponse=fail&message_reponse='.urlencode(utf8_encode("L'ajout a echoué ..."));
			header('Location: ../ajoutBien.php'.$end_url_redirect);
			die();
		}

		//adresse
		$adress_is_exist = adresseIsExistInDataBase($_POST['numero_rue'], $_POST['rue'], $_POST['ville'], $_POST['code_postal']);
		if($adress_is_exist){
			$stmt = myPDO::getSingletonPDO()->query("UPDATE bien_immobilier SET id_adresse = {$adress_is_exist['id_adresse']} WHERE id_bien_immobilier=$id_bien");
			$stmt->closeCursor();
		}
		else{
			$stmt =  myPDO::getSingletonPDO()->prepare("INSERT INTO adresse (numero_rue,rue,ville,code_postal,id_departement) 
														VALUES(:numero_rue,:rue,:ville,:code_postal,:id_departement)");

			$stmt->execute(array(	':numero_rue'=>$_POST['numero_rue'],':rue'=>$_POST['rue'],':ville'=>$_POST['ville'],':code_postal'=>$_POST['code_postal'],
									':id_departement'=>$_POST['departement']));

			$id_adresse = myPDO::getSingletonPDO()->lastInsertId();
			$stmt->closeCursor();

			$stmt=myPDO::getSingletonPDO()->query("UPDATE bien_immobilier SET id_adresse=$id_adresse WHERE id_bien_immobilier=$id_bien ");
			$stmt->closeCursor();
		}

		//specificités
		if($_POST['type_bien_radio'] == IMMEUBLE){
			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO immeuble (id_bien_immobilier) VALUES ($id_bien)");
			$stmt->closeCursor();
		}
		elseif($_POST['type_bien_radio'] == GARAGE){
			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO garage (id_bien_immobilier) VALUES ($id_bien)");
			$stmt->closeCursor();
		}
		elseif($_POST['type_bien_radio'] == MAISON){
			$superficie_jardin=0;
			if(!empty($_POST['superficie_jardin']) && is_numeric($_POST['superficie_jardin']) && $_POST['superficie_jardin'] >=0)
				$superficie_jardin = $_POST['superficie_jardin'];

			if(!empty($_POST['superficie_jardin']) && !is_numeric($_POST['superficie_jardin']))
				$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("L'ajout s'est bien passé! Mais la supérficie a été ignoré: Mauvaise donnée"));

			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO maison (id_bien_immobilier,superficie_jardin) VALUES ($id_bien,$superficie_jardin)");
			$stmt->closeCursor();
		}
		elseif($_POST['type_bien_radio'] == APPARTEMENT){
			$etage='NULL'; $ascenseur='NULL'; $numero_appartement='NULL';

			if(!empty($_POST['etage'])){
				if(is_numeric($_POST['etage']))
					$etage = $_POST['etage'];
				else
					$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("L'ajout s'est bien passé! Mais l'étage a été ignoré: Mauvaise donnée"));
			}

			if(!empty($_POST['ascenseur'])){
				if(is_numeric($_POST['ascenseur']) && ($_POST['ascenseur'] == 1 || $_POST['ascenseur'] == 0))
					$etage = $_POST['ascenseur'];
				else
					$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("L'ajout s'est bien passé! Mais l'ascenseur a été ignoré: Mauvaise donnée"));
			}

			if(!empty($_POST['numero_appartement'])){
				if(is_numeric($_POST['numero_appartement']) && $_POST['numero_appartement'] > 0)
					$etage = $_POST['ascenseur'];
				else{
					$end_url_redirect='?statut_reponse=ok&message_reponse=';
					$end_url_redirect.=urlencode(utf8_encode("L'ajout s'est bien passé! Mais le numero d'appartement a été ignoré: Mauvaise donnée"));
				}
			}

			$stmt = myPDO::getSingletonPDO()->query("INSERT INTO appartement (id_bien_immobilier,etage,ascenseur, numero_appartement) 
												     VALUES ($id_bien,$etage, $ascenseur, $numero_appartement)");
			$stmt->closeCursor();
		}
	}


	header('Location: ../ajoutBien.php'.$end_url_redirect);
