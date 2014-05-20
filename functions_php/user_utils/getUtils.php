<?php
	require_once(dirname(__FILE__).'/../../enum/enum_type_user.php');
	require_once(dirname(__FILE__).'/../settings/connexion.php');


	function getTypePersonne($id_personne){
		$type = array(PROPRIETAIRE,LOCATAIRE,EMPLOYE);
		foreach ($type as $value) {
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT id_personne FROM ".$value." WHERE id_personne=:id_personne");
			$stmt->execute(array( "id_personne"=>$id_personne));
			$type = $stmt->fetch();
			$stmt->closeCursor();
			if($type)
				return $value;
		}
		return NULL;
	}

	function getPhotoPersonne($id_personne){
		$stmt = myPDO::getSingletonPDO()->query("SELECT chemin_photo FROM photo WHERE id_photo = (SELECT id_photo FROM personne WHERE id_personne = $id_personne)");
		$res = $stmt->fetch();
		$stmt->closeCursor();
		return $res;
	}
