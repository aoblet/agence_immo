<?php
	function getBanniereConnexion($session){
		return <<<HTML
		<div id="bar-connected">
			<div class="container" >
				<div class="row">
					<div class="col-md-6">
						<a href="./user/dashboardGateway.php"><i class="fa fa-th"></i>Accéder à mon dashboard</a>
					</div>

					<div class="col-md-6">
						<p>
							<span>{$session['prenom_personne']} {$session['nom_personne']}</span>
							<img src="{$session['photo_personne']}">
							<a href="user/deconnexion.php" class="fa-deconnect"><i class="deco-moove fa fa-unlock"></i></a>
						</p>

					</div>

				</div>
			</div>

		</div>
HTML;
	}
