<?php
	require_once(dirname(__FILE__).'/../../recherche_biens/search.php');
	require_once(dirname(__FILE__).'/../../recherche_biens/affichage_result.php');
	require_once(dirname(__FILE__).'/../../visualisation_bien/getUtils.php');
	require_once(dirname(__FILE__).'/../common_result_html.php');
	require_once(dirname(__FILE__).'/../../user_utils/getUtils_html.php');


	function getMenuOnBienLocataire($id_bien_immobilier){
		//gérer quand le bien est loué : pas d'affichage dans le menu cf visu bien ?

		$link_etat_bien = dirname($_SERVER['PHP_SELF']).'/bien.php?id_bien_immobilier='.trim($id_bien_immobilier);
		$link_infos = getPathRoot().'user/dashboard/locataire/infos.php';
		$link_messages = getPathRoot().'user/dashboard/locataire/messages.php';
		return <<<HTML
		<div class="col-md-3">
			<div class="dash-menu bg-white">
				<h4>MENU</h4>
				<a href="$link_infos" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="historiques.php?id_bien_immobilier=$id_bien_immobilier" class="button-home"><i class="fa fa-sort-amount-desc"></i>Historiques financiers</a>
				<a href="$link_etat_bien" class="button-home"><i class="fa fa-sitemap"></i>Etat du bien</a>
				<a href="$link_messages" class="button-home"><i class="fa fa-envelope"></i>Mes messages</a>
				<!--<a href="" class="button-home"><i class="fa fa-book"></i>Voir l'annonce du bien</a>-->
				<a href="./" class="button-home"><i class="fa fa-reply"></i>Retour au Dash</a>
			</div>
		</div>
HTML;
	}



	function getListBiensLocataire($id_personne){
		return affichage_base_liste_html(searchForLocataireBase($id_personne));
	}

	function affichage_visu_locataire($id_bien_immobilier,$id_locataire){
		$res=array();
		if($id_bien_immobilier=='')
			return getMessageNotFoundVisu();

		$res=searchForLocataireAvance($id_bien_immobilier,$id_locataire); 
		if(empty($res))
			return getMessageNotFoundVisu();

		$html = getInformationsBaseEtatDuBien($res);
		return $html;
	}


	function getEtatDuBienLocataire($id_bien_immobilier,$id_locataire){
		return affichage_visu_locataire($id_bien_immobilier,$id_locataire);
	}

	//id_div -> cacher en js
	function getMessageImputationsVide($id_div){
		return <<<HTML
			<div id='$id_div' class="bien-spe-desc-no-res bg-white margin30">
				<h4>Imputations</h4>
				<p>Bonne nouvelle: Il n'y a pas d'imputations pour le moment !</p>
			</div>
HTML;
	}

	function getMessagePaiementsVide($id_div){
		return <<<HTML
			<div id='$id_div' class="bien-spe-desc-no-res bg-white margin30">
				<h4>Paiements</h4>
				<p>Il n'y a pas paiements effectués pour le moment </p>
			</div>
HTML;
	}

	function getArrayImputations($imputations_array){
		if(empty($imputations_array))
			return getMessageImputationsVide('div-imputations-array');

		$array=<<<HTML
		<table id='imputations_array' class="tablesorter table table-striped table-historique-dash ">
			<thead>
				<tr>
					<th class='width40'>#</th>
					<th>Prix </th>
					<th>Nom </th>
					<th>Description </th>
					<th>Date </th>
				</tr>
			</thead>
			<tbody>
HTML;
		$cpt = 1;
		foreach ($imputations_array as $value) {
			$nom  = isset($value['nom_action']) ? ucfirst($value['nom_action']) :'Intitulé non renseigné';
			$description  = isset($value['descriptif_action']) ? ucfirst($value['descriptif_action']) :'Description non renseignée';

			$date='Date non renseignée';
			if(isset($value['date_historique'])){
				try{
					$date_obj = new DateTime($value['date_historique'], new DateTimeZone('Europe/Paris'));
					$date = $date_obj->format('d/m/Y');
					$date_obj=null;
				}
				catch(Exception $e){
					$date ='La création de la date à échouée';
				}
			}

			$class_prix='';
			if(isset($value['prix_action'])) {
				$prix = $value['prix_action'];
				if($prix <1000){
					$class_prix='indice_A';
				}
				elseif($prix >=1000 && $prix <2000) {
					$class_prix='indice_C';
				}
				elseif($prix >=2000 && $prix <3000) {
					$class_prix='indice_D';
				}
				elseif($prix >=3000 && $prix <5000) {
					$class_prix='indice_F';
				}
				else{
					$class_prix='indice_F';
				}
				$prix.='&nbsp;€';
			}
			else{
				$prix = 'Prix non renseignée';
			}

			$array.="<tr><td>$cpt</td> <td class='$class_prix'><strong>$prix</strong></td> <td>$nom</td> <td>$description</td> <td>$date</td>\n";
			$cpt++;
		}

		$array.='</tbody></table>';

		return <<<HTML
		<div id='div-imputations-array' class="bien-spe-desc bg-white margin30 ">
			<h4>Imputations</h4>
			<div class='table-responsive table-center'> 
				$array
			</div>
		</div>
HTML;
	}

	function getArrayPaiements($paiement_array){
		if(empty($paiement_array))
			return getMessagePaiementsVide('div-paiements-array');

		$array=<<<HTML
		<table id='paiements_array' class="tablesorter table table-striped table-historique-dash ">
			<thead>
				<tr>
					<th class='width40'>#</th>
					<th>Prix </th>
					<th>Nom </th>
					<th>Description </th>
					<th>Date </th>
				</tr>
			</thead>
			<tbody>
HTML;
		$cpt = 1;
		foreach ($paiement_array as $value) {
			$nom  = isset($value['nom_action']) ? ucfirst($value['nom_action']) :'Intitulé non renseigné';
			$description  = isset($value['descriptif_action']) ? ucfirst($value['descriptif_action']) :'Description non renseignée';

			$date='Date non renseignée';
			if(isset($value['date_historique'])){
				try{
					$date_obj = new DateTime($value['date_historique'], new DateTimeZone('Europe/Paris'));
					$date = $date_obj->format('d/m/Y');
					$date_obj=null;
				}
				catch(Exception $e){
					$date ='La création de la date à échouée';
				}
			}

			$class_prix='';
			if(isset($value['prix_action'])) {
				$prix = $value['prix_action'];
				if($prix <1000){
					$class_prix='indice_A';
				}
				elseif($prix >=1000 && $prix <2000) {
					$class_prix='indice_C';
				}
				elseif($prix >=2000 && $prix <3000) {
					$class_prix='indice_D';
				}
				elseif($prix >=3000 && $prix <5000) {
					$class_prix='indice_F';
				}
				else{
					$class_prix='indice_F';
				}
				$prix.='&nbsp;€';
			}
			else{
				$prix = 'Prix non renseignée';
			}

			$array.="<tr><td>$cpt</td> <td class='$class_prix'><strong>$prix</strong></td> <td>$nom</td> <td>$description</td> <td>$date</td>\n";
			$cpt++;
		}

		$array.='</tbody></table>';

		return <<<HTML
		<div id='div-paiements-array' class="bien-spe-desc bg-white margin30 ">
			<h4>Paiements</h4>
			<div class='table-responsive table-center'> 
				$array
			</div>
		</div>
HTML;
	}

	function addSortArrayImputationsJS(){
		return <<<JAVASCRIPT

			$(document).ready(function() { 
				$("#imputations_array").tablesorter();
        		$("#imputations_array").tablesorter( {sortList: [[0,0], [1,0]]} ); 
			}); 
    
JAVASCRIPT;
	}

	function addSortArrayPaiementsJS(){
		return <<<JAVASCRIPT

			$(document).ready(function() { 
				$("#paiements_array").tablesorter();
        		$("#paiements_array").tablesorter( {sortList: [[0,0], [1,0]]} ); 
			}); 
    
JAVASCRIPT;
	}

	function getChoiceButtonLocataireJS(){
		return <<<JAVASCRIPT
			function setChoiceHistorique(type_choice){
				var buttons = document.getElementById('choice_buttons').getElementsByTagName('input');
				for(var i =0; i<buttons.length;i++){
					buttons[i].style.opacity='0.5';
				}
				document.getElementById(type_choice).style.opacity='1';

				if(type_choice=='choice_graphic'){
					$('#div-diagramme-graphic').css('display','block');
					$('#div-imputations-graphic').css('display','block');
					$('#div-paiements-graphic').css('display','block');

					$('#div-paiements-array').css('display','none');
					$('#div-imputations-array').css('display','none');
				}
				else{
					$('#div-diagramme-graphic').css('display','none');
					$('#div-imputations-graphic').css('display','none');
					$('#div-paiements-graphic').css('display','none');

					$('#div-paiements-array').css('display','block');
					$('#div-imputations-array').css('display','block');
				}
			}
			//defaut
			setChoiceHistorique('choice_graphic');
			
JAVASCRIPT;
	}

	function getChoiceButtonLocataireHTML(){
		return <<<HTML
		<div id='choice_buttons'>
			<input type='button' class='button-graphique choice-button' id='choice_graphic' value='Graphiques' onClick="setChoiceHistorique('choice_graphic')">
			<input type='button' class='button-graphique choice-button' id='choice_array' value='Tableaux' onClick="setChoiceHistorique('choice_array')">
		</div>

HTML;
	}



	function getImputationsGraphiqueHTML($imputations_array){

		if(empty($imputations_array)){
			return getMessageImputationsVide('div-imputations-graphic');
		}

		$button_radar = "<input type='button' id='Radar_button_imputations' class='button-graphique' value='Graphique en toile' onClick=\"setTypeGraphique_Imputations('Radar')\">";
					
		$button_bezier_off= "<input type='button' class='button-graphique' id='Line_bezier_off_button_depenses' value='Graphique classique linéaire' onClick=\"setTypeGraphique_Imputations('Line_bezier_off')\">";
		if(count($imputations_array) <= 2){
			$button_bezier_off ='';
			$button_radar='';
		}

		$html=<<<HTML
		<div id='div-imputations-graphic' class="bien-spe-desc bg-white margin30">
			<h4>Imputations</h4>
			<canvas id="canvas-imputations" height="400" width="800" style="margin-top:20px;"></canvas>
			<div class="canvas-legende">
				<span style="border-left:30px solid rgba(255,204,0,1)">Imputations</span>
				<div id='buttons-graphique-imputations'>
					<input type='button' class='button-graphique' id='Line_button_imputations' value='Graphique classique' onClick="setTypeGraphique_Imputations('Line')">
					$button_bezier_off
					<input type='button' class='button-graphique' id='Bar_button_imputations' value='Graphique en bâton' onClick="setTypeGraphique_Imputations('Bar')">
					$button_radar
				</div>
			</div>
		</div>
HTML;
		return $html;
	}

	function getPaiementsGraphiqueHTML($paiements_array){

		if(empty($paiements_array)){
			return getMessagePaiementsVide('div-paiements-graphic');
		}

		$button_radar = "<input type='button' id='Radar_button_paiements' class='button-graphique' value='Graphique en toile' onClick=\"setTypeGraphique_Paiements('Radar')\">";

		$button_bezier_off= "<input type='button' class='button-graphique' id='Line_bezier_off_button_recettes' value='Graphique classique linéaire' onClick=\"setTypeGraphique_Paiements('Line_bezier_off')\">";
		if(count($paiements_array) <= 2){
			$button_bezier_off ='';
			$button_radar='';
		}

		$html=<<<HTML
		<div id='div-paiements-graphic' class="bien-spe-desc bg-white margin30">
			<h4>Paiements</h4>
			<canvas id="canvas-paiements" height="400" width="800" style="margin-top:20px;"></canvas>
			<div class="canvas-legende">
				<span style="border-left:30px solid rgba(151,187,205,1)">Paiements</span>
				<div id='buttons-graphique-paiements'>
					<input type='button' class='button-graphique' id='Line_button_paiements' value='Graphique classique' onClick="setTypeGraphique_Paiements('Line')">
					$button_bezier_off
					<input type='button' class='button-graphique' id='Bar_button_paiements' value='Graphique en bâton' onClick="setTypeGraphique_Paiements('Bar')">
					$button_radar
				</div>
			</div>
		</div>
HTML;
		return $html;
	}

	function getImputationsGraphiqueJS($imputations_array){
		if(empty($imputations_array))
			return;

		$imputations_js = array();
		$labels_js = array();
		$options_chart="{}";
							
		if(count($imputations_array) == 1){
			$imputations_js[]=0;
			$labels_js[]='0';
			$options_chart="{bezierCurve : false}";
			
		}

		foreach ($imputations_array as $value) {
			$imputations_js[] = intval($value['prix_action']);
			$date = 'Pas de date';
			if(isset($value['date_historique']) && !empty($value['date_historique'])){
				$date = new DateTime($value['date_historique'], new DateTimeZone('Europe/Paris'));
				$date = $date->format('d/m/y');
			}

			$labels_js[] = $date;
		}

		$imputations_js = json_encode($imputations_js);
		$labels_js = json_encode($labels_js);

		$js=<<<JAVASCRIPT

			var graphique_imputations = null;

			function cacheButtonImputations(type_button){
				var buttons = document.getElementById('buttons-graphique-imputations').getElementsByTagName("input");
				for(var i=0; i<buttons.length;i++){
					buttons[i].style.opacity='0.5';
					buttons[i].removeAttribute('disabled');
				}
				
				var btn_to_show = document.getElementById(type_button+'_button_imputations');
				if( btn_to_show ){
					btn_to_show.setAttribute('disabled','true');
					btn_to_show.style.opacity='1';
				}
			}

			function setTypeGraphique_Imputations(type_graphique){
				cacheButtonImputations(type_graphique);

				var lineChartData = {
					labels : $labels_js,
					datasets : [
						{
							fillColor : "rgba(255,204,0,0.2)",
							strokeColor : "rgba(255,204,0,1)",
							pointColor : "rgba(255,204,0,1)",
							pointStrokeColor : "#fff",
							data : $imputations_js
						},
					]
				};

				if(type_graphique == 'Line')
					graphique_imputations = new Chart(document.getElementById("canvas-imputations").getContext("2d")).Line(lineChartData,$options_chart);
				else if(type_graphique == 'Line_bezier_off')
					graphique_imputations = new Chart(document.getElementById("canvas-imputations").getContext("2d")).Line(lineChartData,{bezierCurve : false});
				else if(type_graphique == 'Radar')
					graphique_imputations = new Chart(document.getElementById("canvas-imputations").getContext("2d")).Radar(lineChartData,$options_chart);
				else if(type_graphique == 'Bar')
					graphique_imputations = new Chart(document.getElementById("canvas-imputations").getContext("2d")).Bar(lineChartData,$options_chart);

			}

			//par défaut
			if(document.getElementById('canvas-imputations') != null)
				setTypeGraphique_Imputations('Line');
JAVASCRIPT;

		return $js;
	}

	function getPaiementsGraphiqueJS($paiements_array){
		if(empty($paiements_array))
			return;

		$paiements_js = array();
		$labels_js = array();
		$options_chart="{}";
							
		if(count($paiements_array) == 1){
			$paiements_js[]=0;
			$labels_js[]='0';
			$options_chart="{bezierCurve : false}";
			
		}

		foreach ($paiements_array as $value) {
			$paiements_js[] = intval($value['prix_action']);
			$date = 'Pas de date';
			if(isset($value['date_historique']) && !empty($value['date_historique'])){
				$date = new DateTime($value['date_historique'], new DateTimeZone('Europe/Paris'));
				$date = $date->format('d/m/y');
			}

			$labels_js[] = $date;
		}

		$paiements_js = json_encode($paiements_js);
		$labels_js = json_encode($labels_js);

		$js=<<<JAVASCRIPT

			var graphique_paiements = null;

			function cacheButtonPaiements(type_button){
				var buttons = document.getElementById('buttons-graphique-paiements').getElementsByTagName("input");
				for(var i=0; i<buttons.length;i++){
					buttons[i].style.opacity='0.5';
					buttons[i].removeAttribute('disabled');
				}
				
				var btn_to_show = document.getElementById(type_button+'_button_paiements');
				if( btn_to_show ){
					btn_to_show.setAttribute('disabled','true');
					btn_to_show.style.opacity='1';
				}
			}

			function setTypeGraphique_Paiements(type_graphique){
				cacheButtonPaiements(type_graphique);

				var lineChartData = {
					labels : $labels_js,
					datasets : [
						{
							fillColor : "rgba(151,187,205,0.5)",
							strokeColor : "rgba(151,187,205,1)",
							pointColor : "rgba(151,187,205,1)",
							pointStrokeColor : "#fff",
							data : $paiements_js
						},
					]
				};

				if(type_graphique == 'Line')
					graphique_paiements = new Chart(document.getElementById("canvas-paiements").getContext("2d")).Line(lineChartData,$options_chart);
				else if(type_graphique == 'Line_bezier_off')
					graphique_paiements = new Chart(document.getElementById("canvas-paiements").getContext("2d")).Line(lineChartData,{bezierCurve : false});
				else if(type_graphique == 'Radar')
					graphique_paiements = new Chart(document.getElementById("canvas-paiements").getContext("2d")).Radar(lineChartData,$options_chart);
				else if(type_graphique == 'Bar')
					graphique_paiements = new Chart(document.getElementById("canvas-paiements").getContext("2d")).Bar(lineChartData,$options_chart);

			}

			//par défaut
			if(document.getElementById('canvas-paiements') != null)
				setTypeGraphique_Paiements('Line');
JAVASCRIPT;

		return $js;
	}

		function getDiagrammeProportionsLocataireHTML($imputations_array, $paiement_array){

		if(empty($paiement_array) && empty($imputations_array)){
			return <<<HTML
			<div id='div-diagramme-graphic' class="bien-spe-desc-no-res bg-white margin30">
				<h4>Diagramme total des transactions financières</h4>
				<p>Il n'y a actuellement aucune transactions financières</p>
			</div>
HTML;
		}

		return <<<HTML
		<div id='div-diagramme-graphic' class="bien-spe-desc bg-white margin30">
			<h4>Diagramme total des transactions financières</h4>
			<canvas id="canvas-diagramme" height="400" width="800" style="margin-top:20px;"></canvas>
			<div class="canvas-legende">
				<span id='diagramme_imputations_legende' style="border-left:30px solid rgba(255,204,0,1)">Imputations</span>
				<span id='diagramme_paiements_legende' style="border-left:30px solid rgba(151,187,205,1)">Paiements</span>
			</div>
		</div>
HTML;
	}


	function getDiagrammeProportionsLocataireJS($imputations_array,$paiements_array){
		$total_imputations = 0;
		$total_paiements = 0;
		$imputations_prorata=0;
		$paiements_prorata=0;

		foreach ($imputations_array as $value) {
			$total_imputations += $value['prix_action'];
		}

		foreach ($paiements_array as $value) {
			$total_paiements += $value['prix_action'];
		}

		if($total_imputations !=0 || $total_paiements !=0){
			$imputations_prorata = substr( $total_imputations/($total_imputations+$total_paiements) *100, 0,6);

			// on peut déduire l'autre sans opérations => précision assurrée au niveau de l'arrondie
			$paiements_prorata = substr( 100 - floatval($paiements_prorata), 0,6);
		}

		$js=<<<JAVASCRIPT

		
			if(document.getElementById('canvas-diagramme') != null){
				var data_pie = [
					{
						value:$total_paiements,
						color:"rgba(151,187,205,0.7)"
					},
					{
						value:$total_imputations,
						color:"rgba(255,204,0,0.7)"
					}
				]
				//maj totaux
				document.getElementById('diagramme_imputations_legende').innerHTML += ' ($total_imputations € | $imputations_prorata %)';
				document.getElementById('diagramme_paiements_legende').innerHTML += ' ($total_paiements € | $paiements_prorata %)';
				var diagramme_pie = new Chart(document.getElementById("canvas-diagramme").getContext("2d")).Pie(data_pie);
			}

JAVASCRIPT;

		return $js;
	}

