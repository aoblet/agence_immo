<?php
	require_once(dirname(__FILE__).'/../settings/connexion.php');
	
	function userIsExistFromMail($mail){
		try{
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT * FROM personne WHERE mail =:mail");
			$mail = myPDO::my_escape_string(htmlentities($mail));
			$stmt->execute(array("mail"=>$mail));
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