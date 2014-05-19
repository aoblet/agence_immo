<?php
	require_once(dirname(__FILE__).'/../../../functions_php/settings/connexion.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php');

	session_start();
	if(	!isset($_SESSION['id_personne']) || empty($_SESSION['id_personne']) || empty($_SESSION['id_destinataire_for_message'])
		|| !isset($_POST['contenu_message']) || !isset($_SESSION['id_bien_immobilier_for_message'])
		|| empty($_SESSION['id_bien_immobilier_for_message'])){

		$link_home = getPathRoot().'index.php';
		header('Location: '.$link_home,false,301);
		die();
	}

	$contenu_message = trim($_POST['contenu_message']);
	if(empty($contenu_message)){
		header('Location: '.trim($_POST['come_from']),false,301);
		die();
	}

	$id_auteur = $_SESSION['id_personne'];
	$id_destinataire= $_SESSION['id_destinataire_for_message'];
	$contenu_message = myPDO::my_escape_string(htmlspecialchars($contenu_message));
	$id_bien_immobilier = myPDO::my_escape_string(htmlspecialchars($_SESSION['id_bien_immobilier_for_message']));

	$query=<<<SQL
		INSERT 	INTO message (id_auteur,id_destinataire,contenu_message,traite,id_bien_immobilier) 
				VALUES ($id_auteur,$id_destinataire,'$contenu_message',0,$id_bien_immobilier)
SQL;
	$stmt=myPDO::getSingletonPDO()->query($query);
	$stmt->closeCursor();

	//301 "cache" la page à l'user
	header('Location: '.trim($_POST['come_from']),false,301);
	die();


