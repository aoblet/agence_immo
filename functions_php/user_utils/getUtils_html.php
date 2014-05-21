<?php
	require_once(dirname(__FILE__).'/dashboard/getUtils.php');
	require_once(dirname(__FILE__).'/../dashboard/message_html.php');

	// si changement racine : définir ici
	function getPathRoot(){
		$dir_current_script_using = str_replace("\\", "/", (dirname($_SERVER['PHP_SELF'])) );
		$dir_current_path = explode("/", $dir_current_script_using);
		$reconstruction_path_root='';
		for ($i=1; $i<count($dir_current_path) ; $i++) { 
			$reconstruction_path_root.='/'.$dir_current_path[$i];
			if($dir_current_path[$i] == 'agence_immo')
				break;
		}
		$reconstruction_path_root.='/';
		return $reconstruction_path_root;
	}

	function getDivNotifs($session,$nb_messages_non_lues){
		$link_message = getPathRoot().'user/dashboard/'.trim(strtolower($session['type_personne'])).'/messages.php';

		if(!$nb_messages_non_lues)
			return <<<HTML
			<div id="new-messages" class="bg-white">
				<div class="bottom-new-messages">
					<div class="triangle-message"></div>
					<a href="$link_message">Afficher tous les messages</a>
				</div>
			</div>
HTML;
		return getListeMessagesForNotifs($session);
	}


	// come_from : permet la redirection desirée, deconnexion connexion
	function getBanniereConnected($session,$file_come_from=''){
		$link_photo = getPathRoot().$session['photo_personne'];
		$link_deco  = getPathRoot().'user/deconnexion.php';
		$link_dash  = getPathRoot().'user/dashboardGateway.php';

		if( !($nb_messages_non_lues = getNbMessagesNonLus($session['id_personne'])) )
			$nb_messages_non_lues ='';

		$notifs_div = getDivNotifs($session,$nb_messages_non_lues);
		
		return <<<HTML
		<div id="bar-connected">
			<div class="container" >
				<div class="row">
					<div class="col-md-6">
						<a href="$link_dash"><i class="fa fa-th"></i>Accéder à mon dashboard</a>
					</div>

					<div class="col-md-6">
						<form action="$link_deco" method='POST' name='send_to_deconnexion'>
							<p>
								<span>{$session['prenom_personne']} {$session['nom_personne']}</span>
								<img src="$link_photo" alt='photo_personne'>
								<input type='hidden' name='come_from' value="{$file_come_from}" />
								<a href='#' class="fa-message">
									<i class="fa fa-comments"></i>
									<span>$nb_messages_non_lues</span>
								</a>
								<a href='#' onclick='document.send_to_deconnexion.submit()' class="fa-deconnect" title='Déconnexion'><i class="deco-moove fa fa-unlock"></i></a>
							</p>
							$notifs_div
						</form>
					</div>
				</div>
			</div>
		</div>
HTML;
	}

	function getBanniere($session,$come_from=''){
		$banniere='';
		$display_mon_compte=''; $padding_top='';
		if(isset($session) && !empty($session)){
			$banniere.=getBanniereConnected($session,$come_from);
			$display_mon_compte='display:none';
			$padding_top="padding-top:60px";
		}

		$link_index = getPathRoot().'index.php';
		$link_achat = getPathRoot().'result.php?type_achat_location=vente';
		$link_location = getPathRoot().'result.php?type_achat_location=location';

		$banniere.=<<<HTML
		<div class="container" style="$padding_top">
			<div class="row">
				<div class="col-md-12">
					<div id="menu">
						<ul id="menu-nav" class="only-desktop">
							<li><a href="$link_index" class="button-home"><i class="fa fa-home"></i></a></li>
							<li><a href="$link_achat">Acheter</a></li>
							<!--<li><a href="">Vendre</a></li>-->
							<li><a href="$link_location">Louer</a></li>
							<!--<li><a href="#">Faire gerer</a></li>-->
						</ul>

						<a href="#" id="connect" class="only-desktop" style="$display_mon_compte">
							<i class="fa fa-unlock-alt"></i>Mon compte
						</a>

						<a href="#" id="nav-mobile-button" class="only-mobile menu-btn">
							<i class="fa fa-bars"></i>
						</a>

						<a  id="connect-mobile" class="only-mobile" style="display_mon_compte ">
							<i class="fa fa-unlock-alt"></i>Mon compte
						</a>
					</div>
				</div>
			</div>
		</div>
HTML;
	return $banniere;
	}
	function getFormulaireConnexion($mail_message,$password_message,$file_come_from=''){
		$link_to_root=getPathRoot();
		$link_form_login = $link_to_root.'user/login.php';
		$link_form_inscription = $link_to_root.'inscription.html';

		return <<<HTML
		<div id="connect-form" style="display:none;">
			<div class="container" >
				<div class="row">
					<div class="col-md-3">
						<h4>Identification</h4>

						<form action='$link_form_login' method='POST'>
							<div class="form-champ1">
								<input type="text" name="mail" value="" required="required" placeholder="$mail_message" title="Email"/>
							</div>
							<div class="form-champ2">
								<input type="password" name="password" value="" required="required" placeholder="$password_message" title="Mot de passe"/>
							</div>
							<input type='hidden' name='come_from' value='$file_come_from'>
							<input type="submit" name="connexion" value="Connexion" />
							
						</form>
					</div>

					<div class="col-md-3">
						<div class="no-count">
							<h4>Pas encore de compte ?</h4>
							<p>Pour proposer ou gerer des biens, vous devez disposer d'un compte. Il vous permettra nottament d'acceder aux statistiques mensuelles, et d'obtenir un contact privilégié avec l'agence.</p>
							<a href="$link_form_inscription"><i class="fa fa-unlock"></i>Demander la création d'un compte</a>
							
							<div id="connect-mobile" class="close-form only-mobile">
								<a href=""><i class="fa fa-close"></i>FERMER LA FENETRE</a>
							</div>
						</div>
					</div>

					<div class="col-md-3 hide980 only-desktop">
					</div>

					<div class="col-md-3 hide980 only-desktop">
					</div>

				</div>
			</div>
		</div>
HTML;
	}

	function getFooter(){
		return <<<HTML

		<footer class="bg-blue">
			<div class="container">
				<div class="row">

					<div class="col-md-6">
						<h1>FAKE AGENCY</h1>
					</div>

					<div class="col-md-3">
						<div class="contact-footer">
						<h4>Nous contacter</h4>
							<p><a><i class="fa fa-phone"></i>01 45 38 49 02</a></p>
							<p><a><i class="fa fa-envelope"></i>contact@agence.com</a></p>
							<p><a><i class="fa fa-keyboard-o"></i>Prendre contact avec un conseiller</a></p>
						</div>
					</div>

					<div class="col-md-3">
						<div class="direct-links-footer">
							<h4>Liens directs</h4>
							<a href="">Proposer un bien</a><br>
							<a href="">Rechercher un bien</a><br>
							<a href="">Gérer mes biens</a>
							</div>
					</div>

				</div>
			</div>
		</footer>
HTML;

	}

	function urlEncodeArray(&$array){
		foreach ($array as $key => &$value) {
			$array[$key]=urlencode($value);
		}
	}

	

	
