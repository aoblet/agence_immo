<?php
	/**
	*	Partie commune aux dashs
	**/
	require_once(dirname(__FILE__).'/../getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php');

	function getBanniereDash(){
		$link_home = getPathRoot();
		return <<<HTML
			<h1>Dashboard</h1>

			<div class="container" >
				<div class="row">
					<div class="col-md-12">
						<div class="dash-nav">
							<ul>
								<li><a href="$link_home" class="button-home"><i class="fa fa-reply"></i>Retour au site</a></li>
							</ul>
						</div>
					</div>
				</div>

			</div>

HTML;
	}

	function getMenuAccueil($type_personne){
		$infos_persos_link = getPathRoot().'user/dashboard/';
		if($type_personne == LOCATAIRE)
			$infos_persos_link.="locataire/infos.php";
		else if($type_personne == PROPRIETAIRE)
			$infos_persos_link.="proprietaire/infos.php";
		else if($type_personne == EMPLOYE)
			$infos_persos_link.="employe/infos.php";
		else
			$infos_persos_link='';

		return <<<HTML
		<div class="col-md-3" >
			<div class="dash-menu bg-white" >
				<h4>MENU</h4>
				<a href="$infos_persos_link" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="./messages.php" class="button-home"><i class="fa fa-envelope"></i>Mes messages</a>
			</div>
		</div>

HTML;
	}

	function getMenuOnMessage($type_personne){
		$infos_persos_link = getPathRoot().'user/dashboard/';
		$mes_messages_link = getPathRoot().'user/dashboard/';
		if($type_personne == LOCATAIRE){
			$infos_persos_link.="locataire/infos.php";
			$mes_messages_link.="locataire/messages.php";
		}
		else if($type_personne == PROPRIETAIRE){
			$infos_persos_link.="proprietaire/infos.php";
			$mes_messages_link.="proprietaire/messages.php";

		}
		else if($type_personne == EMPLOYE){
			$infos_persos_link.="employe/infos.php";
			$mes_messages_link.="employe/messages.php";
		}
		else{
			$infos_persos_link='';
			$mes_messages_link="";
		}

		return <<<HTML
		<div class="col-md-3" >
			<div class="dash-menu bg-white" >
				<h4>MENU</h4>
				<a href="$infos_persos_link" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="$mes_messages_link" class="button-home"><i class="fa fa-envelope"></i>Mes messages</a>
				<a href="./" class="button-home"><i class="fa fa-reply"></i>Retour au Dash</a>
				<!--<a href="" class="button-home"><i class="fa fa-envelope"></i>Contacter l'agence</a>-->
			</div>
		</div>

HTML;
	}

	

