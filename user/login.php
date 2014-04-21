<?php
	session_start();
	require_once('../functions_php/settings/connexion.php');
	require_once('../functions_php/user_utils/enum_type_user.php');
	require_once('../functions_php/user_utils/getUtils.php');

	if(!empty($_SESSION['id_personne'])){
		header('Location: ../index.php');
		die();
	}
		
	if(isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])){
		$login = mysql_real_escape_string(htmlentities($_POST['login']));
		$password = mysql_real_escape_string(htmlentities($_POST['password']));

		$query="SELECT *,chemin_photo 
				FROM personne pe, photo ph 
				WHERE login=:login AND password=:password
				AND pe.id_photo = ph.id_photo";

		$stmt = myPDO::getSingletonPDO()->prepare($query);
		$stmt->execute(array("login"=>$login, "password"=>sha1($password)));

		if( $ligne=$stmt->fetch() ){
			$_SESSION['id_personne']=$ligne['id_personne'];
			$_SESSION['nom_personne']=$ligne['nom_personne'];
			$_SESSION['login']=$ligne['login'];
			$_SESSION['prenom_personne']=$ligne['prenom_personne'];
			$_SESSION['photo_personne']=$ligne['chemin_photo'];
			$stmt->closeCursor();

			$_SESSION['type_personne']=getTypePersonne($ligne['id_personne']);
			header('Location: ../index.php');			
		}
		else{
			header('Location: ../index.php?err_compte=wrong_login_password');
		}
	}
	else{
		header('Location: ../index.php?err_compte=wrong_use');
	}