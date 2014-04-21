<?php
	require_once(dirname(__FILE__).'/../settings/connexion.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_biens.php');

	/* IS VALID */
	function isValidGaz($id_gaz){
		$gazs = getGaz();
		foreach ($gazs as $value) {
			if($value['id_gaz']==$id_gaz)
				return true;
		}
		return false;
	}

	function isValidChauffage($id_chauffage){
		$chauffages = getChauffages();
		foreach ($chauffages as $value) {
			if($value['id_type_chauffage']==$id_chauffage)
				return true;
		}
		return false;
	}

	function isValidConsoElectrique($id_conso_electrique){
		$consos = getConsosElectriques();
		foreach ($consos as $value) {
			if($value['id_consommation_energetique'] == $id_conso_electrique)
				return true;
		}
		return false;
	}