<?php

	function getBanniereDash(){
		$link_home = getPathRoot();
		return <<<HTML
		<header>
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
		</header>

HTML;
	}

	function getMenuAccueil(){

		return <<<HTML
		<div class="col-md-3" >
			<div class="dash-menu bg-white" >
				<h4>MENU</h4>
				<a href="" class="button-home"><i class="fa fa-user"></i>Mes données personnelles</a>
				<a href="" class="button-home"><i class="fa fa-envelope"></i>Contacter l'agence</a>
			</div>
		</div>

HTML;
	}

