<?php
	require_once(dirname(__FILE__).'/../../recherche_biens/search.php');
	require_once(dirname(__FILE__).'/../../recherche_biens/affichage_result.php');
	require_once(dirname(__FILE__).'/../../visualisation_bien/getUtils.php');
	require_once(dirname(__FILE__).'/../common_result_html.php');
	require_once(dirname(__FILE__).'/../../user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../visualisation_bien/affichage_result.php');


	function getListBiensProprio($id_personne){
		return affichage_base_liste_html(searchForProprioBase($id_personne));
	}

	function getMenuOnBienProprietaire($id_bien_immobilier){
		//gérer quand le bien est loué : pas d'affichage dans le menu cf visu bien ?

		$link_etat_bien = dirname($_SERVER['PHP_SELF']).'/bien.php?id_bien_immobilier='.trim($id_bien_immobilier);
		$link_infos = getPathRoot().'user/dashboard/proprietaire/infos.php';
		$link_messages = getPathRoot().'user/dashboard/proprietaire/messages.php';
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

	//id_div -> cacher en js
	function getMessageDepensesVide($id_div){
		return <<<HTML
			<div id='$id_div' class="bien-spe-desc-no-res bg-white margin30">
				<h4>Dépenses</h4>
				<p>Bonne nouvelle: Il n'y a pas de dépenses pour le moment !</p>
			</div>
HTML;
	}

	function getMessageRecettesVide($id_div){
		return <<<HTML
			<div id='$id_div' class="bien-spe-desc-no-res bg-white margin30">
				<h4>Recettes</h4>
				<p>Il n'y a pas de recettes pour le moment, patience </p>
			</div>
HTML;
	}

	function getDepensesGraphiqueHTML($depenses_array){

		if(empty($depenses_array)){
			return getMessageDepensesVide('div-depenses-graphic');
		}

		$button_radar = "<input type='button' id='Radar_button_depenses' class='button-graphique' value='Graphique en toile' onClick=\"setTypeGraphique_Depenses('Radar')\">";
					
		$button_bezier_off= "<input type='button' class='button-graphique' id='Line_bezier_off_button_depenses' value='Graphique classique linéaire' onClick=\"setTypeGraphique_Depenses('Line_bezier_off')\">";
		if(count($depenses_array) <= 2){
			$button_bezier_off ='';
			$button_radar='';
		}

		$html=<<<HTML
		<div id='div-depenses-graphic' class="bien-spe-desc bg-white margin30">
			<h4>Dépenses</h4>
			<canvas id="canvas-depenses" height="400" width="800" style="margin-top:20px;"></canvas>
			<div class="canvas-legende">
				<span style="border-left:30px solid rgba(255,204,0,1)">Dépenses</span>
				<div id='buttons-graphique-depenses'>
					<input type='button' class='button-graphique' id='Line_button_depenses' value='Graphique classique' onClick="setTypeGraphique_Depenses('Line')">
					$button_bezier_off
					<input type='button' class='button-graphique' id='Bar_button_depenses' value='Graphique en bâton' onClick="setTypeGraphique_Depenses('Bar')">
					$button_radar
				</div>
			</div>
		</div>
HTML;
		return $html;
	}

	function getDepensesGraphiqueJS($depenses_array){
		if(empty($depenses_array))
			return;

		$depenses_js = array();
		$labels_js = array();
		$options_chart="{}";
							
		if(count($depenses_array) == 1){
			$depenses_js[]=0;
			$labels_js[]='0';
			$options_chart="{bezierCurve : false}";
			
		}

		foreach ($depenses_array as $value) {
			$depenses_js[] = intval($value['prix_action']);
			$date = 'Pas de date';
			if(isset($value['date_historique']) && !empty($value['date_historique'])){
				$date = new DateTime($value['date_historique'], new DateTimeZone('Europe/Paris'));
				$date = $date->format('d/m/y');
			}

			$labels_js[] = $date;
		}

		$depenses_js = json_encode($depenses_js);
		$labels_js = json_encode($labels_js);

		$js=<<<JAVASCRIPT

			var graphique_depenses = null;

			function cacheButtonDepenses(type_button){
				var buttons = document.getElementById('buttons-graphique-depenses').getElementsByTagName("input");
				for(var i=0; i<buttons.length;i++){
					buttons[i].style.opacity='0.5';
					buttons[i].removeAttribute('disabled');
				}
				
				var btn_to_show = document.getElementById(type_button+'_button_depenses');
				if( btn_to_show ){
					btn_to_show.setAttribute('disabled','true');
					btn_to_show.style.opacity='1';
				}
			}

			function setTypeGraphique_Depenses(type_graphique){
				cacheButtonDepenses(type_graphique);

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
				};

				if(type_graphique == 'Line')
					graphique_depenses = new Chart(document.getElementById("canvas-depenses").getContext("2d")).Line(lineChartData,$options_chart);
				else if(type_graphique == 'Line_bezier_off')
					graphique_depenses = new Chart(document.getElementById("canvas-depenses").getContext("2d")).Line(lineChartData,{bezierCurve : false});
				else if(type_graphique == 'Radar')
					graphique_depenses = new Chart(document.getElementById("canvas-depenses").getContext("2d")).Radar(lineChartData,$options_chart);
				else if(type_graphique == 'Bar')
					graphique_depenses = new Chart(document.getElementById("canvas-depenses").getContext("2d")).Bar(lineChartData,$options_chart);

			}

			//par défaut
			if(document.getElementById('canvas-depenses') != null)
				setTypeGraphique_Depenses('Line');
JAVASCRIPT;

		return $js;
	}
	
		

	function getRentreesGraphiqueHTML($rentrees_array){

		if(empty($rentrees_array)){
			return getMessageRecettesVide('div-recettes-graphic');
		}

		$button_radar = "<input type='button' id='Radar_button_recettes' class='button-graphique' value='Graphique en toile' onClick=\"setTypeGraphique_Recettes('Radar')\">";

		$button_bezier_off= "<input type='button' class='button-graphique' id='Line_bezier_off_button_recettes' value='Graphique classique linéaire' onClick=\"setTypeGraphique_Recettes('Line_bezier_off')\">";
		if(count($rentrees_array) <= 2){
			$button_bezier_off ='';
			$button_radar='';
		}

		$html=<<<HTML
		<div id='div-recettes-graphic' class="bien-spe-desc bg-white margin30">
			<h4>Recettes</h4>
			<canvas id="canvas-recettes" height="400" width="800" style="margin-top:20px;"></canvas>
			<div class="canvas-legende">
				<span style="border-left:30px solid rgba(151,187,205,1)">Recettes</span>
				<div id='buttons-graphique-recettes'>
					<input type='button' class='button-graphique' id='Line_button_recettes' value='Graphique classique' onClick="setTypeGraphique_Recettes('Line')">
					$button_bezier_off
					<input type='button' class='button-graphique' id='Bar_button_recettes' value='Graphique en bâton' onClick="setTypeGraphique_Recettes('Bar')">
					$button_radar
				</div>
			</div>
		</div>
HTML;
		return $html;
	}

	function getRecettesGraphiqueJS($rentrees_array){
		if(empty($rentrees_array))
			return ;

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
				$date = $date->format('d/m/y');
			}

			$labels_js[] = $date;
		}

		$rentrees_js = json_encode($rentrees_js);
		$labels_js = json_encode($labels_js);

		$js=<<<JAVASCRIPT

			var graphique_recettes = null;

			function cacheButtonRecettes(type_button){
				var buttons = document.getElementById('buttons-graphique-recettes').getElementsByTagName("input");
				for(var i=0; i<buttons.length;i++){
					buttons[i].style.opacity='0.5';
					buttons[i].removeAttribute('disabled');
				}
				
				var btn_to_show = document.getElementById(type_button+'_button_recettes');
				if( btn_to_show ){
					btn_to_show.setAttribute('disabled','true');
					btn_to_show.style.opacity='1';
				}
			}

			function setTypeGraphique_Recettes(type_graphique){
				cacheButtonRecettes(type_graphique);

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
				};

				if(type_graphique == 'Line')
					graphique_recettes = new Chart(document.getElementById("canvas-recettes").getContext("2d")).Line(lineChartData,$options_chart);
				else if(type_graphique == 'Line_bezier_off')
					graphique_depenses = new Chart(document.getElementById("canvas-recettes").getContext("2d")).Line(lineChartData,{bezierCurve : false});
				else if(type_graphique == 'Radar')
					graphique_recettes = new Chart(document.getElementById("canvas-recettes").getContext("2d")).Radar(lineChartData,$options_chart);
				else if(type_graphique == 'Bar')
					graphique_recettes = new Chart(document.getElementById("canvas-recettes").getContext("2d")).Bar(lineChartData,$options_chart);
			}

			//par défaut
			if(document.getElementById('canvas-recettes') != null)
			 	setTypeGraphique_Recettes('Line');

JAVASCRIPT;

		return $js;
	}

	function getDiagrammeProportionsProprietaireHTML($depenses_array, $rentrees_array){

		if(empty($rentrees_array) && empty($depenses_array)){
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
				<span id='diagramme_depenses_legende' style="border-left:30px solid rgba(255,204,0,1)">Dépenses</span>
				<span id='diagramme_recettes_legende' style="border-left:30px solid rgba(151,187,205,1)">Recettes</span>
			</div>
		</div>
HTML;
	}

	function getDiagrammeProportionsProprietaireJS($depenses_array,$recettes_array){
		$total_depenses = 0;
		$total_recettes = 0;
		$depenses_prorata=0;
		$recettes_prorata=0;

		foreach ($depenses_array as $value) {
			$total_depenses += $value['prix_action'];
		}

		foreach ($recettes_array as $value) {
			$total_recettes += $value['prix_action'];
		}

		if($total_recettes !=0 || $total_depenses !=0){
			$depenses_prorata = substr( $total_depenses/($total_depenses+$total_recettes) *100, 0,6);

			// on peut déduire l'autre sans opérations => précision assurrée au niveau de l'arrondie
			$recettes_prorata = substr( 100 - floatval($depenses_prorata), 0,6);
		}

		$js=<<<JAVASCRIPT

		
			if(document.getElementById('canvas-diagramme') != null){
				var data_pie = [
					{
						value:$total_recettes,
						color:"rgba(151,187,205,0.7)"
					},
					{
						value:$total_depenses,
						color:"rgba(255,204,0,0.7)"
					}
				]
				//maj totaux
				document.getElementById('diagramme_recettes_legende').innerHTML += ' ($total_recettes € | $recettes_prorata %)';
				document.getElementById('diagramme_depenses_legende').innerHTML += ' ($total_depenses € | $depenses_prorata %)';
				var diagramme_pie = new Chart(document.getElementById("canvas-diagramme").getContext("2d")).Pie(data_pie);
			}

JAVASCRIPT;

		return $js;
	}

	function getChoiceButtonProprietaireHTML(){
		return <<<HTML
		<div id='choice_buttons'>
			<input type='button' class='button-graphique choice-button' id='choice_graphic' value='Graphiques' onClick="setChoiceHistorique('choice_graphic')">
			<input type='button' class='button-graphique choice-button' id='choice_array' value='Tableaux' onClick="setChoiceHistorique('choice_array')">
		</div>

HTML;
	}

	function getChoiceButtonProprietaireJS(){
		return <<<JAVASCRIPT
			function setChoiceHistorique(type_choice){
				var buttons = document.getElementById('choice_buttons').getElementsByTagName('input');
				for(var i =0; i<buttons.length;i++){
					buttons[i].style.opacity='0.5';
				}
				document.getElementById(type_choice).style.opacity='1';

				if(type_choice=='choice_graphic'){
					document.getElementById('div-diagramme-graphic').style.display='block';
					document.getElementById('div-recettes-graphic').style.display='block';
					document.getElementById('div-depenses-graphic').style.display='block';

					document.getElementById('div-depenses-array').style.display='none';
					document.getElementById('div-recettes-array').style.display='none';
				}
				else{
					document.getElementById('div-diagramme-graphic').style.display='none';
					document.getElementById('div-recettes-graphic').style.display='none';
					document.getElementById('div-depenses-graphic').style.display='none';

					document.getElementById('div-depenses-array').style.display='block';
					document.getElementById('div-recettes-array').style.display='block';
				}
			}
			//defaut
			setChoiceHistorique('choice_graphic');
			
			

JAVASCRIPT;
	}



	function getArrayDepenses($depenses_array){
		if(empty($depenses_array))
			return getMessageDepensesVide('div-depenses-array');

		$array=<<<HTML
		<table id='depenses_array' class="tablesorter table table-striped table-historique-dash ">
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
		foreach ($depenses_array as $value) {
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
		<div id='div-depenses-array' class="bien-spe-desc bg-white margin30 ">
			<h4>Dépenses</h4>
			<div class='table-responsive table-center'> 
				$array
			</div>
		</div>
HTML;
	}

	function addSortArrayDepensesJS(){
		return <<<JAVASCRIPT
			$(document).ready(function() { 
        		$('#depenses_array').tablesorter({
       				headers: { 4: { sorter: "shortDate", dateFormat: "ddmmyyyy"}   },
       				sortList: [[0,0]]
				});        		
			}); 
    
JAVASCRIPT;
	}

	function addSortArrayRecettesJS(){
		return <<<JAVASCRIPT
			$(document).ready(function() { 
				$('#recettes_array').tablesorter({
       				headers: { 4: { sorter: "shortDate", dateFormat: "ddmmyyyy"}   },
       				sortList: [[0,0]]
				});        		
			}); 
    
JAVASCRIPT;
	}

	function getArrayRecettes($recettes_array){
		if(empty($recettes_array))
			return getMessageRecettesVide('div-recettes-array');

		$array=<<<HTML
		<table id='recettes_array' class="tablesorter table table-striped table-historique-dash ">
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
		foreach ($recettes_array as $value) {
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
				$prix = $value['prix_action'].'&nbsp;€';
				$class_prix='indice_B';
			}
			else{
				$prix = 'Prix non renseignée';
			}

			$array.="<tr><td>$cpt</td> <td class='$class_prix'><strong>$prix</strong></td> <td>$nom</td> <td>$description</td> <td>$date</td>\n";
			$cpt++;
		}

		$array.='</tbody></table>';

		return <<<HTML
		<div id='div-recettes-array' class="bien-spe-desc bg-white margin30">
			<h4>Recettes</h4>
			<div class='table-responsive table-center'> 
				$array
			</div>
		</div>
HTML;
	}


	function affichage_visu_proprio($id_bien_immobilier){
		$res=array();
		if($id_bien_immobilier=='')
			return getMessageNotFoundVisu();

		$res=searchForProprioAvance($id_bien_immobilier); 
		if(empty($res))
			return getMessageNotFoundVisu();

		$html = getInformationsBaseEtatDuBien($res);
		return $html;
	}


	function getEtatDuBienProprietaire($id_bien_immobilier){
		return affichage_visu_proprio($id_bien_immobilier);
	}




	