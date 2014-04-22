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

	function isValidConsoEnergetique($id_consommation_energetique){
		$consos = getConsosEnergetiques();
		foreach ($consos as $value) {
			if($value['id_consommation_energetique'] == $id_consommation_energetique)
				return true;
		}
		return false;
	}