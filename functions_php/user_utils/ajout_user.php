<?php
	require_once('../settings/connexion.php');
	require_once('enum_type_user.php');
	

	function userIsExistFromLogin($login){
		try{
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT * FROM personne WHERE login =:login");
			$login = mysql_real_escape_string(htmlentities($login));
			$stmt->execute(array("login"=>$login));
			$res = $stmt->fetch();
			$stmt->closeCursor();
			if($res)
				return $res['id_personne'];
			return false;
		}
		catch(PDOException $e){
			die("Probleme PDO userisexist".$e->getMessage());
		}
	}

	//password clair, return true si insertion ok sinon false
	function addPersonne($nom,$prenom,$login,$password,$type){
		$login = mysql_real_escape_string(htmlentities($login));
		if(userIsExistFromLogin($login))
			return false;

		// pas de doublon on insere
		try{
			$stmt_photo = myPDO::getSingletonPDO()->query("SELECT * FROM photo WHERE UPPER(chemin_photo) LIKE UPPER('%img/defaut%')");
			if($ligne=$stmt_photo->fetch())
				$id_photo = $ligne['chemin_photo'];
			else
				$id_photo = null;
			$stmt_photo->closeCursor();

			$prenom = mysql_real_escape_string(htmlentities($prenom));
			$nom 	= mysql_real_escape_string(htmlentities($nom));
			$stmt = myPDO::getSingletonPDO()->prepare("INSERT INTO personne (nom_personne,prenom_personne,login,password,id_photo) VALUES(:nom_personne,:prenom_personne,:login,:password, :id_photo)");
			$stmt->execute(array(	'nom_personne'=>$nom,
									'prenom_personne'=>$prenom,
									'login'=>$login,
									'password'=>sha1($password),
									'id_photo'=>$id_photo));
			$stmt->closeCursor();

			//par securite on va chercher l'insertion par le login (car pas de doublon) au lieu de last insert
			$id_personne = userIsExistFromLogin($login);

			//type personne
			$stmt_type = myPDO::getSingletonPDO()->prepare("INSERT INTO ".$type."(id_personne) VALUES (:id_personne)");
			$stmt_type->execute(array("id_personne"=>$id_personne));
			$stmt_type->closeCursor();
		}
		catch(Exception $e){
			//mail('servicetech@agence_immo.com','Pb PDO','Probleme PDO dans addPersonne');
			die("Probleme reseau: un mail a ete envoye au service technique (".$e->getMessage().")");
		}
		
	}

	