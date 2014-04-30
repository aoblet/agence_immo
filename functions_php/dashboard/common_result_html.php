<?php
	require_once(dirname(__FILE__).'/../visualisation_bien/getUtils.php');

	function getInformationsBaseEtatDuBien($infos_array){
		$res=$infos_array; 

		$res = $res[0];

		$prix			= empty($res['prix']) 			? '': $res['prix'].' €';		
		$descriptif 	= empty($res['descriptif']) 	? '...': $res['descriptif'];
		$surface 		= empty($res['superficie']) 	? '' : "<p><i class='fa fa-expand'></i><span>Surface :</span> {$res['superficie']} m&sup2;</p>";
		$jardin 		= empty($res['jardin']) 		? '' : "<p><i class='fa fa-picture-o'></i><span>Jardin :</span> {$res['jardin']} m&sup2;</p>";
		$nb_pieces 		= empty($res['nb_pieces'])		? '' : "<p><i class='fa fa-th-large'></i><span>Nb pièces :</span> {$res['nb_pieces']}</p>";
		$parking 		= !empty($res['parking']) && $res['parking']==1	? "<p><i class='fa fa-road'></i><span>Stationnement :</span> Non</p>" :"<p><i class='fa fa-road'></i><span>Stationnement :</span> Oui</p>";
		$nb_etages 		= empty($res['nb_etages'])		? '' : "<p><i class='fa fa-ellipsis-v'></i><span>Nb étages :</span> {$res['nb_etages']}</p>"; 
		$numero_etage 	= empty($res['numero_etage'])	? '' : "<p><i class='fa fa-arrows-v'></i><span>Num étages :</span> {$res['numero_etage']} e</p>";
		$chauffage 		= empty($res['infos_chauffage']['nom_type_chauffage'])	? '' : "<p><i class='fa fa-fire'></i><span>Chauffage :</span> {$res['infos_chauffage']['nom_type_chauffage']} </p>";
		$type_bien 		= empty($res['info_type_bien']) ? '' : $res['info_type_bien'] ;
		$type_achat_location	= empty($res['info_type_achat_location']) ? '' : $res['info_type_achat_location'] ;

		$consommation_energetique ='';
		$gaz ='';
		$ascenseur =''; 
		$adresse='';


		if(!empty($res['infos_conso_energetique']) && !empty($res['infos_conso_energetique']['id_consommation_energetique'])){
			$classe='indice_'.trim(strtoupper($res['infos_conso_energetique']['nom_consommation_energetique']));
			$consommation_energetique=<<<HTML
				<p><i class="fa fa-signal"></i>Indice energetique : <span class="$classe" >{$res['infos_conso_energetique']['nom_consommation_energetique']}</span></p>
				<p class="padleft35"><span>Conso energ mini kilowatt :</span> {$res['infos_conso_energetique']['conso_kilowatt_an_mcarre_mini']}</p>
				<p class="padleft35"><span>Conso energ maxi kilowatt:</span> {$res['infos_conso_energetique']['conso_kilowatt_an_mcarre_maxi']}</p>
HTML;
		}

		if(!empty($res['infos_gaz']) && !empty($res['infos_gaz']['id_gaz'])){
			$classe='indice_'.trim(strtoupper($res['infos_gaz']['nom_gaz']));
			$gaz=<<<HTML
				<p><i class="fa fa-globe"></i>Effet de serre :<span class="$classe"> {$res['infos_gaz']['nom_gaz']}</span></p>
				<p class="padleft35" ><span>Emission co2 mini :</span> {$res['infos_gaz']['emission_kilo_co2_an_mcarre_mini']}</p>
				<p class="padleft35"><span>Emission co2 maxi :</span> {$res['infos_gaz']['emission_kilo_co2_an_mcarre_maxi']}</p>
HTML;
		}

		if(!empty($res['ascenseur'])){
			if($res['ascenseur'] == 0)
				$is_present = "Non";
			else
				$is_present ='Oui';
			$ascenseur = "<p><i class='fa fa-sort'></i><span>Ascenseur :</span> $is_present</p>";
		}

		if(!empty($res['infos_adresse']) && !empty($res['infos_adresse']['id_adresse'])){
			$adresse_rue='';
			$adresse_numero_rue='';
			$adresse_dep='';
			$adresse_region='';
			$adresse_code_postal='';
			$adresse_ville='';

			if(!empty($res['infos_adresse']['rue']))
				$adresse_rue=$res['infos_adresse']['rue'];

			if(!empty($res['infos_adresse']['numero_rue']))
				$adresse_numero_rue=$res['infos_adresse']['numero_rue'];

			if(!empty($res['infos_adresse']['nom_departement']))
				$adresse_dep=$res['infos_adresse']['nom_departement'];

			if(!empty($res['infos_adresse']['code_postal']))
				$adresse_code_postal=$res['infos_adresse']['code_postal'];

			if(!empty($res['infos_adresse']['ville']))
				$adresse_ville=ucfirst($res['infos_adresse']['ville']);

			if(!empty($res['infos_adresse']['nom_region']))
				$adresse_region=$res['infos_adresse']['nom_region'];


			$adresse.=<<<HTML
				<p><i class="fa fa-map-marker"></i><span>Adresse :</span> <strong>$adresse_numero_rue $adresse_rue</strong></p>
				<p class="padleft35"><span>Ville :</span> <strong>$adresse_code_postal $adresse_ville</strong></p>
				<p class="padleft35"><span>Département :</span> $adresse_dep</p>
				<p class="padleft35"><span>Région :</span> $adresse_region</p>
HTML;
		}

		$phrase_type_bien_type_operation_superficie = '';
		if(!empty($type_bien))
			$phrase_type_bien_type_operation_superficie.=$type_bien." ";

		if(!empty($type_achat_location)){
			if(trim(strtolower($type_achat_location)) == 'location')
				$type_achat_location ='louer';
			else
				$type_achat_location ='acheter';
		}
			$phrase_type_bien_type_operation_superficie.='à '.$type_achat_location;

		if(!empty($res['superficie']))
			$phrase_type_bien_type_operation_superficie.=" <span>".$res['superficie']." m&sup2;</span>";

		$date_parution='';
		if(!empty($res['date_parution'])){
			$date_formated_base = getDateFormatedVisuBase($res['date_parution']);
			$date_parution = "<i class='fa fa-calendar-o'></i> {$date_formated_base} ";
		}

		//infos locataire

		$statut_occupation='';
		if(!empty($res['info_type_achat_location'])){
			if(trim(strtolower($res['info_type_achat_location'])) == 'location'){
				if($res['id_personne_locataire'] != NULL){
					$statut_occupation= "<p class=''><i class='fa fa-check-square-o'></i>Bien loué</p>";
				}
				else{
					$statut_occupation= "<p class=''><i class='fa fa-minus-square'></i>Bien non loué</p>";
				}
			}
		}

		$result_html=<<<HTML
			<div class="titlepage bg-blue">
				<h2>Etat du bien <span class='indication-bien-dash'>$adresse_numero_rue $adresse_rue, $adresse_ville ( $adresse_code_postal )</span></h2>

			</div>
			<div class="bien-spe-title bg-white margin30">
				<h2>$phrase_type_bien_type_operation_superficie</h2>
				<h3>$prix</h3>
			</div>
			<div class="bien-spe-desc bg-white margin30">
				<div class='row'>
					<div class="col-md-6">
						<h4>Localisation</h4>
						<p>$adresse</p>
					</div>

					<div class="col-md-6">
						<h4>Statut</h4>
						$statut_occupation
						<p class="">$date_parution (date de mise en ligne)</p>

					</div>
				</div>
				

				<h4>Description du bien</h4>
				<p class="desc-p-bien"> $descriptif</p>
				
				<h4 style='padding:15px'>Informations techniques</h4>
				<div class="row">
					<div class="col-md-6">
						<!-- adresse  <p><i class="fa fa-map-marker"></i><span>Adresse :</span> 4 rue du php, 75002 Paris</p> -->
						$surface
						$jardin
						$nb_pieces
						$parking
						$nb_etages
						$ascenseur
					</div>
					<div class="col-md-6">
						$chauffage
						$consommation_energetique
						$gaz			
					</div>
				</div>
			</div>
HTML;
	return $result_html;
	}