<?php
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils.php');
	require_once(dirname(__FILE__).'/../../../functions_php/dashboard/proprietaire/affichage_result.php');
	require_once(dirname(__FILE__).'/../../../functions_php/dashboard/message_html.php');
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php');
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php');
	session_start();

	//verif securité
	if(!isset($_SESSION['id_personne']) || empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != LOCATAIRE){
		$link_home = getPathRoot().'index.php';
		header('Location: '.$link_home,false,301);
		die();
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

		<link rel='stylesheet' href='../../../css/bootstrap.min.css' type='text/css' media='all' />
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
	<header>
		<?php echo getBanniereConnected($_SESSION) ?>
		<?php echo getBanniereDash() ?>
	</header>

	<section class="bg-grey first-section" style="padding-top:60px;">

		<div class="container ">
			<div class="row">

				<?php echo getMenuOnMessage($_SESSION['type_personne']) ?>
			
				<div class="col-md-9">
					
					<div class="titlepage bg-blue">
						<h2>Mes messages</h2>
					</div>
					<?php echo getListMessagesHTML($_SESSION['id_personne'],$_SESSION['type_personne']);?>
				</div>
			</div>
		</div>

	</section>

	<section class="bg-grey" style="min-height:100px;">

	</section>

	<?php echo getFooter() ?>

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

