<?php
	require_once(dirname(__FILE__).'/../../../functions_php/settings/connexion.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils.php');

	session_start();

	if(	!isset($_SESSION['id_personne']) || empty($_SESSION['id_personne']) || empty($_GET['id_bien_immobilier']) || 
		!is_numeric($_GET['id_bien_immobilier']) || !getLegitimite($_SESSION,$_GET['id_bien_immobilier']) ){

		$link_home = getPathRoot().'index.php';
		header('Location: '.$link_home,false,301);
		die();
	}

	$end_url_redirect=''; $id_destinataire='';
	if($_SESSION['type_personne'] == EMPLOYE){
		// traitement différent : id_destinataire en plus
		$end_url_redirect='';
	}
	else{
		$id_destinataire = getIdGestionnaire($_GET['id_bien_immobilier']);
		$conversation = getConversation($_GET['id_bien_immobilier'],$_SESSION['id_personne'],$id_destinataire,true);
		if($conversation && $conversation[0]['id_destinataire'] == $_SESSION['id_personne'] ){
			//on update tous les messages de la conversation

			$query = "	UPDATE message set traite=1 
						WHERE 	( (id_destinataire={$_SESSION['id_personne']} AND id_auteur=$id_destinataire) OR 
								(id_destinataire=$id_destinataire AND id_auteur={$_SESSION['id_personne']}) ) AND 
							  	message.id_bien_immobilier ={$conversation[0]['id_bien_immobilier']}";
			$stmt = myPDO::getSingletonPDO()->query($query);
			$stmt->closeCursor();
		}
	}
	
	//301 "cache" la page à l'user
	$url=getPathRoot().'user/dashboard/'.strtolower($_SESSION['type_personne']).'/message.php?id_bien_immobilier='.$_GET['id_bien_immobilier'].$end_url_redirect."#last_message_ancre";
	header('Location: '.$url, false,301);
	die();


