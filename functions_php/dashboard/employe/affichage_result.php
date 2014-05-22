<?php
	require_once(dirname(__FILE__).'/../../recherche_biens/search.php');
	require_once(dirname(__FILE__).'/../../recherche_biens/affichage_result.php');
	require_once(dirname(__FILE__).'/../../visualisation_bien/getUtils.php');
	require_once(dirname(__FILE__).'/../../visualisation_bien/affichage_result.php');
	require_once(dirname(__FILE__).'/../common_result_html.php');
	require_once(dirname(__FILE__).'/../../user_utils/getUtils_html.php');

	function getListBiensEmploye($id_personne){
		return affichage_base_liste_html(searchForEmployeBase($id_personne));
	}

	function getMenuOnBienEmploye($id_bien_immobilier){
		//gérer quand le bien est loué : pas d'affichage dans le menu cf visu bien ?

		$link_etat_bien = dirname($_SERVER['PHP_SELF']).'/bien.php?id_bien_immobilier='.trim($id_bien_immobilier);
		$link_infos = getPathRoot().'user/dashboard/employe/infos.php';
		$link_messages = getPathRoot().'user/dashboard/employe/messages.php';
		$link_ajout_bien = getPathRoot().'/user/dashboard/employe/ajoutBien.php?id_bien_immobilier='.trim($id_bien_immobilier);
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