<?php
	require_once(dirname(__FILE__).'/../../recherche_biens/search.php');
	require_once(dirname(__FILE__).'/../../recherche_biens/affichage_result.php');

	function getListBiens($id_personne){
		return affichage_base_liste_html(searchForProprioBase($id_personne));
	}

	function getMenuOnBien($id_bien_immobilier){
		//gérer quand le bien est loué : pas d'affichage dans le menu cf visu bien ?

		$link_etat_bien = dirname($_SERVER['PHP_SELF']).'/bien.php?id_bien_immobilier='.trim($id_bien_immobilier);
		return <<<HTML
		<div class="col-md-3">
			<div class="dash-menu bg-white">
				<h4>MENU</h4>
				<a href="" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="historiques.php?id_bien_immobilier=$id_bien_immobilier" class="button-home"><i class="fa fa-sort-amount-desc"></i>Historiques financiers</a>
				<!--<a href="historique_entree.php?id_bien_immobilier=$id_bien_immobilier" class="button-home"><i class="fa fa-sort-amount-asc"></i>Historique des recettes</a>-->
				<a href="$link_etat_bien" class="button-home"><i class="fa fa-sitemap"></i>Etat du bien</a>
				<a href="" class="button-home"><i class="fa fa-envelope"></i>Contacter l'agence</a>
				<a href="" class="button-home"><i class="fa fa-book"></i>Voir l'annonce du bien</a>
				<a href="./" class="button-home"><i class="fa fa-reply"></i>Retour au Dash</a>
			</div>
		</div>
HTML;
	}

	function getEtatDuBien($informations_array){
		return <<<HTML
		<div class="bien-spe-title bg-white margin30">
			<h2>Appartement à vendre <span>47 m&sup2;</span></h2>
			<h3>340 000 €</h3>
		</div>
HTML;
	}

	function getDepensesGraphique($depenses_array){

		if(empty($depenses_array)){
			return <<<HTML
			<div class="bien-spe-desc-no-res bg-white margin30">

				<h4>Dépenses</h4>
				<p>Il n'y a pas de dépenses pour le moment</p>
			</div>
HTML;
		}

		$html=<<<HTML
		<div class="bien-spe-desc bg-white margin30">
			<h4>Dépenses</h4>

			<canvas id="canvas-depenses" height="400" width="800" style="margin-top:20px;"></canvas>

			<div class="canvas-legende">
				<span style="border-left:30px solid rgba(255,204,0,1)">Dépenses</span>
			</div>
HTML;
	
		$depenses_js = array();
		$labels_js = array();
		$options_chart ="{}";

		if(count($depenses_array) == 1){
			$depenses_js[]=0;
			$labels_js[]='';
			$options_chart="{bezierCurve : false}";
		}

		foreach ($depenses_array as $value) {
			$depenses_js[] = intval($value['prix_action']);
			$date = 'Pas de date';
			if(isset($value['date_historique']) && !empty($value['date_historique'])){
				$date = new DateTime($value['date_historique'], new DateTimeZone('Europe/Paris'));
				$date = $date->format('d/m/Y');
			}

			$labels_js[] = $date;
		}

		$depenses_js = json_encode($depenses_js);
		$labels_js = json_encode($labels_js);



		$js=<<<JAVASCRIPT
			<script type='text/javascript'>
			var lineChartData = {
				labels : $labels_js,
				datasets : [
					{
						fillColor : "rgba(255,204,0,0.2)",
						strokeColor : "rgba(255,204,0,1)",
						pointColor : "rgba(255,204,0,1)",
						pointStrokeColor : "#fff",
						data : $depenses_js
					},

				]
				
			}

			var myLineDepenses = new Chart(document.getElementById("canvas-depenses").getContext("2d")).Line(lineChartData,$options_chart);
			
			</script>
JAVASCRIPT;
		
		$html .= $js."</div>";
		return $html;
	}

	function getRentreesGraphique($rentrees_array){

		if(empty($rentrees_array)){
			return <<<HTML
			<div class="bien-spe-desc-no-res bg-white margin30">

				<h4>Recettes</h4>
				<p>Il n'y a pas de recettes pour le moment</p>
			</div>
HTML;
		}
		
		$html=<<<HTML
		<div class="bien-spe-desc bg-white margin30">

			<h4>Recettes</h4>
			<canvas id="canvas-recettes" height="400" width="800" style="margin-top:20px;"></canvas>

			<div class="canvas-legende">
				<span style="border-left:30px solid rgba(151,187,205,1)">Recettes</span>
			</div>

			
HTML;
	
		$rentrees_js = array();
		$labels_js = array();
		$options_chart="{}";

		if(count($rentrees_array) == 1){
			$rentrees_js[]=0;
			$labels_js[]='0';
			$options_chart="{bezierCurve : false}";
		}

		foreach ($rentrees_array as $value) {
			$rentrees_js[] = intval($value['prix_action']);
			$date = 'Pas de date';
			if(isset($value['date_historique']) && !empty($value['date_historique'])){
				$date = new DateTime($value['date_historique'], new DateTimeZone('Europe/Paris'));
				$date = $date->format('d/m/Y');
			}

			$labels_js[] = $date;
		}

		$rentrees_js = json_encode($rentrees_js);
		$labels_js = json_encode($labels_js);



		$js=<<<JAVASCRIPT
			<script type='text/javascript'>
			var lineChartData = {
				labels : $labels_js,
				datasets : [
					{
						fillColor : "rgba(151,187,205,0.5)",
						strokeColor : "rgba(151,187,205,1)",
						pointColor : "rgba(151,187,205,1)",
						pointStrokeColor : "#fff",
						data : $rentrees_js
					},

				]
				
			}

			var myLineRecettes = new Chart(document.getElementById("canvas-recettes").getContext("2d")).Line(lineChartData,$options_chart);
			
			</script>
JAVASCRIPT;
		
		$html .= $js."</div>";
		return $html;
	}





	