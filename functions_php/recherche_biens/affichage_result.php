<?php
	function affichage_base_liste_html($res){
		$result_html='';
		foreach ($res as $value) {
			//si photo absente : hardcoded ici
			$photo_apercu = empty($value['chemins_photos']) ? "img/plans/1.jpg" : $value['chemins_photos'][0];

			$prix  = (empty($value['prix']) || $value['prix'] == 0) ? "Prix inconnu" : $value['prix'].' €';
			$superficie = empty($value['superficie']) ? "Superficie inconnue" : $value['superficie'].' m&sup2;';
			$type_bien = empty($value['info_type_bien']) ? "Bien immobilier": ucfirst($value['inf_type_bien']);
			$type_achat_location = empty($value['info_type_achat_location']) ? "AGENCE" : $value['info_type_achat_location'];
			$conso_energetique ='';
			$adresse = '';

			if(!empty($value['infos_conso_energetique']) && !empty($value['infos_conso_energetique']['nom_consommation_energetique'])){
				$indice = $value['infos_conso_energetique']['nom_consommation_energetique'];
				$conso_energetique = " <p><i class='fa fa-tachometer'></i> Indice éco : <span>$indice</span></p>";
			}


			if(!empty($value['infos_adresse']) && !empty($value['infos_adresse']['id_adresse'])){
				$adresse_infos = '';
				if(!empty($value['infos_adresse']['code_postal']))
					$adresse_infos.=$value['infos_adresse']['code_postal'];

				if(!empty($value['infos_adresse']['ville']))
					$adresse_infos.=" ".$value['infos_adresse']['ville'];

				if(!empty($adresse_infos))
					$adresse="<p><i class='fa fa-globe'></i>$adresse_infos</p>";
			}

			$descriptif='';
			if(!empty($value['descriptif'])){
				$descriptif=substr($value['descriptif'], 0, 30);
			}

	
			$result_html.=<<<HTML
			<article class="article-bien margin30">
				<a href="">
					<div class="col-md-4 article-bien-pic" style="background:url($photo_apercu) top center no-repeat;">
						<div class="article-bien-pic-loc-achat">$type_achat_location</div>
					</div>
					<div class="col-md-8 bg-white article-bien-desc">
						<div class="bien-title">
							<h4>
								$type_bien $superficie
							</h4>
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
	 	return $result_html;
	}