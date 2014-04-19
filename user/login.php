<?php
	session_start();
	require_once('../functions_php/settings/connexion.php');
	require_once('../functions_php/user_utils/enum_type_user.php');

	function getUserType($id_personne){
		$type = array(PROPRIETAIRE,LOCATAIRE,EMPLOYE);
		foreach ($type as $value) {
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT id_personne FROM ".$value." WHERE id_personne=:id_personne");
			$stmt->execute(array("type"=>$type, "id_personne"=>$id_personne));
			$type = $stmt->fetch();
			$stmt->closeCursor();

			if($stmt->fetch())
				return $value;
		}
		return NULL;
	}


	if(isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])){
		$login = mysql_real_escape_string(htmlentities($_POST['login']));
		$password = mysql_real_escape_string(htmlentities($_POST['password']));

		$stmt = myPDO::getSingletonPDO()->prepare("SELECT * FROM personne WHERE login=:login AND password=:password");
		$stmt->execute(array("login"=>$login, "password"=>sha1($password)));

		if( $ligne=$stmt->fetch() ){
			$_SESSION['id_personne']=$ligne['id_personne'];
			$_SESSION['nom_personne']=$ligne['nom_personne'];
			$_SESSION['prenom_personne']=$ligne['prenom_personne'];
			$_SESSION['user_name_personne']=$ligne['user_name_personne'];
			$_SESSION['photo_personne']=$ligne['photo_personne'];
			$stmt->closeCursor();

			$_SESSION['type_personne']=getUserType($ligne['id_personne']);
			header('Location: ../index.php');			
		}
		else{
			header('Location: ../index.php?err_compte=wrong_login_password');
		}
	}
	else{
		header('Location: ../index.php?err_compte=wrong_use');
	}