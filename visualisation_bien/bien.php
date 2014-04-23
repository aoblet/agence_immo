<?php
	session_start();
	require_once('../functions_php/recherche_biens/search.php');
	require_once('../functions_php/recherche_biens/getUtils_html.php');
	require_once('../functions_php/user_utils/getUtils_html.php');
	require_once('../functions_php/user_utils/getUtils.php');
	require_once('../functions_php/recherche_biens/affichage_result.php');


	$mail_message = 'Email';
	$password_message='Mot de passe';
	$session_to_getBanniere ='';

	if(isset($_SESSION['id_personne']) && !empty($_SESSION['id_personne'])){
		$session_to_getBanniere = $_SESSION;
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

	//dernier parmam:come_from pour redirection
	$formulaire_connexion = getFormulaireConnexion($mail_message,$password_message,basename(__FILE__));
	$banniere_header = getBanniere($session_to_getBanniere,basename(__FILE__));

?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="apple-touch-icon" href="icon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

		<title>Agence Immo</title>

		<link rel='stylesheet' href='../css/pushy.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../css/bootstrap.min.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../css/flexslider.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../css/default.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../css/styles.css' type='text/css' media='all' />



		<link rel='stylesheet' href='../css/fonts/font-awesome.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../css/fonts/font-awesome-animation.css' type='text/css' media='all' />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="../js/jquery-ui-1.10.4.min.js"></script>


		<script type="text/javascript" src="../js/modernizr.custom.js"></script>
		<script type="text/javascript" src='../js/main.js'></script>
		<script type="text/javascript" src='../js/multiple.js'></script>
	</head>

	<body>

	<header>
		<?php echo $banniere_header ?>
		<?php echo $formulaire_connexion ?>
	</header>



	

	<section class="bg-grey first-section" >





		<div class="container margin60">
			<div class="row">
				<div class="col-md-12">
					<div class="bien-spe-title bg-white">
						<div class="bien-spe-title-alert">
							<h4>Excusivité</h4>
						</div>
						<h2>Appartement à vendre <span>47 m&sup2;</span></h2>


						<div class="bien-spe-title-back">
							<a href=""><i class="fa fa-reply"></i> Retour</a>
						</div>

						<h3>340 000 €</h3>
					</div>
				</div>
			</div>
		</div>

		<div class="container margin30">
			<div class="row">
				<div class="col-md-6">
					<div class="bien-spe-desc bg-white">

						


						<h4>Description du bien</h4>
						<p class="desc-p-bien">Dans un bel immeuble de la rue du Docteur Heulin, aux portes des Batignolles, un très bel appartement en étage sur cour, calme et ensoleillé, comprenant : entrée, séjour, 2 chambres dont une avec vue dégagée sans vis à vis, et la seconde munie d'un dressing, cuisine séparée, salle d'eau et wc séparés (nous consulter pour plus de détails). </p>

						<h4>Informations techniques</h4>
						<div class="row">
							<div class="col-md-6">
								<p><i class="fa fa-map-marker"></i><span>Adresse :</span> 4 rue du php, 75002 Paris</p>
								<p><i class="fa fa-expand"></i><span>Surface :</span> 47 m&sup2;</p>
								<p><i class="fa fa-picture-o"></i><span>Jardin :</span> 9 m&sup2;</p>
								<p><i class="fa fa-th-large"></i><span>Nb pièces :</span> 5</p>
								<p><i class="fa fa-road"></i><span>Stationnement :</span> Garage (ou parking)</p>
								<p><i class="fa fa-ellipsis-v"></i><span>Nb étages :</span> 4</p>
								<p><i class="fa fa-arrows-v"></i><span>Num étages :</span> 3ème</p>
								<p><i class="fa fa-sort"></i><span>Ascenseur :</span> non</p>
								
							</div>
							<div class="col-md-6">
								
								<p><i class="fa fa-fire"></i><span>Chauffage :</span> Individuel</p>
								<p><i class="fa fa-signal"></i><span>Indice energetique :</span> A</p>
								<p><i class="fa fa-signal"></i><span>Conso energ mini :</span> 100</p>
								<p><i class="fa fa-signal"></i><span>Conso energ maxi :</span> 200</p>

								<p><i class="fa fa-globe"></i><span>Effet de serre :</span> B</p>
								<p><i class="fa fa-globe"></i><span>E.d.S mini :</span> 200</p>
								<p><i class="fa fa-globe"></i><span>E.d.S maxi :</span> 700</p>
								
								
							</div>
						</div>



					</div>
				</div>

				<div class="col-md-6">
					<div class="bien-spe-contact bg-white">
						<div class="bien-spe-desc-mel">En ligne depuis 5 jours</div>
						<a href="mailto:contact@fakeagency.com">Contacter l'agence</a>
					</div>

					<div class="bien-spe-pic bg-white margin30">
						
						<div class="flexslider">
							<ul class="slides">
								<li>
									<img src="../img/biens/1.jpg" />
								</li>
								<li>
									<img src="../img/biens/2.jpg" />
								</li>
								<li>
									<img src="../img/biens/3.jpg" />
								</li>
							</ul>
						</div>
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

<script src="../js/pushy.js"></script>
<script defer src="../js/flexslider.js"></script>


<script type="text/javascript">


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


</body>
</html>