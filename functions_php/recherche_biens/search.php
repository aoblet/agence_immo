<?php
	require_once('../settings/connexion.php');

	function getTypeBien($id_bien_immobilier){
		$type = array('appartement','maison','immeuble','garage');
		foreach ($type as $value) {
			$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM {$value}");
			$res = $stmt->fetch();
			$stmt->closeCursor();
			if($res)
				return $value;			
		}
		return NULL; // probleme aucune correspondance..
	}

	function getAllBiensFromType($type){
		try{
			$stmt = myPDO::getSingletonPDO()->prepare("SELECT id_bien_immobilier FROM {$type}");
			$stm->excute();
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

	// VERIF A FAIRE ICI
	// retour tableau correpsondant aux critères
	// $orderby: prix, superficie, nb_pieces
	// verif a faire sur les types de biens en dehors
	// escape string a faire avant
	function search($types_bien, $type_achat_location, $budget_mini, $budget_maxi, $ville, $region, $departement, $nb_pieces,$order_by, $superficie_mini, $superficie_maxi){
		$table=' bien_immobiler';

		//types de biens
		$clause_types_bien='';
		if(!is_null($types_bien)){
			foreach ($types_bien as $value){
				if($isNotNull=getAllBiensFromType($value)){
					foreach ($isNotNull as $value_id) {
						$clause_types_bien.=" OR id_bien_immobilier=$value_id";
					}
				}
					
			}
		}

		// type achat ou loc
		$clause_type_achat_location = 'AND id_personne_loueur IS NULL '; // on prends les biens non loues 
		if($type_achat_location == 'location')
			$clause_type_achat_location = 'AND id_agence_vendeur IS NULL';
		elseif($type_achat_location == 'vente')
			$clause_type_achat_location= 'AND id_agence_loueur IS NULL AND id_personne_proprio IS NULL';

		//budget
		$clause_budget='';
		if(is_numeric($budget_mini) && is_numeric($budget_maxi))
			$clause_budget = "prix BETWEEN $budget_mini AND $budget_maxi";

		//ville
		$clause_ville='';
		if(!is_null($ville)){
			$clause_ville = " AND bien_immobiler.id_adresse = (SELECT DISTINCT id_adresse FROM adresse WHERE UPPER(VILLE) LIKE UPPER('%{$ville%}'))";
		}

		//departement
		$clause_departement='';
		if(!is_null($departement)){
			$clause_departement = " AND bien_immobiler.id_adresse = (SELECT DISTINCT id_adresse FROM adresse WHERE id_departement = {$departement})";
		}

		//region
		$clause_region='';
		if(!is_null($region)){
			$clause_region = " AND bien_immobiler.id_adresse = (SELECT DISTINCT id_adresse FROM adresse WHERE id_departement = 
																	(SELECT DISTINCT id_departement FROM departement WHERE id_region = {$region}))";
																		
		}

		//nb pieces
		$clause_nb_piece ='';
		if(!is_null($nb_pieces) && is_numeric($nb_pieces))
			$clause_nb_piece = " AND nb_pieces=$nb_pieces";

		//superficie
		$clause_superficie ='';
		if(!is_null($superficie_mini) && !is_null($superficie_maxi) && is_numeric($superficie_mini) && is_numeric($superficie_maxi)){
			$clause_superficie=" AND superficie BETWEEN $superficie_mini AND $superficie_maxi";
		}

		//orderby
		$clause_order_by='';
		if($order_by == 'prix' || $order_by == 'superficie' || $order_by =='nb_pieces')
			$clause_order_by = " ORDER BY $order_by";

		$query = "SELECT * FROM ".$table." WHERE 1=1 ".$clause_types_bien.$clause_type_achat_location.$clause_budget.$clause_superficie.$clause_departement.$clause_region.$clause_nb_piece.$clause_order_by;
		$stmt = myPDO::getSingletonPDO()->query($query);

		// comment organiser les adresses. voir demain
		
	}
