<?php
	session_start();
	require_once('../functions_php/settings/connexion.php');

	function userIsType($type,$id_personne){
		$stmt = $bdd->prepare("SELECT id_personne FROM :type WHERE id_personne=:id_personne");
		$stmt->execute(array("type"=>$type, "id_personne"=>$id_personne));
		$type = $stmt->fetch();
		$stmt->closeCursor();

		if($stmt->fetch())
			return true;
		return false;
	}


	if(isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password'])){
		$login = mysql_escape_string(htmlentities($_POST['login']));
		$password = mysql_escape_string(htmlentities($_POST['password']));

		$stmt = $bdd->prepare("SELECT * FROM personne WHERE login=:login AND password=:password");
		$stmt->execute(array("login"=>$login, "password"=>sha1($password)));

		if( $ligne=$stmt->fetch() ){
			$_SESSION['id_personne']=$ligne['id_personne'];
			$_SESSION['nom_personne']=$ligne['nom_personne'];
			$_SESSION['prenom_personne']=$ligne['prenom_personne'];
			$_SESSION['user_name_personne']=$ligne['user_name_personne'];
			$_SESSION['photo_personne']=$ligne['photo_personne'];
			$stmt->closeCursor();

			//type_personne
			if(userIsType("employe",$ligne['id_personne']))
				$type_personne = 'employe';
			elseif(userIsType("locataire",$ligne['id_personne']))
				$type_personne = 'locataire';
			elseif(userIsType("proprietaire",$ligne['id_personne']))
				$type_personne = 'proprietaire';
			else
				die("Probleme au niveau de la base de donnees : type personnes");

			$_SESSION['type_personne']=$type_personne;
			
		}
		else{
			header('Location: ../index.php?err_compte=wrong_login_password');
		}
	}
	else{
		header('Location: ../index.php?err_compte=wrong_use');
	}