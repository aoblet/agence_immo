<?php
	require_once(dirname(__FILE__).'/../settings/connexion.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_biens.php');
	require_once(dirname(__FILE__).'/getUtils.php');
	require_once(dirname(__FILE__).'/getInfos.php');
	require_once(dirname(__FILE__).'/isValid.php');

	// transforme les donnees du formulaire en option
	function formToArrayOpt($GET_form){
		$array_data_indices=array(	'id_bien_immobilier', 'types_bien','type_achat_location', 'budget_mini','budget_maxi','ville','region','departement','nb_pieces','superficie_mini',
									'superficie_maxi', 'gaz_effet_serre','type_chauffage','consos_energetiques', 'jardin', 'parking', 'nb_etages', 'ascenseur','order_by');

		$array_opt = array();
		foreach ($array_data_indices as $value) {
			if(isset($GET_form[$value]))
				$array_opt[$value] = $GET_form[$value];
		}

		return $array_opt;
	}

	function secureArray($array){
		if(!empty($array)){
			foreach ($array as $key => $value) {
				if(is_string($array[$key]))
					$array[$key] = mysql_real_escape_string($value);
			}
			return $array;
		}
	}

	/*
	 return table correpsondant aux critères ou si id_bien_immo ok => infos uniquement de celui ci
	 $orderby: prix_croissant, prix_decroissant, superficie_croissant, superficie_decroissant, nb_pieces, date
	 param : region,dep,gaz,chaffage,conso elec : id
	 		 gaz, conso elect, type chauffage : tableau
	 CF LES DEP REGIONS: CODE OU NOM??
	 
	 opt array indices : 'id_bien_immobilier', 'types_bien','type_achat_location', 'budget_mini','budget_maxi','ville','region','departement','nb_pieces','superficie_mini',
						  'superficie_maxi', 'gaz_effet_serre','type_chauffage','consos_energetiques', 'jardin', 'parking', 'nb_etages', 'ascenseur','order_by',is_for_lambda
	 */
	function searchBase($opt=NULL){

		$query = <<<SQL
				SELECT DISTINCT 
						bien_immobilier.id_bien_immobilier,
						bien_immobilier.prix,
						bien_immobilier.superficie,
						bien_immobilier.nb_pieces,
						bien_immobilier.descriptif,
						bien_immobilier.parking,
						bien_immobilier.nb_etages,
 						bien_immobilier.date_parution,
						appartement.etage,
						appartement.ascenseur,
						appartement.numero_appartement,
						maison.superficie_jardin
				FROM    bien_immobilier 
						LEFT OUTER JOIN appartement  ON appartement.id_bien_immobilier = bien_immobilier.id_bien_immobilier
						LEFT OUTER JOIN maison    	 ON maison.id_bien_immobilier = bien_immobilier.id_bien_immobilier
						LEFT OUTER JOIN immeuble     ON immeuble.id_bien_immobilier = bien_immobilier.id_bien_immobilier
						LEFT OUTER JOIN garage    	 ON garage.id_bien_immobilier = bien_immobilier.id_bien_immobilier
				WHERE   1=1 
SQL;

		if(empty($opt)){
			$query.=" AND bien_immobilier.id_personne_locataire IS NULL ";
		}
		elseif(!empty($opt['id_bien_immobilier']) && is_numeric($opt['id_bien_immobilier'])){
			$query.=" AND bien_immobilier.id_bien_immobilier = {$opt['id_bien_immobilier']} ";
			if(isset($opt['is_for_lambda']) && $opt['is_for_lambda'])
				$query.=' AND bien_immobilier.id_personne_locataire IS NULL';
		}
		else{
			$opt = secureArray($opt);
			//recupere tous les id des types de biens		
			$clause_types_bien='';
			if(!empty($opt['types_bien'])){
				foreach ($opt['types_bien'] as $value){
					if($value == IMMEUBLE || $value == APPARTEMENT || $value == MAISON ||$value == GARAGE){
							$type = trim($value);
							$clause_types_bien.=" $type.id_bien_immobilier = bien_immobilier.id_bien_immobilier OR ";
					}	
				}
				if(!empty($clause_types_bien))
					$clause_types_bien = ' AND ( '.$clause_types_bien." '')";
			}


			// type achat ou loc
			$clause_type_achat_location = 'AND id_personne_locataire IS NULL '; // on prends les biens non loues 

			if(!empty($opt['type_achat_location'])){
				if($opt['type_achat_location'] == 'location')
					$clause_type_achat_location .= ' AND id_agence_vendeur IS NULL ';
				elseif($opt['type_achat_location'] == 'vente')
					$clause_type_achat_location.= ' AND id_agence_loueur IS NULL AND id_personne_proprio IS NULL ';
			}


			//budget
			$clause_budget='';
			if(isset($opt['budget_mini']) && !empty($opt['budget_maxi']) && is_numeric($opt['budget_mini']) && is_numeric($opt['budget_maxi']))
				$clause_budget = "AND prix BETWEEN {$opt['budget_mini']} AND {$opt['budget_maxi']}";
			elseif(isset($opt['budget_mini']) && is_numeric($opt['budget_mini'])){
				$clause_budget = "AND prix > {$opt['budget_mini']} ";
			}
			elseif(isset($opt['budget_maxi']) && is_numeric($opt['budget_maxi'])){
				$clause_budget = "AND prix < {$opt['budget_maxi']} ";
			}


			//ville
			$clause_ville='';
			if(!empty($opt['ville']) && !is_array($opt['ville'])){
				$clause_ville = "AND bien_immobilier.id_adresse IN (SELECT DISTINCT id_adresse FROM adresse WHERE UPPER(ville) LIKE UPPER('{$opt['ville']}')) ";
				
			}


			//departement
			$clause_departement='';
			if(!empty($opt['departement']) and !is_array($opt['departement'])){
				$clause_departement =" AND bien_immobilier.id_adresse IN (SELECT DISTINCT id_adresse FROM adresse WHERE id_departement = {$opt['departement']}) "; 
			}


			//region
			$clause_region='';
			if(!empty($opt['region']) && !is_array($opt['region'])){
				$clause_region = "AND bien_immobilier.id_adresse IN (SELECT DISTINCT id_adresse FROM adresse WHERE adresse.id_departement IN (
																		SELECT DISTINCT id_departement FROM departement WHERE id_region={$opt['region']}))";
			}


			//nb pieces
			$clause_nb_pieces ='';
			if(!empty($opt['nb_pieces']) && is_numeric($opt['nb_pieces']))
				$clause_nb_pieces = " AND nb_pieces = {$opt['nb_pieces']} ";


			//superficie
			$clause_superficie ='';
			if(!empty($opt['superficie_mini']) && !empty($opt['superficie_maxi']) && is_numeric($opt['superficie_mini']) && is_numeric($opt['superficie_maxi']) ){
				$clause_superficie=" AND superficie BETWEEN {$opt['superficie_mini']} AND {$opt['superficie_maxi']} ";
			}
			elseif(!empty($opt['superficie_mini']) && is_numeric($opt['superficie_mini'])){
				$clause_superficie=" AND superficie > {$opt['superficie_mini']} ";
			}
			elseif(!empty($opt['superficie_maxi']) && is_numeric($opt['superficie_maxi'])){
				$clause_superficie=" AND superficie < {$opt['superficie_maxi']} ";
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
			$clause_conso_energetique='';
			if(!empty($opt['consos_energetiques']) && is_array($opt['consos_energetiques'])){
				foreach ($opt['consos_energetiques'] as $value) {
					if(isValidConsoEnergetique($value))
						$clause_conso_energetique.=" id_consommation_energetique = $value OR ";
				}
				if(!empty($clause_conso_energetique))
					$clause_conso_energetique = " AND ( ".$clause_conso_energetique." '')";
			}


			//type chauffage 
			$clause_type_chauffage='';
			if(!empty($opt['type_chauffage'])){
				foreach ($opt['type_chauffage'] as $value) {
					if(isValidChauffage($value))
						$clause_type_chauffage.=" id_type_chauffage = $value OR ";
				}
				if(!empty($clause_type_chauffage))
					$clause_conso_energetique = " AND ( ".$clause_type_chauffage." '')";
			}


			//parking
			$clause_parking='';
			if(!empty($opt['parking']) && ($opt['parking'] == 0 || $opt['parking'] == 1))
				$clause_parking = " AND parking = {$opt['parking']} ";

			//nb etages
			$clause_nb_etages='';
			if(!empty($opt['nb_etages']) && is_numeric($opt['nb_etages']))
				$clause_nb_etages = " AND nb_etages = {$opt['nb_etages']} ";

			//ascenseur
			$clause_ascenseur='';
			if(!empty($opt['ascenseur']) && is_numeric($opt['ascenseur']) && $opt['ascenseur'] == 1)
				$clause_ascenseur = " AND appartement.id_bien_immobilier = bien_immobilier.id_bien_immobilier AND appartement.ascenseur = 1 ";

			//jardin
			$clause_jardin='';
			if(!empty($opt['jardin']) && is_numeric($opt['jardin']) && $opt['jardin'] == 1)
				$clause_jardin = " AND maison.id_bien_immobilier = bien_immobilier.id_bien_immobilier AND maison.superficie_jardin > 0 ";

			//orderby
			$clause_order_by=' ORDER BY date_parution DESC';
			if(!empty($opt['order_by'])){
				if( $opt['order_by'] == 'prix_croissant' || $opt['order_by'] == 'prix_decroissant' || 
					$opt['order_by'] == 'superficie_croissant' || $opt['order_by'] == 'superficie_decroissant' || 
					$opt['order_by'] =='nb_pieces' || $opt['order_by'] =='date_parution'){

					if($opt['order_by'] == 'prix_croissant')
						$clause_order_by = " ORDER BY prix";
					elseif($opt['order_by'] == 'prix_decroissant')
						$clause_order_by = " ORDER BY prix DESC";
					elseif($opt['order_by'] == 'superficie_croissant')
						$clause_order_by = " ORDER BY superficie";
					elseif($opt['order_by'] == 'superficie_decroissant')
						$clause_order_by = " ORDER BY superficie DESC";
					elseif($opt['order_by'] == 'date_parution')
						$clause_order_by = " ORDER BY date_parution DESC";	
					else
						$clause_order_by = " ORDER BY {$opt['order_by']}";
				}
			}
			
			
			$query.= $clause_types_bien.$clause_type_achat_location.$clause_budget.$clause_superficie;
			$query.= $clause_departement.$clause_region.$clause_ville.$clause_nb_pieces;
			$query.= $clause_gaz.$clause_conso_energetique.$clause_type_chauffage.$clause_parking.$clause_nb_etages.$clause_ascenseur.$clause_jardin.$clause_order_by;
		}
		
		//echo $query;
		$stmt = myPDO::getSingletonPDO()->query($query); 

		$resultas = array();
		while($ligne = $stmt->fetch()){
			$ligne['infos_conso_energetique'] 	= getInfosConsoEnergetique($ligne['id_bien_immobilier']);
			$ligne['infos_gaz'] 				= getInfosGaz($ligne['id_bien_immobilier']);
			$ligne['infos_chauffage'] 			= getInfosChauffage($ligne['id_bien_immobilier']);
			$ligne['infos_adresse'] 			= getInfosAdresse($ligne['id_bien_immobilier']);
			$ligne['infos_chemins_photos']		= getInfosPhotos($ligne['id_bien_immobilier']);
			$ligne['info_type_achat_location']	= getInfosAchatLocation($ligne['id_bien_immobilier']);
			$ligne['info_type_bien']			= getTypeBien($ligne['id_bien_immobilier']);
			$ligne['infos_personne_gest']		= getInfosGestionnaire($ligne['id_bien_immobilier']);

			$resultas[]=$ligne;
		}

		$stmt->closeCursor();
		//var_dump($resultas);
		return $resultas;
	}

	// rajoute d'autres informations
	function searchForLocataire($id_bien_immobilier = NULL){

	}
	function searchForEmployeGestionnaire($id_bien_immobilier = NULL){

	}
	function searchForProprio($id_bien_immobilier = NULL){

	}

