<?php

	function getBlocConnect($session){
		return <<<HTML
		<div id="fixed-connect">
			<div id="fixed-connect-pic">
				<img src="{$session['photo_personne']}" width="90" height="90">
			</div>

			<div id="fixed-connect-menu" class="bg-grey">
				<a href="index.html"><i class="fa fa-user"></i></a>
				<a href="index.html"><i class="fa fa-envelope"></i></a>
				<a href="index.html"><i class="fa fa-gears"></i></a>
			</div>
		</div>
HTML;
	}

	function getConnectMessage($is_connect){
		if($is_connect){
			return <<<HTML
			<div class="no-count">
				<h4>A votre écoute</h4>
				<p>	Nous sommes heureux de vous compter parmis nous <strong>{$_SESSION['nom_personne']} {$_SESSION['prenom_personne']}.</strong> 
					Vous avez un doute, une interrogation, une suggestion? Contactez nous! Nous serons ravis de vous aider.</p>
				<a href="index.php"><i class="fa fa-envelope"></i>Nous contacter</a>
				
				<div id="connect-mobile" class="close-form only-mobile">
					<a href=""><i class="fa fa-close"></i>FERMER LA FENETRE</a>
				</div>
			</div>
HTML;
		}
		
		return <<<HTML
		<div class="no-count">
			<h4>Pas encore de compte ?</h4>
			<p>Pour proposer ou gerer des biens, vous devez disposer d'un compte. Il vous permettra nottament d'acceder aux statistiques mensuelles, et d'obtenir un contact privilégié avec l'agence.</p>
			<a href="inscription.html"><i class="fa fa-unlock"></i>Demander la création d'un compte</a>

			<div id="connect-mobile" class="close-form only-mobile">
				<a href=""><i class="fa fa-close"></i>FERMER LA FENETRE</a>
			</div>
		</div>
HTML;
	}