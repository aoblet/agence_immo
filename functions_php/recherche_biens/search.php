<?php
	require_once('../settings/connexion.php');
	require_once('./enum_type_biens.php');
	require_once('./getUtils.php');
	require_once('./getInfos.php');
	require_once('./isValid.php');

	/*
	 return table correpsondant aux critères ou si id_bien_immo ok => infos uniquement de celui ci
	 $orderby: prix, superficie, nb_pieces
	 param : region,dep,gaz,chaffage,conso elec : id
	 		 gaz, conso elect, type chauffage : tableau
	 CF LES DEP REGIONS: CODE OU NOM??
	 
	 opt array indices : id_bien_immobilier, types_bien, type_achat_location, budget_mini, budget_maxi, ville, region, departement, nb_pieces,
	 					 superficie_mini, superficie_maxi, gaz_effet_serre, type_chauffage, conso_electrique, order_by
	 */
	function search($opt=NULL){

		if(empty($opt)){
			$query=" SELECT * FROM bien_immobilier";
		}
		elseif(!empty($opt['id_bien_immobilier'])){
			$query=" SELECT * FROM bien_immobilier WHERE id_bien_immobilier = {$opt['id_bien_immobilier']} ";
		}
		else{
			//recupere tous les id des types de biens		
			$clause_types_bien='';
			if(!empty($opt['types_bien'])){
				foreach ($opt['types_bien'] as $value){
					if($value == IMMEUBLE || $value == APPARTEMENT || $value == MAISON ||$value == GARAGE){
						if($isNotNull=getAllBiensFromType($value)){
							foreach ($isNotNull as $value_id) {
								$clause_types_bien.=" id_bien_immobilier={$value_id['id_bien_immobilier']} OR ";
							}
						}
					}	
				}
				if(!empty($clause_types_bien))
					$clause_types_bien = ' AND ( '.$clause_types_bien." '')";
			}


			// type achat ou loc
			$clause_type_achat_location = 'AND id_personne_locataire IS NULL '; // on prends les biens non loues 

			if(!empty($opt['type_achat_location'])){
				if($opt['type_achat_location'] == 'location')
					$clause_type_achat_location = 'AND id_agence_vendeur IS NULL';
				elseif($opt['type_achat_location'] == 'vente')
					$clause_type_achat_location= 'AND id_agence_loueur IS NULL AND id_personne_proprio IS NULL';
			}


			//budget
			$clause_budget='';
			if(!empty($opt['budget_mini']) && !empty($opt['budget_maxi']) && is_numeric($opt['budget_mini']) && is_numeric($opt['budget_maxi']))
				$clause_budget = "prix BETWEEN {$opt['budget_mini']} AND {$opt['budget_maxi']}";


			//ville
			$clause_ville='';
			if(!empty($opt['ville'])){
				$ville = mysql_real_escape_string($opt['ville']);
				$clause_ville = " AND bien_immobiler.id_adresse = (SELECT DISTINCT id_adresse FROM adresse WHERE UPPER(VILLE) LIKE UPPER('%{$opt['ville']}%'))";
			}


			//departement
			$clause_departement='';
			if(!empty($opt['departement'])){
				$clause_departement = " AND bien_immobiler.id_adresse = (SELECT DISTINCT id_adresse FROM adresse WHERE id_departement = {$opt['departement']})";
			}


			//region
			$clause_region='';
			if(!empty($opt['region'])){
				$clause_region = " AND bien_immobiler.id_adresse = (SELECT DISTINCT id_adresse FROM adresse WHERE id_departement = 
																		(SELECT DISTINCT id_departement FROM departement WHERE id_region = {$opt['region']}))";
																			
			}


			//nb pieces
			$clause_nb_piece ='';
			if(!empty($opt['nb_pieces']) && is_numeric($opt['nb_pieces']))
				$clause_nb_piece = " AND nb_pieces = {$opt['nb_pieces']} ";


			//superficie
			$clause_superficie ='';
			if(!empty($opt['superficie_mini']) && !empty($opt['superficie_maxi']) && is_numeric($opt['superficie_mini']) && is_numeric($opt['superficie_maxi']) ){
				$clause_superficie=" AND superficie BETWEEN {$opt['superficie_mini']} AND {$opt['superficie_maxi']} ";
			}


			//gaz effet de serre
			$clause_gaz='';
			if(!empty($opt['gaz_effet_serre'])){
				foreach ($opt['gaz_effet_serre'] as $value) {
					if(isValidGaz($value))
						$clause_gaz.=" id_gaz = $value OR ";
				}
				if(!empty($clause_gaz))
					$clause_gaz = " AND ( ".$clause_gaz." '')"; 
			}


			//conso electrique
			$clause_conso_electrique='';
			if(!empty($opt['conso_electrique'])){
				foreach ($opt['conso_electrique'] as $value) {
					if(isValidConsoElectrique($value))
						$clause_conso_electrique.=" id_consommation_energetique = $value OR ";
				}
				if(!empty($clause_conso_electrique))
					$clause_gaz = " AND ( ".$clause_conso_electrique." '')";
			}


			//type chauffage 
			$clause_type_chauffage='';
			if(!empty($opt['type_chauffage'])){
				foreach ($opt['type_chauffage'] as $value) {
					if(isValidChauffage($value))
						$clause_type_chauffage.=" id_type_chauffage = $value OR ";
				}
				if(!empty($clause_type_chauffage))
					$clause_gaz = " AND ( ".$clause_type_chauffage." '')";
			}


			//orderby
			$clause_order_by='';
			if(!empty($opt['order_by'])){
				if($opt['order_by'] == 'prix' || $opt['order_by'] == 'superficie' || $opt['order_by'] =='nb_pieces')
				$clause_order_by = " ORDER BY {$opt['order_by']}";
			}
			

			$query = "SELECT * FROM bien_immobilier WHERE 1=1 ".$clause_types_bien.$clause_type_achat_location.$clause_budget.$clause_superficie;
			$query.= $clause_departement.$clause_region.$clause_nb_piece;
			$query.= $clause_gaz.$clause_conso_electrique.$clause_type_chauffage.$clause_order_by;
		}
		
		echo $query;
		$stmt = myPDO::getSingletonPDO()->query($query); 

		$resultas = array();
		while($ligne = $stmt->fetch()){
			$ligne['infos_conso_energetique'] = getInfosConsoEnergetique($ligne['id_bien_immobilier']);
			$ligne['infos_gaz'] = getInfosGaz($ligne['id_bien_immobilier']);
			$ligne['infos_chauffage'] = getInfosChauffage($ligne['id_bien_immobilier']);
			$ligne['infos_adresse'] = getInfosAdresse($ligne['id_bien_immobilier']);	

			$resultas[]=$ligne;
		}

		$stmt->closeCursor();
		return $resultas;
	}

	// rajoute d'autres informations
	function search_for_admin(){}
