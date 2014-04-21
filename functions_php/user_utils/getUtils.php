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


