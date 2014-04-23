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

	
	
	function getDateFormatedResultBase($date_to_print){
		return (new DateTime($date_to_print,new DateTimeZone('Europe/Paris')))->format('d/m/Y');
	}

	function getDateFormatedResultDetails($date_to_compare){
		$date_return		='';
		if(!empty($date_to_compare)){
			$time_zone 			= new DateTimeZone('Europe/Paris');
			$date_to_compare 	= new DateTime($date_to_compare, $time_zone);
			$date_current 		= new DateTime(NULL,$time_zone);
			$date_diff 			= $date_current->diff($date_to_compare);
			$time_zone 			= NULL; //opti
			//var_dump($date_diff);
			
			// si mois > 5 on affiche pas de détails
			if($date_diff->m == 0){
				// jours
				if($date_diff->d == 0){
					//heures
					if($date_diff->h == 0){
						//minutes
						if($date_diff->i == 0){
							$date_return = "Il y a ".$date_diff->s." seconde";

							if($date_diff->s > 1)
								$date_return.='s';
						}
						else{
							$date_return = "Il y a ".$date_diff->i." minute";

							if($date_diff->i > 1)
								$date_return.='s';
						}
					}
					else{
						$date_return = "Il y a ".$date_diff->h." heure";

						if($date_diff->h > 1)
							$date_return .='s';
					}
				}
				elseif($date_diff->d < 32){
					$date_return = "Il y a ".$date_diff->d." jour";
					if($date_diff->d > 1)
						$date_return.='s';
				}
				else{
					$date_return = ''; // secu
				}
			}
			elseif($date_diff->m <6){
				$date_return="Il y a ".$date_diff->m." mois";
			}
		}
		
		return $date_return;
	}

