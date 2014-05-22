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

	if(empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != EMPLOYE || empty($_POST['id_bien_immobilier'])
		){
		header('Location: '.getPathRoot().'index.php',false,301);
		die();
	}


	$end_url_redirect='';

	if(  empty($_POST['prix']) || !is_numeric($_POST['prix']) || $_POST['prix'] <=0 || empty($_POST['motif']) || empty($_POST['date'])){
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
				//on insere le nouveau paiement dans la bd
				$id_locataire='';
				$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM bien_immobilier WHERE id_bien_immobilier={$_POST['id_bien_immobilier']}");
				$id_locataire = $stmt->fetch();

				if($id_locataire = $id_locataire['id_personne_locataire']){
					
					$stmt->closeCursor();
					$stmt = myPDO::getSingletonPDO()->prepare("	INSERT INTO historique (nom_action,prix_action,descriptif_action,id_bien_immobilier,date_historique)
																VALUES (:nom_action,:prix_action,:descriptif_action,:id_bien_immobilier, FROM_UNIXTIME(:date_historique))");
					
					$stmt->execute(array(	':nom_action'=>$_POST['motif'],':prix_action'=>$_POST['prix'],':descriptif_action'=>$_POST['motif'],
											':id_bien_immobilier'=>$_POST['id_bien_immobilier'],':date_historique'=>$date_timestamp));

					$id_historique = myPDO::getSingletonPDO()->lastInsertId();
					$stmt->closeCursor();

					$stmt = myPDO::getSingletonPDO()->prepare("	INSERT INTO paiement (date_paiement,montant_paiement,motif_paiement,id_personne_payeur,id_historique)
																VALUES ( FROM_UNIXTIME(:date_paiement),:montant_paiement,:motif_paiement,:id_personne_payeur,:id_historique)");
					$stmt->execute(array(	':date_paiement'=>$date_timestamp,':montant_paiement'=>$_POST['prix'],':motif_paiement'=>$_POST['motif'],
											':id_personne_payeur'=>$id_locataire,':id_historique'=>$id_historique));

					$id_paiement = myPDO::getSingletonPDO()->lastInsertId();
					$stmt->closeCursor();

					$stmt = myPDO::getSingletonPDO()->query("	INSERT INTO historique_entree (id_historique,id_paiement) VALUES ($id_historique,$id_paiement)");
					$stmt->closeCursor();

					$end_url_redirect='?statut_reponse=ok&message_reponse='.urlencode(utf8_encode("Le paiement à bien été ajouté"));
				}
				else{
					$end_url_redirect='?statut_reponse=fail&message_reponse='.urlencode(utf8_encode("Il n'y a actuellement pas de locataires"));
				}
				$stmt->closeCursor();
			}
			else
				$end_url_redirect='?statut_reponse=fail&message_reponse='.urlencode(utf8_encode("Erreur sur la date"));
		}
	}


	header('Location: ../ajoutPaiement.php'.$end_url_redirect.'&id_bien_immobilier='.$_POST['id_bien_immobilier']);
