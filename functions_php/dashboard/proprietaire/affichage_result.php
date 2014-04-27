<?php
	require_once(dirname(__FILE__).'/../../recherche_biens/search.php');
	require_once(dirname(__FILE__).'/../../recherche_biens/affichage_result.php');

	function getListBiens($id_personne){
		return affichage_base_liste_html(searchForProprioBase($id_personne));
	}

	function getMenuOnBien($id_bien_immobilier){
		//gérer quand le bien est loué : pas d'affichage dans le menu cf visu bien ?
		return <<<HTML
		<div class="col-md-3">
			<div class="dash-menu bg-white">
				<h4>MENU</h4>
				<a href="" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="historique_depense.php?id_bien_immobilier=$id_bien_immobilier" class="button-home"><i class="fa fa-sort-amount-desc"></i>Historique des dépenses</a>
				<a href="historique_entree.php?id_bien_immobilier=$id_bien_immobilier" class="button-home"><i class="fa fa-sort-amount-asc"></i>Historique des recettes</a>
				<a href="" class="button-home"><i class="fa fa-envelope"></i>Contacter l'agence</a>
				<a href="" class="button-home"><i class="fa fa-book"></i>Voir l'annonce du bien</a>
				<a href="./" class="button-home"><i class="fa fa-reply"></i>Retour au Dash</a>
			</div>
		</div>
HTML;
	}

	function getEtatDuBien(){

	}


	