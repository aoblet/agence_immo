<?php
	require_once(dirname(__FILE__).'/../../enum/enum_type_user.php');
	require_once(dirname(__FILE__).'/../../enum/enum_type_biens.php');
	require_once(dirname(__FILE__).'/../user_utils/getUtils_html.php');

	function getMessageNotFoundResult(){
		$lien_acceuil = getPathRoot().'index.php';
		return <<<HTML
			<div class="no-result bg-white margin30">
				<h2><i class="fa fa-puzzle-piece"></i></h2>
				<h3>Oups..</h3>
				<p>Fake Agency ne dispose malheureusement pas de biens corréspondants à tous vos critères. Veuillez élargir votre recherche, ou contacter directement l'agence pour plus de précisions.</p>
				<a href="$lien_acceuil"><i class="fa fa-reply"></i>Retourner sur la page d'accueuil</a>
				<a href="mailto:contact@fakeagency.com"><i class="fa fa-envelope"></i>Contacter l'agence</a>
			</div>
HTML;
	}
	//type personne : pour le lien
	function affichage_base_liste_html($res, $type_user=''){
		$result_html='';
		
		foreach ($res as $value) {
			//si photo absente : hardcoded ici
			//$photo_apercu = empty($value['chemins_photos']) ? "img/plans/1.jpg" : $value['chemins_photos'][0];
			$photo_apercu ='';
			if($value['info_type_bien'] == MAISON){
				$photo_apercu = 'img/plans/1.jpg';
			}
			elseif($value['info_type_bien'] == IMMEUBLE){
				$photo_apercu = 'img/plans/2.jpg';
			}
			elseif($value['info_type_bien'] == APPARTEMENT){
				$photo_apercu = 'img/plans/2.jpg';
			}
			elseif($value['info_type_bien'] == GARAGE){
				$photo_apercu = 'img/plans/4.jpg';
			}



			$prix  = (empty($value['prix']) || $value['prix'] == 0) ? "Prix inconnu" : $value['prix'].' €';
			$superficie = empty($value['superficie']) ? "Superficie inconnue" : $value['superficie'].' m&sup2;';
			$type_bien = empty($value['info_type_bien']) ? "Bien immobilier": ucfirst($value['info_type_bien']);
			$type_achat_location = empty($value['info_type_achat_location']) ? "Agence" : $value['info_type_achat_location'];
			$date_parution = empty($value['date_parution']) ? '' : "<i class='fa fa-clock-o'></i>{$value['date_parution']}";

			$conso_energetique ='';
			$adresse = '';

			if(!empty($value['infos_conso_energetique']) && !empty($value['infos_conso_energetique']['nom_consommation_energetique'])){
				$indice = $value['infos_conso_energetique']['nom_consommation_energetique'];
				$conso_energetique = " <p><i class='fa fa-tachometer'></i> Indice éco : <span>$indice</span></p>";
			}


			if(!empty($value['infos_adresse']) && !empty($value['infos_adresse']['id_adresse'])){
				$adresse_infos = '';
				if(!empty($value['infos_adresse']['code_postal']))
					$adresse_infos.=' '.trim($value['infos_adresse']['code_postal']);

				if(!empty($value['infos_adresse']['ville']))
					$adresse_infos.=" ".$value['infos_adresse']['ville'];

				if(!empty($adresse_infos))
					$adresse="<p><i class='fa fa-globe'></i>$adresse_infos</p>";
			}

			$descriptif='';
			if(!empty($value['descriptif'])){
				$descriptif=substr($value['descriptif'], 0, 80)."...";
			}

			$lien_bien_immobilier='';
			if(!empty($value['id_bien_immobilier'])){

				$dir_current_script_using = str_replace("\\", "/", (dirname($_SERVER['PHP_SELF'])) );
				$dir_current_path = explode("/", $dir_current_script_using);
				$lien_bien_immobilier = $dir_current_path[count($dir_current_path)-1];

				if($lien_bien_immobilier == "agence_immo")
					$lien_bien_immobilier= dirname($_SERVER['PHP_SELF'])."/visualisation_bien/";
				elseif (1) {
					// to complete for dash
					$lien_bien_immobilier="";
				}


				switch ($type_user) {
					case EMPLOYE:
						$lien_bien_immobilier.="employe/";
						break;

					case PROPRIETAIRE:
						$lien_bien_immobilier.="proprietaire/";
						break;

					case LOCATAIRE:
						$lien_bien_immobilier.="locataire/";
						break;
					default:
						break;
				}

				$lien_bien_immobilier = $lien_bien_immobilier."bien.php?id_bien_immobilier=".trim($value['id_bien_immobilier']);
			}


			$result_html.=<<<HTML
			<article class="article-bien margin30">
				<a href="$lien_bien_immobilier">
					<div class="col-md-4 article-bien-pic" style="background:url($photo_apercu) top center no-repeat;">
						<div class="article-bien-pic-loc-achat">$type_achat_location</div>
					</div>
					<div class="col-md-8 bg-white article-bien-desc">
						<div class="bien-title">
							<h4>
								$type_bien $superficie
							</h4>
							<h3>$date_parution</h3>
							<h5>$prix</h5>
						</div>
						<div class="bien-desc">
							<div class="col-md-6">
								$adresse
								<p><i class="fa fa-home"></i> $type_bien</p>
								$conso_energetique
							</div>
							<div class="col-md-6">
								$descriptif
							</div>
							
						</div>
						<div class="article-bien-contact-agency"><a href=""><i class="fa fa-envelope"></i></a></div>
						<div class="article-bien-contact-agency2"><a href=""><i class="fa fa-bell"></i></a></div>
					</div>
				</a>
			</article>
HTML;
		}
		$current_dir = str_replace('\\','/',dirname($_SERVER['PHP_SELF']));
		$current_dir = explode('/', $current_dir);
		$current_dir = $current_dir[count($current_dir) -1]; 

		if(isset($res) && empty($res) && $current_dir == 'agence_immo'){
			$result_html=getMessageNotFoundResult();
		}

	 	return $result_html;
	}
