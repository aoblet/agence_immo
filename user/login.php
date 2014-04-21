<?php
	session_start();
	require_once('../functions_php/settings/connexion.php');
	require_once('../enum/enum_type_user.php');
	require_once('../functions_php/user_utils/getUtils.php');

	if(!empty($_SESSION['id_personne'])){
		header('Location: ../index.php');
		die();
	}
		
	if(isset($_POST['mail']) && isset($_POST['password']) && !empty($_POST['mail']) && !empty($_POST['password'])){
		$mail = mysql_real_escape_string(htmlentities($_POST['mail']));
		$password = mysql_real_escape_string(htmlentities($_POST['password']));

		$query="SELECT *,chemin_photo 
				FROM personne pe, photo ph 
				WHERE mail=:mail AND password=:password
				AND pe.id_photo = ph.id_photo";

		$stmt = myPDO::getSingletonPDO()->prepare($query);
		$stmt->execute(array("mail"=>$mail, "password"=>sha1($password)));

		if( $ligne=$stmt->fetch() ){
			$_SESSION['id_personne']=$ligne['id_personne'];
			$_SESSION['nom_personne']=$ligne['nom_personne'];
			$_SESSION['mail']=$ligne['mail'];
			$_SESSION['prenom_personne']=$ligne['prenom_personne'];
			$_SESSION['photo_personne']=$ligne['chemin_photo'];
			$stmt->closeCursor();

			$_SESSION['type_personne']=getTypePersonne($ligne['id_personne']);
			header('Location: ../index.php');			
		}
		else{
			header('Location: ../index.php?err_compte=wrong_mail_password');
			die();
		}
	}
	else{
		header('Location: ../index.php?err_compte=wrong_use');
		die();
	}