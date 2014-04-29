<?php
	require_once(dirname(__FILE__).'/../../../functions_php/recherche_biens/search.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php');

	require_once(dirname(__FILE__).'/../../../functions_php/dashboard/proprietaire/affichage_result.php');

	session_start();


	if(!isset($_SESSION['id_personne']) || empty($_SESSION['id_personne'])){
		$link_home = getPathRoot().'index.php';
		header('Location: '.$link_home);
		die();
	}

	//verif param bien présent
	if(!isset($_GET['id_bien_immobilier']) || !is_numeric($_GET['id_bien_immobilier'])){
		header('Location: ./index.php');
		die();
	}



	//verif que le bien lui correspond sinon redirection
	$isOK = getLegitimite($_SESSION,$_GET['id_bien_immobilier']);

	if(!$isOK){
		header('Location: ./index.php');
		die();
	}

	$infos = searchForProprioAvance($_GET['id_bien_immobilier']);
	$depenses = isset($infos['infos_historiques_depense']) ? $infos['infos_historiques_depense'] : array();
	$rentrees = isset($infos['infos_historiques_rentree']) ? $infos['infos_historiques_rentree'] : array();

	$graphiqueDepenses = getDepensesGraphique($depenses);
	$graphiqueRentrees = getRentreesGraphique($rentrees);

?>



<!DOCTYPE html>
<html lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="apple-touch-icon" href="icon.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">

		<title>Agence Immo</title>

		<link rel='stylesheet' href='../../../css/pushy.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/bootstrap.min.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/flexslider.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/default.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/dash.css' type='text/css' media='all' />



		<link rel='stylesheet' href='../../../css/fonts/font-awesome.css' type='text/css' media='all' />
		<link rel='stylesheet' href='../../../css/fonts/font-awesome-animation.css' type='text/css' media='all' />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


		<script type="text/javascript" src="../../../js/modernizr.custom.js"></script>
		<script type="text/javascript" src='../../../js/main.js'></script>
		<script type="text/javascript" src='../../../js/Chart.js'></script>
	</head>

	<body>

	<?php echo getBanniereConnexion($_SESSION) ?>
	<?php echo getBanniereDash() ?>


	<section class="bg-grey first-section" style="padding-top:60px;">

		<div class="container ">
			<div class="row">

				<?php echo getMenuOnBien(2) ?>
			
				<div class="col-md-9">
					
					<div class="titlepage bg-blue">
						<h2>Historiques financiers</h2>
					</div>
					<?php echo $graphiqueDepenses ?>
					<?php echo $graphiqueRentrees ?>

				</div>
			</div>
		</div>

	</section>







	<section class="bg-grey" style="min-height:100px;">

	</section>

	<?php echo getFooter() ?>

</div>



<script type="text/javascript">


var open_menu = 0;
$( "#connect").click(function() {
	$('#connect-form').slideToggle('fast');
});

$( "#connect-mobile").click(function() {
	$('#connect-form').slideToggle('fast');
});


</script>


</body>
</html>