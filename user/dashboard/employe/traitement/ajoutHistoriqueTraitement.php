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

	if(empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != EMPLOYE || empty($_POST['id_bien_immobilier'])){
		header('Location: '.getPathRoot().'index.php',false,301);
		die();
	}


	$end_url_redirect='';

	if(  empty($_POST['prix']) || !is_numeric($_POST['prix']) || $_POST['prix'] <=0 || empty($_POST['nom_action']) 
		|| empty($_POST['descriptif']) || empty($_POST['date']) || !isset($_POST['imputation']) || ($_POST['imputation'] != 0 && $_POST['imputation'] != 1)
		|| empty($_POST['type_operation']) ||  ($_POST['type_operation'] != "entree" && $_POST['type_operation'] != "depense") ){

		$message=urlencode(utf8_encode("Erreur: les paramètres n'ont pas été correctement saisis"));
		$end_url_redirect='?statut_reponse=fail&message_reponse='.$message;
	}
	else{
		if(!preg_match('/^(([0-2][0-9])|([3][0-1]))\/(([0][0-9])|([1][0-2]))\/([0-9]{4})$/', $_POST['date'])){
			$message=urlencode(utf8_encode("Erreur: problème de formatage de la date"));
			$end_url_redirect='?statut_reponse=fail&message_reponse='.$message;
		}
		else{
			$date_timestamp='';
			try{
				$date_timestamp = date_create_from_format ('d/m/Y',$_POST['date'],new DateTimeZone('Europe/Paris'))->getTimeStamp();
			}
			catch(Exception $e){
				$end_url_redirect='?statut_reponse=fail&message_reponse='.urlencode(utf8_encode($e->getMessage()));
			}

			if($date_timestamp){
				//on insere le nouveau histo dans la bd
				$id_locataire='';

				if($_POST['imputation']){
					$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM bien_immobilier WHERE id_bien_immobilier={$_POST['id_bien_immobilier']}");
					$id_locataire = $stmt->fetch();

					if(!($id_locataire = $id_locataire['id_personne_locataire'])){
						$end_url_redirect='?statut_reponse=fail&message_reponse=';
						$end_url_redirect.=urlencode(utf8_encode("Il n'y a actuellement pas de locataires pour l'imputation"));
						$end_url_redirect.='&id_bien_immobilier='.$_POST['id_bien_immobilier'];
						header('Location: ../ajoutHistorique.php'.$end_url_redirect);
						die();
					}
				}

				$stmt = myPDO::getSingletonPDO()->prepare("	INSERT INTO historique (nom_action,prix_action,descriptif_action,id_bien_immobilier)
															VALUES (:nom_action,:prix_action,:descriptif_action,:id_bien_immobilier)");

				$stmt->execute(array(	':nom_action'=>$_POST['nom_action'],':prix_action'=>$_POST['prix'],':descriptif_action'=>$_POST['descriptif'],
										':id_bien_immobilier'=>$_POST['id_bien_immobilier']));

				$id_historique = myPDO::getSingletonPDO()->lastInsertId();
				$stmt->closeCursor();

				if($_POST['type_operation'] == 'entree'){
					$stmt=myPDO::getSingletonPDO()->query("INSERT INTO historique_entree (id_historique) VALUES ($id_historique)");
					$stmt->closeCursor();
					$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("Historique d'entrée ajouté"));
				}
				else{
					//depense
					$stmt=myPDO::getSingletonPDO()->query("INSERT INTO historique_depense (id_historique) VALUES ($id_historique)");
					$stmt->closeCursor();

					if($_POST['imputation']){

						$stmt=myPDO::getSingletonPDO()->query("	UPDATE historique_depense SET id_personne_impute=$id_locataire
																WHERE id_historique=$id_historique");
						$stmt->closeCursor();
						$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("Historique de dépense et d'imputation ajouté"));
					}
					else
						$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("Historique de dépense ajouté"));
				}
			}
			else
				$end_url_redirect='?statut_reponse=fail&message_reponse='.urlencode(utf8_encode("Erreur sur la date"));
		}
	}

	header('Location: ../ajoutHistorique.php'.$end_url_redirect.'&id_bien_immobilier='.$_POST['id_bien_immobilier']);
