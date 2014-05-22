<?php
	require_once(dirname(__FILE__).'/../../recherche_biens/search.php');
	require_once(dirname(__FILE__).'/../../recherche_biens/affichage_result.php');
	require_once(dirname(__FILE__).'/../../visualisation_bien/getUtils.php');
	require_once(dirname(__FILE__).'/../../visualisation_bien/affichage_result.php');
	require_once(dirname(__FILE__).'/../common_result_html.php');
	require_once(dirname(__FILE__).'/../../user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../settings/connexion.php');

	function getListBiensEmploye($id_personne){
		return affichage_base_liste_html(searchForEmployeBase($id_personne));
	}

	function getMenuOnBienEmploye($id_bien_immobilier){
		//gérer quand le bien est loué : pas d'affichage dans le menu cf visu bien ?

		$link_etat_bien = dirname($_SERVER['PHP_SELF']).'/bien.php?id_bien_immobilier='.trim($id_bien_immobilier);
		$link_infos = getPathRoot().'user/dashboard/employe/infos.php';
		$link_messages = getPathRoot().'user/dashboard/employe/messages.php';
		$link_ajout_bien = getPathRoot().'/user/dashboard/employe/ajoutBien.php';
		$link_ajout_historique = getPathRoot().'/user/dashboard/employe/ajoutHistorique.php?id_bien_immobilier='.trim($id_bien_immobilier);
		$link_ajout_paiement = getPathRoot().'/user/dashboard/employe/ajoutPaiement.php?id_bien_immobilier='.trim($id_bien_immobilier);
		$link_modif_annonce= getPathRoot().'/user/dashboard/employe/modifierAnnonce.php?id_bien_immobilier='.trim($id_bien_immobilier);

		return <<<HTML
		<div class="col-md-3">
			<div class="dash-menu bg-white">
				<h4>MENU</h4>
				<a href="$link_infos" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="historiques.php?id_bien_immobilier=$id_bien_immobilier" class="button-home"><i class="fa fa-sort-amount-desc"></i>Historiques financiers</a>
				<a href="$link_etat_bien" class="button-home"><i class="fa fa-sitemap"></i>Etat du bien</a>
				<a href="$link_messages" class="button-home"><i class="fa fa-envelope"></i>Mes messages</a>
				<a href="$link_modif_annonce" class="button-home"><i class="fa fa-file"></i>Modifier l'annonce</a>
				<a href="$link_ajout_paiement" class="button-home"><i class="fa fa-plus"></i>Ajouter un paiement</a>
				<a href="$link_ajout_historique" class="button-home"><i class="fa fa-align-left"></i>Ajouter un historique</a>
				<a href="$link_ajout_bien" class="button-home"><i class="fa fa-pencil"></i>Ajouter un bien</a>
				<!--<a href="" class="button-home"><i class="fa fa-book"></i>Voir l'annonce du bien</a>-->
				<a href="./" class="button-home"><i class="fa fa-reply"></i>Retour au Dash</a>
			</div>
		</div>
HTML;
	}

	function affichage_visu_employe($id_bien_immobilier,$id_employe){
		$res=array();
		if($id_bien_immobilier=='')
			return getMessageNotFoundVisu();

		$res=searchForEmployeAvance($id_bien_immobilier,$id_employe); 
		if(empty($res))
			return getMessageNotFoundVisu();

		$html = getInformationsBaseEtatDuBien($res);
		return $html;
	}


	function getEtatDuBienEmploye($id_bien_immobilier,$id_employe){
		return affichage_visu_employe($id_bien_immobilier,$id_employe);
	}

	/* ajout bien utils*/
	function getSelectTypeChauffageEmploye($id_select){
		$stmt = myPDO::getSingletonPDO()->query("SELECT id_type_chauffage, nom_type_chauffage FROM type_chauffage");

		$type_chauffage_opt = "<option value=''> Type chauffage ...</option>";
		while($ligne=$stmt->fetch()){
			$type_chauffage_opt.="<option value='{$ligne['id_type_chauffage']}'>{$ligne['nom_type_chauffage']}</option>";
		}
		$stmt->closeCursor();

		return "<select name='$id_select' class='add-bien-tech-select'>\n".$type_chauffage_opt.'</select>';
	}

	function getSelectConsosEnergetiqueEmploye($id_select){
		$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM consommation_energetique_classe");

		$type_energetique_opt = "<option value=''> Electrique ...</option>";
		while($ligne=$stmt->fetch()){
			$id_energie 		= $ligne['id_consommation_energetique'];
			$indice_energie		= $ligne['nom_consommation_energetique'];
			$max_energie_kilo	= $ligne['conso_kilowatt_an_mcarre_maxi'];

			$type_energetique_opt.="<option value='$id_energie'>$indice_energie - $max_energie_kilo Kw/m&sup2;/an </option>";
		}
		$stmt->closeCursor();

		return "<select name='$id_select' style='margin-right:15px' class='add-bien-tech-select'>\n".$type_energetique_opt.'</select>';
	}

	function getSelectConsosGazEmploye($id_select){
		$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM gaz_a_effet_de_serre_classe");

		$type_gaz_opt = "<option value=''> Gaz à effet de serre ...</option>";
		while($ligne=$stmt->fetch()){
			$id_gaz			= $ligne['id_gaz'];
			$indice_gaz		= $ligne['nom_gaz'];
			$max_gaz_kilo	= $ligne['emission_kilo_co2_an_mcarre_maxi'];

			$type_gaz_opt.="<option value='$id_gaz'>$indice_gaz - $max_gaz_kilo Kg_co2/m&sup2;/an </option>";
		}
		$stmt->closeCursor();

		return "<select name='$id_select' class='add-bien-tech-select'>\n".$type_gaz_opt.'</select>';
	}

	function getSelectProprietaireEmploye($id_select){
		$stmt = myPDO::getSingletonPDO()->query("	SELECT personne.id_personne,personne.prenom_personne,personne.nom_personne 
													FROM personne, proprietaire WHERE personne.id_personne = proprietaire.id_personne 
												 	UNION 
												 	SELECT id_agence_immobiliere as id_personne, nom_agence_immobiliere as prenom_personne, '' as nom_personne
												 	FROM agence_immobiliere ");

		$proprietaire_opt = "<option value=''> Proprietaire ...</option>";
		while($ligne=$stmt->fetch()){
			$id_proprietaire		= $ligne['id_personne'];
			$prenom_proprietaire	= $ligne['prenom_personne'];
			$nom_proprietaire		= $ligne['nom_personne'];

			$proprietaire_opt.="<option value='$id_proprietaire'>$prenom_proprietaire - $nom_proprietaire</option>";
		}
		$stmt->closeCursor();

		return "<select name='$id_select' class='add-bien-tech-select'>\n".$proprietaire_opt.'</select>';
	}

	function getSelectDepartementsEmploye($id_select){
		$stmt = myPDO::getSingletonPDO()->query("SELECT * FROM departement");

		$dep_opt = "<option value=''> Departement ...</option>";
		while($ligne=$stmt->fetch()){
			$id_dep			= $ligne['id_departement'];
			$code_postal	= $ligne['code_departement'];
			$nom_dep		= $ligne['nom_departement'];

			$dep_opt.="<option value='$id_dep'>$code_postal - $nom_dep</option>";
		}
		$stmt->closeCursor();

		return "<select name='$id_select' class='add-bien-select'>\n".$dep_opt.'</select>';
	}

	function getSelectTypeBienEmploye($id_radio,$array_type_bien){
		$option_type_bien="";
		foreach ($array_type_bien as $value) {
			$id_type = $value.'_id';
			$value_radio = strtolower($value);
			$value_html = ucfirst($value);
			$option_type_bien.="<input type='radio' id='$id_type' name='$id_radio' value='$value_radio' /> 
								<span ><label for='$id_type' style='line-height:0px;font-weight:normal;margin-top:12px'>$value_html</label></span>";
		}
		return $option_type_bien;
	}