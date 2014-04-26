<?php
	session_start();
	// passerelle pour le dashboard
	require_once('../enum/enum_type_user.php');

	if(isset($_SESSION['id_personne']) && !empty($_SESSION['id_personne']) ){
		// par secu
		if(isset($_SESSION['type_personne']) && !empty($_SESSION['type_personne'])){
			if($_SESSION['type_personne'] == EMPLOYE){
				header('Location: dashboard/employe/');
				die();
			}

			if($_SESSION['type_personne'] == LOCATAIRE){
				header('Location: dashboard/locataire/');
				die();
			}

			if($_SESSION['type_personne'] == PROPRIETAIRE){
				header('Location: dashboard/proprietaire/');
				die();
			}
		}
	}
	
	header('Location: ../index.php');
	die();
	