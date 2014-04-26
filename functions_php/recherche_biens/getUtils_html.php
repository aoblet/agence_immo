<?php
	require_once(dirname(__FILE__).'/getUtils.php');
	require_once(dirname(__FILE__).'/../user_utils/getUtils_html.php');

	function getSelectDepartementsHTML($name_select, $id_selected=NULL){
		$departemens = getAllDepartements();

		if(!empty($departemens)){
			$dep_opt="<option value=''>Tous les départements</option>";
			foreach ($departemens as $key => $value) {
				$selected='';
				if($value['id_departement'] == $id_selected)
					$selected='selected';

				$dep_opt.=" <option value='{$value['id_departement']}' $selected >{$value['code_departement']} - {$value['nom_departement']}</option>\n";
			}

			$html="<select name='{$name_select}' id='$name_select'> \n".$dep_opt." </select>";

			return $html;	
		}
		return '';
		
	}

	function getAnnoncesRecentesLimit4(){
		$biens_array= getAnnoncesRecentes(4);
		$html='';


		foreach ($biens_array as $value) {
			//photo à gérer
			$type = !empty($value['info_type_bien']) ? ucfirst($value['info_type_bien']): 'Bien immo';
			$superficie = !empty($value['superficie']) ? $value['superficie']." m&sup2;": '';
			$link_pic = getPathRoot().'img/plans/1.jpg';
			$link_visu = getPathRoot().'visualisation_bien/bien.php?id_bien_immobilier='.trim($value['id_bien_immobilier']);

			//bloc annonce
			$html.= <<<HTML
			<a href='$link_visu'>
				<div class="col-md-3">
					<div class="bg-white last-ann">
						<div class="last-ann-pic" style="background:url($link_pic) top center no-repeat;">
						</div>
						<div class="last-ann-desc bg-blue">
							$type $superficie
						</div>
					</div>
				</div>
			</a>
HTML;
		}

		// pour assurer le css on remplie 4 colonnes en tout
		$cpt=count($biens_array);
		while($cpt<4){
			$html.= "\n<div class='col-md-3'></div>";
			$cpt++;
		}
		return $html;
	}

