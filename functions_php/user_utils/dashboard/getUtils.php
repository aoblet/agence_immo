<?php
	/**
	*	Partie commune aux dashs
	**/
	require_once(dirname(__FILE__).'/../../settings/connexion.php');
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php');


	function getLegitimite($session, $id_bien_immobilier){

		if(!isset($session['id_personne']) || !is_numeric($session['id_personne']))
			return false;
		$id_personne = $session['id_personne'];

		$query="SELECT id_bien_immobilier FROM bien_immobilier WHERE id_bien_immobilier = $id_bien_immobilier ";

		if($session['type_personne'] == PROPRIETAIRE){
			$query.=" AND id_personne_proprio = $id_personne";
		}
		elseif ($session['type_personne'] == EMPLOYE) {
			$query.=" AND id_personne_gest = $id_personne";
		}
		elseif ($session['type_personne'] == LOCATAIRE) {
			$query.=" AND id_personne_locataire = $id_personne";
		}
		else{
			return false;
		}

		$stmt = myPDO::getSingletonPDO()->query($query);
		$res = $stmt->fetch();
		$stmt->closeCursor();

		return ($res ? true : false);
	}

	function getInformationsIdentitePersonne($id_personne){
		if($id_personne == NULL)
			return NULL;

	}