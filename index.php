<?php
	session_start();
	require_once('functions_php/user_utils/getUtils_html.php');

	//bloc connexion haut && nom prenom affichés;
	// remplacer les liens en temps voulu
	// armand, pourrais tu voir quand le gars est co, ce qu'on pourrai mettre ? merci.
	// j'ai essayé quelque trucs mais bof
	if(isset($_SESSION['id_personne']) && !empty($_SESSION['id_personne'])){

		$div_connect = getBlocConnect($_SESSION);
		$div_connect_message = getConnectMessage(true);

		$div_connect_info_deco = <<<HTML
				<div class='form-champ2'>
					<h4>Bienvenue {$_SESSION['login']}</h4>
					<p>{$_SESSION['type_personne']}</p>
					<a href='user/deconnexion.php'><i class="fa fa-unlock"></i></a>
				</div>	
HTML;

	}
	// de base
	else{
		$div_connect='';
		$login='Login'; $password = 'Mot de passe';
		$div_connect_message = getConnectMessage(false);

		if(isset($_GET['err_compte']) && $_GET['err_compte'] == 'wrong_login_password'){
			$login = 'Mauvais login';
			$password='Mauvais mot de passe';
		}
		elseif (isset($_GET['err_compte']) && $_GET['err_compte'] == 'wrong_use') {
			$login = "Mauvaise utilisation du script";
			$password = $login;			
		}

		$div_connect_info_deco = <<<HTML
			<h4>Identification</h4>
			<form name='login_user' action="user/login.php" method='POST'>
				<div class="form-champ1">
					<i class="fa fa-user"></i>
					<input type="text" name="login" value="" required="required" placeholder="$login"/>
				</div>
				<div class="form-champ2">
					<i class="fa fa-key"></i>
					<input type="password" name="password" value="" required="required" placeholder="$password"/>
				</div>
				<input type="submit" name="connexion" value="Connexion" />
			</form>
HTML;
	}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="apple-touch-icon" href="icon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

		<title>Agence Immo</title>

		<link rel='stylesheet' href='css/pushy.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/flexslider.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/bootstrap.min.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/default.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/styles.css' type='text/css' media='all' />



		<link rel='stylesheet' href='css/fonts/font-awesome.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/fonts/font-awesome-animation.css' type='text/css' media='all' />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="js/jquery-ui-1.10.4.min.js"></script>


		<script type="text/javascript" src="js/modernizr.custom.js"></script>
		<script type="text/javascript" src='js/main.js'></script>
		<script type="text/javascript" src='js/multiple.js'></script>
	</head>

	<body>


		<?php echo $div_connect ?>


		<nav class="pushy pushy-left">
            <ul>
                <li><a href="index.html" class="button-home"><i class="fa fa-home"></i></a></li>
				<li><a href="#">Acheter</a></li>
				<li><a href="#">Vendre</a></li>
				<li><a href="#">Louer</a></li>
				<li><a href="#">Faire gerer</a></li>
            </ul>
        </nav>
        <div class="site-overlay"></div>

<div class="all-wrap">



		<section class="slider">
			<div class="flexslider">
				<ul class="slides">
					<li>

						<img src="img/slides/1_.jpg" />
					</li>
					<li>
						<img src="img/slides/2_.jpg" />
					</li>
					<li>
						<img src="img/slides/1_.jpg" />
					</li>
				</ul>
			</div>
		</section>



		<header>
			<div class="container" >


				<div class="row">

					<div class="col-md-12">
						<div id="menu">

							<ul id="menu-nav" class="only-desktop">
								<li><a href="index.html" class="button-home"><i class="fa fa-home"></i></a></li>
								<li><a href="#">Acheter</a></li>
								<li><a href="#">Vendre</a></li>
								<li><a href="#">Louer</a></li>
								<li><a href="#">Faire gerer</a></li>
							</ul>


							<a href="#" id="connect" class="only-desktop">
								<i class="fa fa-unlock-alt"></i>Mon compte
							</a>



							<a href="#" id="nav-mobile-button" class="only-mobile menu-btn">
								<i class="fa fa-bars"></i>
							</a>

							<a  id="connect-mobile" class="only-mobile">
								<i class="fa fa-unlock-alt"></i>Mon compte
							</a>
						</div>

						

					</div>

				</div>
			</div>

			<div id="connect-form" style="display:none;">
				<div class="container" >
					<div class="row">
						<div class="col-md-3">
							<?php echo $div_connect_info_deco; ?>
						</div>
						<div class="col-md-3">
							<?php echo $div_connect_message ;?>
						</div>

						<div class="col-md-3 hide980 only-desktop">
						</div>

						<div class="col-md-3 hide980 only-desktop">
						</div>

						

						

					</div>
				</div>
			</div>

		</header>



		<!-- <div id="navigation-mobile">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<ul id="menu-nav-mobile">
							<li class="current-menu-ancestor"><a href="#">Accueil</a></li>
							<li><a href="#">Acheter</a></li>
							<li><a href="#">Vendre</a></li>
							<li><a href="#">Louer</a></li>
							<li><a href="#">Faire gérer</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div> -->



		<section class="bg-grey first-section" >


			<div class="container margin60">


				<div class="row">


					<div class="col-md-3">
						
						<div class="bg-white home-form-search-left">
							<h4>Actualités</h4>
							<p><strong>Immobilier :</strong> c'est le moment de vendre !</p>
							
						</div>

						<div class="bg-white home-form-search-left">
							<h4>Infos Pratiques</h4>
							<a><i class="fa fa-key"></i>Dernières exclus</a>
							<a><i class="fa fa-envelope"></i>Contacter l'agence</a>
						</div>
						
					</div>

					<div class="col-md-9">
						<div class="bg-white home-form-search">
							<h4>Rechercher par critères</h4>

							<form id="search-form" action='result.php' method='GET' name='search_biens'>								


								<div id="form-col2" class="col-md-4 form-col2">
									<p>
										<input type="radio" name="choice" value="vente" id="acheter" /> 
										<label class="choice" for="acheter">Acheter</label>
									</p>
									<p>
										<input type="radio" name="choice" value="location" id="louer" />
										<label class="choice" for="louer">Louer</label>
									</p>

									<p>
										<select id="type" name="type_biens[]" multiple="multiple">
											<option value="maison">Maison</option>
											<option value="appartement">Appartement</option>
											<option value="immeuble">Immeuble</option>
											<option value="garage">Garage</option>
										</select>
									</p>
									<p>
										<input type="text" name="ville" value="Paris">
										<!-- <label>~</label>
										<select>
											<option selected> 5 km </option>
											<option> 20 km </option>
											<option> 50 km </option>
										</select> -->
									</p>
								</div>


								<div id="form-col3" class="col-md-4 form-col3">
									<p>
										<label>Budget</label><br>
										<input type="number" name="budget_mini" id="bud" value="0">
										<label> à </label>
										<input type="number" name="budget_maxi" id="bud" value="400">
										<label> € </label>
									</p>
									<p class="margin30">
										<label>Nombre de pièces</label><br>
										<input type="number" name="nb_pieces" id="room" value="3">
									</p>
								</div>
								
								<div id="form-col1" class="col-md-4 form-col1">
									<p>
										Un affinement de recherche comprenant surface, garage, jardin vous sera proposé selon les différents résultats de votre recherche.
									</p>
									
									<input type="submit" name="submit" id="search" value="Rechercher">

									
								</div>


								
								

							</form>



							



						</div>
						
					</div>

				</div>
			</div>



		</section>



		<section class="bg-grey">


			<div class="container margin30 ">


				<div class="row">

					<div class="col-md-12 ">

						<div class="bg-blue last-ann-title">
							Nos annonces récentes
						</div>
						
					</div>

				</div>

			</div>


			<div class="container margin30">


				<div class="row">

					<div class="col-md-3">
						<div class="bg-white last-ann">
							<div class="last-ann-pic" style="background:url(img/plans/1.jpg) top center no-repeat;">

							</div>
							<div class="last-ann-desc bg-blue">
								Annonce récente
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="bg-white last-ann">
							<div class="last-ann-pic" style="background:url(img/plans/2.jpg) top center no-repeat;">

							</div>
							<div class="last-ann-desc bg-blue">
								Annonce récente
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="bg-white last-ann">
							<div class="last-ann-pic" style="background:url(img/plans/3.jpg) top center no-repeat;">

							</div>
							<div class="last-ann-desc bg-blue">
								Annonce récente
							</div>
						</div>
					</div>

					<div class="col-md-3">
						<div class="bg-white last-ann">
							<div class="last-ann-pic" style="background:url(img/plans/4.jpg) top center no-repeat;">

							</div>
							<div class="last-ann-desc bg-blue">
								Annonce récente
							</div>
						</div>
					</div>

				</div>
			</div>



		</section>

		<section class="bg-grey">
			<div class="container margin60">


				<div class="row">

					<div class="col-md-6">

						<div class="bg-white home-about">
							<h4>En savoir+ sur notre agence</h4>
							<p>
								Fake Agency est une agence immobilière créée en avril 2014 par les collaborateurs Armand Biteau et Alexis Oblet. They had a dream that one day every house shall be inhabited, and every appartment shall be rented.
							</p>


							<p>
								<strong>Vous désirez contribuer à l'objectif ?</strong>
							</p>
							<a href=""><i class="fa fa-plus"></i>Proposer un bien</a>
							<a href=""><i class="fa fa-plus"></i>Rechercher un bien</a>
							<a href=""><i class="fa fa-plus"></i>Gérer mes biens</a>
							
						</diV>
					</div>

					<div class="col-md-6">


						<div class="bg-white home-contact">
							<h4>Nous contacter</h4>
							<p><a><i class="fa fa-phone"></i>01 45 38 49 02</a></p>
							<p><a><i class="fa fa-envelope"></i>contact@agence.com</a></p>
						</div>


						<div class="bg-white home-maps">
							<div id="map-canvas"></div>
						</div>

						

					</div>

				</div>

			</div>

		</section>

		<section class="bg-grey" style="min-height:100px;">
			
		</section>




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



</div>

 		<script src="js/pushy.js"></script>
		<script defer src="js/flexslider.js"></script>

		<script type="text/javascript">


		animheader = function(){

	$(window).scroll(function(){
		var $this = $(this),
		pos   = $this.scrollTop();


		if (pos > 300){
			$('header').addClass('menu-small');
			$('.first-section').addClass('pad-top');

		} else {
			$('header').removeClass('menu-small');
			$('.first-section').removeClass('pad-top');
		}
	});
};



$(document).ready(function() {
	animheader();
});


		<?php 
			if(isset($_GET['err_compte']) && !empty($_GET['err_compte']) && ($_GET['err_compte'] == 'wrong_login_password' || $_GET['err_compte'] == 'wrong_use'))
				echo "$('#connect-form').slideToggle('fast');";
		?>
		var open_menu = 0;
		$( "#connect").click(function() {
			$('#connect-form').slideToggle('fast');
		});

		$( "#connect-mobile").click(function() {
			$('#connect-form').slideToggle('fast');
		});



		$(document).ready(function(){
			$("#type").multiselect();
		});


		$(window).load(function(){
			$('.flexslider').flexslider({
				animation: "slide",
				start: function(slider){
					$('body').removeClass('loading');
				}
			});
		});
		</script>


		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript">





		function initialize() {

			var styles = [
			{
				"featureType": "administrative.locality",
				"stylers": [
				{ "visibility": "off" }
				]
			},{
				"featureType": "landscape",
				"stylers": [
				{ "color": "#eaeaea" }
				]
			}

			];


			var styledMap = new google.maps.StyledMapType(styles, {name: "Gmap stylée"});

			var myLatlng = new google.maps.LatLng(48, -1);

			var mapOptions = {
				center: new google.maps.LatLng(48, -1),
				zoom: 15,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false
			};

			var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

			map.mapTypes.set('map_style', styledMap);
			map.setMapTypeId('map_style');



			var marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
				title: 'Idéis'
			});

		}

		google.maps.event.addDomListener(window, 'load', initialize);
		</script>


	</body>
	</html>
	