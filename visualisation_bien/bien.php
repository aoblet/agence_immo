<?php
	session_start();
	require_once('../functions_php/recherche_biens/search.php');
	require_once('../functions_php/recherche_biens/getUtils_html.php');
	require_once('../functions_php/user_utils/getUtils_html.php');
	require_once('../functions_php/user_utils/getUtils.php');
	require_once('../functions_php/recherche_biens/affichage_result.php');
	require_once('../functions_php/visualisation_bien/affichage_result.php');


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

	$id_bien_immobilier ='';
	if(isset($_GET['id_bien_immobilier']) && !empty($_GET['id_bien_immobilier']) && is_numeric($_GET['id_bien_immobilier']))
		$id_bien_immobilier = $_GET['id_bien_immobilier'];

	$is_for_landa = true;
	$affichage_result = affichage_base_visu($id_bien_immobilier,$is_for_landa);

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


	<?php echo $affichage_result ?>

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