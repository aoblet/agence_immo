<?php
	require_once(dirname(__FILE__).'/../settings/connexion.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_biens.php');
	
	
	function getTypeBien($id_bien_immobilier){
		$type = array(APPARTEMENT,MAISON,IMMEUBLE,GARAGE);
		foreach ($type as $value) {
			$stmt = myPDO::getSingletonPDO()->query("SELECT DISTINCT * FROM {$value} WHERE id_bien_immobilier = $id_bien_immobilier");
			$res = $stmt->fetch();
			$stmt->closeCursor();
			if($res)
				return $value;			
		}
		return NULL; // probleme aucune correspondance..
	}

	/* GET ALL */
	// return table
	function getAllBiensFromType($type){
		try{
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT id_bien_immobilier FROM {$type}");
			$stmt->execute();
			$res = array();

			while($ligne = $stmt->fetch()){
				$res[]=$ligne;
			}
			
			$stmt->closeCursor();
			return $res;
		}
		catch(Exception $e){
			return NULL;
		}	
	}

	function getGaz(){
		$stmt = myPDO::getSingletonPDO()->query("SELECT DISTINCT * FROM gaz_a_effet_de_serre_classe");
		$res  = array();
		while($ligne = $stmt->fetch()){
			$res[]=$ligne;
		}
		$stmt->closeCursor();
		return $res;
	}

	function getChauffages(){
		$stmt = myPDO::getSingletonPDO()->query("SELECT DISTINCT * FROM type_chauffage");
		$res  = array();
		while($ligne = $stmt->fetch()){
			$res[]=$ligne;
		}
		$stmt->closeCursor();
		return $res;
	}

	function getConsosEnergetiques(){
		$stmt = myPDO::getSingletonPDO()->query("SELECT DISTINCT * FROM consommation_energetique_classe");
		$res  = array();
		while($ligne = $stmt->fetch()){
			$res[]=$ligne;
		}
		$stmt->closeCursor();
		return $res;
	}

	function getAllDepartements(){
		$stmt = myPDO::getSingletonPDO()->query("SELECT DISTINCT * FROM departement ORDER BY code_departement");
		$res  = array();
		while($ligne = $stmt->fetch()){
			$res[]=$ligne;
		}
		$stmt->closeCursor();
		return $res;
	}