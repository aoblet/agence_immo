<?php
	require_once(dirname(__FILE__).'/../settings/connexion.php');
	require_once(dirname(__FILE__).'/isExist.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_user.php');
	

	//password clair, return true si insertion ok sinon false
	function addPersonne($nom,$prenom,$mail,$password,$type,$photo="img/avatar.png"){
		$mail = myPDO::my_escape_string(htmlentities($mail));
		
		if(userIsExistFromMail($mail)) // pas de doublon on insere
			return false;

		try{
			//recup photo
			$stmt_photo = myPDO::getSingletonPDO()->query("SELECT * FROM photo WHERE UPPER(chemin_photo) LIKE UPPER('%{$photo}%')");
			if($ligne=$stmt_photo->fetch())
				$id_photo = $ligne['id_photo'];
			else
				$id_photo = null;
			$stmt_photo->closeCursor();

			$prenom = myPDO::my_escape_string(htmlentities($prenom));
			$nom 	= myPDO::my_escape_string(htmlentities($nom));
			$stmt = myPDO::getSingletonPDO()->prepare("INSERT INTO personne (nom_personne,prenom_personne,mail,password,id_photo) VALUES(:nom_personne,:prenom_personne,:mail,:password, :id_photo)");
			$stmt->execute(array(	'nom_personne'=>$nom,
									'prenom_personne'=>$prenom,
									'mail'=>$mail,
									'password'=>sha1($password),
									'id_photo'=>$id_photo));
			$stmt->closeCursor();

			//par securite on va chercher l'insertion par le mail (car pas de doublon) au lieu de last insert
			$id_personne = userIsExistFrommail($mail);

			//type personne
			$stmt_type = myPDO::getSingletonPDO()->prepare("INSERT INTO ".$type."(id_personne) VALUES (:id_personne)");
			$stmt_type->execute(array("id_personne"=>$id_personne));
			$stmt_type->closeCursor();
		}
		catch(Exception $e){
			//mail('servicetech@agence_immo.com','Pb PDO','Probleme PDO dans addPersonne: '.$e->getMessage());
			die("Probleme reseau: un mail a ete envoye au service technique");
		}
		
	}

	/*if(addPersonne("alexis","admin","admin2","password","employe") != false)
		echo "insertion ok";
	else 
		echo 'insertion faux';*/