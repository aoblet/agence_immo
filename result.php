<?php
	session_start();
	require_once('functions_php/recherche_biens/search.php');
	require_once('functions_php/user_utils/getUtils_html.php');

	$div_connect='';
	if(!empty($_SESSION['id_personne'])){
		$div_connect=getBlocConnect($_SESSION);
	}

	// messages connect etc ...

	$opts = formToArrayOpt($_GET);
	var_dump($opts);
	$res = searchBase($opts);
	// to continue

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
						<h4>Identification</h4>
						<form>
						<div class="form-champ1">
							<i class="fa fa-user"></i>
							<input type="text" name="email" value="" required="required" placeholder="Email"/>
						</div>
						<div class="form-champ2">
							<i class="fa fa-key"></i>
							<input type="password" name="pwd" value="" required="required" placeholder="Mot de passe"/>
						</div>
						<input type="submit" name="connexion" value="Connexion" />
						
						</form>
						</div>

						<div class="col-md-3">
							<div class="no-count">
							<h4>Pas encore de compte ?</h4>
							<p>Pour proposer ou gerer des biens, vous devez disposer d'un compte. Il vous permettra nottament d'acceder aux statistiques mensuelles, et d'obtenir un contact privilégié avec l'agence.</p>
							<a href="inscription.html"><i class="fa fa-unlock"></i>Demander la création d'un compte</a>
							
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

		</header>

<section class="bg-grey first-section" >
		<div class="container" >
			<div class="row">

					<div class="col-md-3  margin60">
						<div id="avanced-search-infos" class="bg-white">

							
						</div>

						<div id="avanced-search" class="bg-white margin30">
							<form>
							<h4>Affiner votre recherche</h4>

							<p>
								<label>Ville</label>
								<input type="text" name="nom" value="" placeholder="Ville"/>
							</p>

							<p>
								<label></label>
								<input type="checkbox" name="jardin_check" value="jardin"><span>Jardin</span><br>
								<input type="checkbox" name="garage_check" value="garage"><span>Garage</span><br>
								<input type="checkbox" name="ascenseur_check" value="ascenseur"><span>Ascenseur</span><br>
								<input type="checkbox" name="parking_check" value="Parking"><span>Parking</span><br>
							</p>

							<p>
								<label>Nombre d'étages</label>
								<input type="number" name="nb_etages" value="3"/>
							</p>
							<p>
								<label>Nombre de pièces</label>
								<input type="number" name="nb_pieces" value="2"/>
							</p>
							<!-- <p>
								<label>Nombre de chambres? y'a pas dans la bd</label>
								<input type="number" name="chambres" value="1"/>
							</p> -->
							<p>
								<input class="affiner" type="submit" name="submit" value="Affiner la recherche"/>
							</p>
						</form>
						</div>
					</div>

					<div class="col-md-9 bg-grey margin60">
						
						<div class="bg-white home-form-search">
							<h4>Votre recherche par critères</h4>

							<form id="search-form">
								<div id="form-col2" class="col-md-4 form-col2">
									<p>
										<input type="radio" name="choice" value="acheter" id="acheter" /> 
										<label class="choice" for="acheter">Acheter</label>
									</p>
									<p>
										<input type="radio" name="choice" value="louer" id="louer" />
										<label class="choice" for="louer">Louer</label>
									</p>

									<p>
										<select id="type" name="type" multiple="multiple">
											<option value="1">Maison</option>
											<option value="2">Appartement</option>
											<option value="3">Immeuble</option>
											<option value="4">Garage</option>
											<option value="5">Commerce</option>
										</select>
									</p>
									<p>
										<input type="text" name="location" value="Paris">
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
										<input type="number" name="bud" id="bud" value="0">
										<label> à </label>
										<input type="number" name="bud" id="bud" value="400">
										<label> € </label>
									</p>
									<p class="margin30">
										<label>Nombre de pièces</label><br>
										<input type="number" name="room" id="room" value="3">
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
						
						
						<div class="margin30 results-title">

								<div class="col-md-6 bg-blue">
									<h4><span>102</span> résultats correspondants</h4>
								</div>

								<div class="col-md-6 bg-white" style="border:none">
									
									<form>
										<p>Trier par
										<select>
										<option>Date</option>
										<option>Prix croissant</option>
										<option>Prix décroissant</option>
										<option>Localité</option>
										</select>
									
								</p>
								</form>	
								</div>
						</div>



						<article class="article-bien margin30">
							<div class="col-md-4 article-bien-pic" style="background:url(img/plans/1.jpg) top center no-repeat;">
								
							</div>
							<div class="col-md-8 bg-white article-bien-desc">
								<div class="bien-title">
									<h4>
									Appartement 49 m&sup2;
									</h4>
									<h5>340 000 €</h5>
								</div>
								<div class="bien-desc">
									<p><i class="fa fa-globe"></i> 75017 Paris</p>
									<p><i class="fa fa-arrows-h"></i> 39 m&sup2;</p>
									<p><i class="fa fa-tachometer"></i> Indice A</p>
								</div>
								<div class="article-bien-contact-agency"><a href=""><i class="fa fa-envelope"></i></a></div>
								<div class="article-bien-contact-agency2"><a href=""><i class="fa fa-bell"></i></a></div>
							</div>
						</article>

						







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



<script type="text/javascript">

var open_menu = 0;
		$( "#connect").click(function() {
			$('#connect-form').slideToggle('fast');
		});

		$( "#connect-mobile").click(function() {
			$('#connect-form').slideToggle('fast');
		});


animheader = function(){

	$(window).scroll(function(){
		var $this = $(this),
		pos   = $this.scrollTop();


		if (pos > 0){
			$('header').addClass('menu-small');
			$('.first-section').addClass('pad-top2');

		} else {
			$('header').removeClass('menu-small');
			$('.first-section').removeClass('pad-top2');
		}
	});
};



$(document).ready(function() {
	animheader();
});


		$(document).ready(function(){
			$("#type").multiselect();
		});
		</script>


	</body>
	</html>