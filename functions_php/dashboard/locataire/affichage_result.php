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

		$res=searchForLocatarieAvance($id_bien_immobilier,$id_locataire); 
		if(empty($res))
			return getMessageNotFoundVisu();

		$html = getInformationsBaseEtatDuBien($res);
		return $html;
	}


	function getEtatDuBienLocataire($id_bien_immobilier,$id_locataire){
		return affichage_visu_locataire($id_bien_immobilier,$id_locataire);
	}

