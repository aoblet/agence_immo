<?php
	session_start();
	require_once('functions_php/user_utils/getUtils_html.php');
	require_once('functions_php/recherche_biens/getUtils_html.php');

	$mail_message = 'Email';
	$password_message='Mot de passe';
	$session_to_getBanniere='';

	if(isset($_SESSION['id_personne']) && !empty($_SESSION['id_personne'])){
		$session_to_getBanniere=$_SESSION;
	}
	else{
		if(isset($_GET['err_compte'])){
			if($_GET['err_compte'] == "wrong_mail_password"){
				$mail_message='Email ou mot de passe inconnu';
				$password_message='';
			}
			else{
				$mail_message = 'Mauvaise utilisation';
				$password_message=$mail_message;
			}
		}
	}
	$banniere_header = getBanniere($session_to_getBanniere);
	$formulaire_connexion = getFormulaireConnexion($mail_message,$password_message);
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
		<link rel='stylesheet' href='css/bootstrap.min.css' type='text/css' media='all' />
		<link rel='stylesheet' href='css/flexslider.css' type='text/css' media='all' />
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


<!-- <div id="fixed-connect">
	<div id="fixed-connect-pic">
		<img src="img/avatar.png" width="90" height="90">
	</div>

	<div id="fixed-connect-menu" class="bg-grey">
		<a href="index.html"><i class="fa fa-user"></i></a>
		<a href="index.html"><i class="fa fa-envelope"></i></a>
		<a href="index.html"><i class="fa fa-gears"></i></a>
	</div>
</div> -->


		<nav class="pushy pushy-left">
            <ul>
                <li><a href="index.php" class="button-home"><i class="fa fa-home"></i></a></li>
				<li><a href="#">Acheter</a></li>
				<li><a href="#">Vendre</a></li>
				<li><a href="#">Louer</a></li>
				<li><a href="#">Faire gerer</a></li>
            </ul>
        </nav>
        <div class="site-overlay"></div>

<div class="all-wrap">




		<header>
			<?php echo $banniere_header ?>
			<?php echo $formulaire_connexion ?>
		</header>



	

		<section class="bg-grey first-section" >


			<div class="container margin30">


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

							<form id="search-form" action='result.php' method='GET'>					
								<div id="search-form">
								<div id="form-col2" class="col-md-4 form-col2">
									<p>
										<input type="radio" name="type_achat_location" value="vente" id="acheter"/> 
										<label class="choice" for="acheter">Acheter</label>
									</p>
									<p>
										<input type="radio" name="type_achat_location" value="location" id="louer" />
										<label class="choice" for="louer">Louer</label>
									</p>

									<p>
										<select id="type" name="types_bien[]" multiple="multiple">
											<option value="maison" selected>Maison</option>
											<option value="appartement" selected>Appartement</option>
											<option value="immeuble" selected>Immeuble</option>
											<option value="garage" selected>Garage</option>
										</select>
									</p>
									
									<p>
											<?php 
												echo getSelectDepartementsHTML("departement");
											?>

									</p>
								</div>


								<div id="form-col3" class="col-md-4 form-col3">
									<p>
										<label>Budget</label><br>
										<input class="bud" type="number" name="budget_mini" id="budget_mini" placeHolder="Mini" value="">
										<label> à </label>
										<input class="bud" type="number" name="budget_maxi" id="budget_maxi" placeHolder="Maxi" value="">
										<label> € </label>
									</p>
									<p class="margin30">
										<label>Nombre de pièces</label><br>
										<input type="number" name="nb_pieces" id="room" placeHolder='Indifférent' value="">
									</p>
								</div>
								
								<div id="form-col1" class="col-md-4 form-col1">
									<p>
										Pour une précision de recherche plus importante, l'agence vous conseille l'utilisation de la recherche avancée, située à gauche de la page.
									</p>
									
									<input type="submit" name="submit_base" id="search" value="Rechercher">
									<input type='reset'  value='Annuler'>									
								</div>
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

					<?php echo getAnnoncesRecentesLimit4(); ?>

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


// 		animheader = function(){

// 	$(window).scroll(function(){
// 		var $this = $(this),
// 		pos   = $this.scrollTop();


// 		if (pos > 300){
// 			$('header').addClass('menu-small');
// 			$('.first-section').addClass('pad-top');

// 		} else {
// 			$('header').removeClass('menu-small');
// 			$('.first-section').removeClass('pad-top');
// 		}
// 	});
// };



// $(document).ready(function() {
// 	animheader();
// });



		var open_menu = 0;
		<?php 
			if(isset($_GET['err_compte']) && ($_GET['err_compte'] == 'wrong_use' || $_GET['err_compte'] == 'wrong_mail_password' ))
				echo "$('#connect-form').slideToggle('fast');"
		?>

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