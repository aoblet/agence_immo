<?php
	require_once(dirname(__FILE__).'/getUtils.php');

	function getSelectDepartementsHTML($name_select, $id_selected=NULL){
		$departemens = getAllDepartements();

		if(!empty($departemens)){
			$dep_opt="<option value=''>Tous les départements</option>";
			foreach ($departemens as $key => $value) {
				$selected='';
				if($value['id_departement'] == $id_selected)
					$selected='selected';

				$dep_opt.=" <option value='{$value['id_departement']}' $selected > {$value['nom_departement']}</option>\n";
			}

			$html="<select name='{$name_select}' id='$name_select'> \n".$dep_opt." </select>";

			return $html;	
		}
		return '';
		
	}
