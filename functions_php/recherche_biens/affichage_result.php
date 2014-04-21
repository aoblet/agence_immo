<?php
	function affichage_base_liste_html($res){
		$result_html='';
		foreach ($res as $value) {
			//si photo absente : hardcoded ici
			$photo_apercu = empty($value['chemins_photos']) ? "img/plans/1.jpg" : $value['chemins_photos'][0];
			
			$prix  = (empty($value['prix']) || $value['prix'] == 0) ? "Prix inconnu" : $value['prix'].' €';
			$superficie = empty($value['superficie']) ? "Superficie inconnue" : $value['superficie'].' m&sup2';
			$type_bien = empty($value['info_type_bien']) ? "": ucfirst($value['inf_type_bien']);
			$type_achat_location = empty($value['info_type_achat_location']) ? "Bien proposé par l'agence" : $value['info_type_achat_location'];
			$conso_energetique ='';
			$adresse = '';

			if(!empty($value['infos_conso_energetique']) && !empty($value['infos_conso_energetique']['nom_consommation_energetique'])){
				$indice = $value['infos_conso_energetique']['nom_consommation_energetique'];
				$conso_energetique = " <p><i class='fa fa-tachometer'></i> Indice $indice</p>";
			}

			if(!empty($value['infos_adresse']) && !empty($value['infos_adresse']['id_adresse'])){
				$adresse_infos = '';

				if(!empty($value['infos_adresse']['code_postal']))
					$adresse_infos.=$value['infos_adresse']['code_postal'];

				if(!empty($value['infos_adresse']['ville']))
					$adresse_infos.=" ".$value['infos_adresse']['ville'];

				if(!empty($adresse_infos))
					$adresse="<p><i class='fa fa-globe'></i>$adresse</p>";
			}

			$responsable='';
			if(!empty($value['infos_personne_gest']) && !empty($value['infos_personne_gest']['nom_personne_gest'])){
				$infos_personne_gest = $value['infos_personne_gest']['nom_personne_gest']." ".$value['infos_personne_gest']['prenom_personne_gest'];
				$responsable = "<p><i class='fa fa-users'></i>$infos_personne_gest</p>";
			}
	

			$result_html.=<<<HTML
			<article class="article-bien margin30">
				<div class="col-md-4 article-bien-pic" style="background:url($photo) top center no-repeat;">
		
				</div>
				<div class="col-md-8 bg-white article-bien-desc">
					<div class="bien-title">
						<h4>
							$type_achat_location
						</h4>
						<h5>$prix</h5>
					</div>
					<div class="bien-desc">
						$adresse
						$superficie
						$conso_energetique
						$responsable
					</div>
					<div class="article-bien-contact-agency"><a href=""><i class="fa fa-envelope"></i></a></div>
					<div class="article-bien-contact-agency2"><a href=""><i class="fa fa-bell"></i></a></div>
				</div>
			</article>
HTML;
		}
	 	return $result_html;
	}