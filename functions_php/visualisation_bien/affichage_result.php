<?php
	require_once(dirname(__FILE__).'/../../enum/enum_type_user.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_biens.php');
	require_once(dirname(__FILE__).'/../user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../recherche_biens/search.php');

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

	function affichage_base_visu($id_bien_immobilier,$is_for_landa){
		$link_retour = getPathRoot().'result.php';
		$res=array();

		if($id_bien_immobilier=='')
			return getMessageNotFoundVisu();

		$res=searchBase(array('id_bien_immobilier'=>$id_bien_immobilier,'is_for_landa'=>$is_for_landa)); 
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
		$date_parution = empty($res['date_parution']) ?'':$res['date_parution'];

		$consommation_energetique ='';
		$gaz ='';
		$ascenseur =''; 
		if(!empty($res['infos_conso_energetique']) && !empty($res['infos_conso_energetique']['id_consommation_energetique'])){
			$consommation_energetique=<<<HTML
				<p><i class="fa fa-signal"></i><span>Indice energetique :</span> {$res['infos_conso_energetique']['nom_consommation_energetique']}</p>
				<p><i class="fa fa-signal"></i><span>Conso energ mini :</span> {$res['infos_conso_energetique']['conso_kilowatt_an_mcarre_mini']}</p>
				<p><i class="fa fa-signal"></i><span>Conso energ maxi :</span> {$res['infos_conso_energetique']['conso_kilowatt_an_mcarre_maxi']}</p>
HTML;
		}

		if(!empty($res['infos_gaz']) && !empty($res['infos_gaz']['id_gaz'])){
			$gaz=<<<HTML
				<p><i class="fa fa-signal"></i><span>Indice energetique :</span> {$res['infos_gaz']['nom_gaz']}</p>
				<p><i class="fa fa-signal"></i><span>Conso energ mini :</span> {$res['infos_gaz']['emission_kilo_co2_an_mcarre_mini']}</p>
				<p><i class="fa fa-signal"></i><span>Conso energ maxi :</span> {$res['infos_gaz']['emission_kilo_co2_an_mcarre_maxi']}</p>
HTML;
		}

		if(!empty($res['ascenseur'])){
			if($res['ascenseur'] == 0)
				$is_present = "Non";
			else
				$is_present ='Oui';
			$ascenseur = "<p><i class='fa fa-sort'></i><span>Ascenseur :</span> $is_present</p>";
		}

		$phrase_type_bien_type_operation_superficie = '';
		if(!empty($type_bien))
			$phrase_type_bien_type_operation_superficie.=$type_bien." ";

		if(!empty($type_achat_location)){
			if($type_achat_location == 'location')
				$type_achat_location ='louer';
			else
				$type_achat_location ='acheter';
		}
			$phrase_type_bien_type_operation_superficie.='à '.$type_achat_location;

		if(!empty($res['superficie']))
			$phrase_type_bien_type_operation_superficie.=" <span>".$res['superficie']." m&sup2;</span>";

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