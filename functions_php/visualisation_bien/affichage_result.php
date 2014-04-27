<?php
	require_once(dirname(__FILE__).'/../../enum/enum_type_user.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_biens.php');
	require_once(dirname(__FILE__).'/../user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../recherche_biens/search.php');
	require_once(dirname(__FILE__).'/getUtils.php');


	function getMessageNotFoundVisu(){
		$lien_acceuil = getPathRoot().'index.php';
		return <<<HTML
		<section class="bg-grey first-section" >
			<div class="container margin60">
				<div class="row">
					<div class="col-md-12">
						<div class="no-bien bg-white">
							<h2><i class="fa fa-puzzle-piece"></i></h2>
							<h3>Oups..</h3>
							<p>La description du bien que vous avez demandée ne semble pas correspondre au lien envoyé. Veuillez recommencer votre requette. Si l'erreur persiste, merci de bien vouloir nous en faire part, afin que nous retablissions les données au plus vite. Merci</p>
							<a href="$lien_acceuil"><i class="fa fa-reply"></i>Retourner sur la page précédente</a>
							<a href="maito:contact@fakeagency.com"><i class="fa fa-envelope"></i>Contacter l'agence</a>
						</div>
					</div>
				</div>
			</div>
		</section>
HTML;
	}

	function generateMap(){
		$js=<<<JAVASCRIPT
			<script type='text/javascript'>
				google.maps.event.addDomListener(window, 'load', function(){

					var carte = null;

					var styles = [
						{
							"featureType": "administrative.locality",
							"stylers": [
								{ "visibility": "off" }
							]
						},
						{
							"featureType": "landscape",
							"stylers": [
								{ "color": "#eaeaea" }
							]
						}

					];


					var styledMap = new google.maps.StyledMapType(styles, {name: "Gmap stylée"});
					var myLatlng = new google.maps.LatLng(48, -1);

					//init sur paris
					carte = new google.maps.Map(
						document.getElementById('map-canvas'),
						{
							zoom:7,
							//center: new google.maps.LatLng(48.855697, 2.347403),
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							scrollwheel: false

						}
					);
					//carte.mapTypes.set('map_style', styledMap);
					//carte.setMapTypeId('map_style');

					var address=document.getElementById('ville_map').innerHTML;
					var geocoder = new google.maps.Geocoder();
					
					geocoder.geocode({'address':address},function(results,status){

						if(status == google.maps.GeocoderStatus.OK){
							var adr_latlng = results[0].geometry.location;

							var marker = new google.maps.Marker({
								position:adr_latlng,
								//map:carte,
								title:address,
								animation: google.maps.Animation.DROP
							});
							
							setTimeout(function(){
								marker.setMap(carte);
							},500);


							var content_info ="<div style='line-height:1.35;overflow:hidden;white-space:nowrap;'><div style='text-align:center'>"+ address +"</div>";
							content_info += "<a target='_BLANK' href='http://en.wikipedia.org/w/index.php?title="+ address+"'>Informations sur "+ address +" </a></div>";
							var info_window = new google.maps.InfoWindow({
								content:content_info
							});
							
							carte.setCenter(adr_latlng);

							google.maps.event.addDomListener(marker,'click',function(){
								info_window.open(carte, marker);	
							});

							
							setTimeout(function(){
								info_window.open(carte, marker);
							},1000);
						}
						else{
							var info_window_echec = new google.maps.InfoWindow({
								content:'Pas de renseignements sur la ville'
							});
							
							carte.setCenter(new google.maps.LatLng(48.855697, 2.347403));

							carte.setZoom(4);
							info_window_echec.setPosition( new google.maps.LatLng(51.049087, 2.277611) );
							info_window_echec.open(carte);
						}
					});

				});
			</script>
JAVASCRIPT;

		return $js;
	}

	function affichage_base_visu($id_bien_immobilier,$is_for_lambda){
		//gérer les photos
		$link_retour = getPathRoot().'result.php';
		$res=array();

		if($id_bien_immobilier=='')
			return getMessageNotFoundVisu();

		$res=searchBase(array('id_bien_immobilier'=>$id_bien_immobilier,'is_for_lambda'=>$is_for_lambda)); 
		if(empty($res))
			return getMessageNotFoundVisu();

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
				$adresse_ville=$res['infos_adresse']['ville'];

			if(!empty($res['infos_adresse']['nom_region']))
				$adresse_region=$res['infos_adresse']['nom_region'];


			$adresse.=<<<HTML
			
				<p><i class="fa fa-map-marker"></i><span>Adresse :</span> $adresse_numero_rue $adresse_rue</p>
				<p class="padleft35"><span>Ville :</span> $adresse_code_postal <span id='ville_map'>$adresse_ville</span></p>
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

		if(!empty($res['date_parution'])){
			$date_formated_base = getDateFormatedVisuBase($res['date_parution']);
			$date_formated_details = getDateFormatedVisuDetails($res['date_parution']);
			if($date_formated_details)
				$date_formated_details="<i class='fa fa-clock-o'></i> ".$date_formated_details;

			$date_parution = "<i class='fa fa-calendar-o'></i> {$date_formated_base} {$date_formated_details}";
		}

		$result_html=<<<HTML
		<section class="bg-grey first-section" >

			<div class="container margin60">
				<div class="row">
					<div class="col-md-12">
						<div class="bien-spe-title bg-white">
							<div class="bien-spe-title-alert">
								<h4>Excusivité</h4>
							</div>
							<h2>$phrase_type_bien_type_operation_superficie</h2>

							<div class="bien-spe-title-back">
								<a href="$link_retour"><i class="fa fa-reply"></i> Retour</a>
							</div>

							<h3>$prix</h3>
						</div>
					</div>
				</div>
			</div>

			<div class="container margin30">
				<div class="row">
					<div class="col-md-6">
						<div class="bien-spe-desc bg-white">

							<h4>Description du bien</h4>
							<p class="desc-p-bien"> $descriptif</p>

							<h4>Localisation</h4>
							$adresse

							<h4>Informations techniques</h4>
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
					</div>

					<div class="col-md-6">
						<div class="bien-spe-contact bg-white">
							<div class="bien-spe-desc-mel">$date_parution</div>
							<a href="mailto:contact@fakeagency.com">Contacter l'agence</a>
						</div>

						<div class="margin30 bg-white">
							<div id="map-canvas" style='height:250px'></div>
						</div>

						<div class="bien-spe-pic bg-white margin30">
							
							<div class="flexslider">
								<ul class="slides">
									<li>
										<img src="../img/biens/1.jpg" />
									</li>
									<li>
										<img src="../img/biens/2.jpg" />
									</li>
									<li>
										<img src="../img/biens/3.jpg" />
									</li>
								</ul>
							</div>
						</div>

					</div>

				</div>
			</div>
		</section>
HTML;
	return $result_html;
	}