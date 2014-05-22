<?php
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php'); 
	require_once(dirname(__FILE__).'/../../../enum/enum_type_biens.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/recherche_biens/search.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/dashboard/employe/affichage_result.php'); 
	session_start();

	if(empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != EMPLOYE){
		header('Location: '.getPathRoot().'index.php',false,301);
		die();
	}

	if(empty($_GET['id_bien_immobilier']) || !getLegitimite($_SESSION,$_GET['id_bien_immobilier'])){
		header('Location: '.getPathRoot().'user/dashboardGateway.php',false,301);
		die();
	}

	$statut_reponse=''; $message_reponse='';
	if(!empty($_GET['statut_reponse']) && ($_GET['statut_reponse'] == 'ok' || $_GET['statut_reponse'] =='fail')){
		$statut_reponse= $_GET['statut_reponse'];
		if(!empty($_GET['message_reponse']))
			$message_reponse=urldecode(utf8_decode($_GET['message_reponse']));
	}

	$infos_adresse = getInfosAdresse($_GET['id_bien_immobilier']);
	$adresse_rue = $infos_adresse['rue'];
	$adresse_numero_rue = $infos_adresse['numero_rue'];
	$adresse_ville = $infos_adresse['ville'];
	$adresse = $adresse_numero_rue.' '.$adresse_rue.', '.$adresse_ville.' ( '.$infos_adresse['code_postal'].' )';
		
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
		<link rel="stylesheet" href="../../../css/jquery-ui.css">
  		<script src="../../../js/jquery-ui.js"></script>
  		<script>
			  $(function() {
			    $( "#datepicker" ).datepicker();
			  });
		 </script>

	</head>

	<body>

		<?php echo getModalsInfos();?>
		<header>
			<?php echo getBanniereConnected($_SESSION) ?>
			<?php echo getBanniereDash() ?>
		</header>


	<section class="bg-grey first-section" style="padding-top:60px;">


		<div class="container ">
			<div class="row">

				<?php echo getMenuOnBienEmploye($_GET['id_bien_immobilier']) ?>

				<div class="col-md-9">
					
					<div class="titlepage bg-blue">
						<h2>Ajouter un historique sur le bien<span class='indication-bien-dash'><?php echo $adresse?></span></h2>
					</div>
		
					<div class="form-add-pai bg-white margin30">
						<form name='form_ajout_historique' method='POST' action='traitement/ajoutHistoriqueTraitement.php'>
							<h4>Les informations primaires sont obligatoires.</h4>

							<div class="form-champ">
								<label>Type d'opération</label>
								<div class="form-add-radio">
									<input type="radio" id="entree_id" checked name="type_operation" value="entree" /> 
									<span><label for="entree_id" style='line-height:0px;font-weight:normal;margin-top:12px'>Entree</label></span>

									<input type="radio" id="depense_id" name="type_operation" value="depense" /> 
									<span><label for="depense_id" style='line-height:0px;font-weight:normal;margin-top:12px'>Depense</label></span>
								</div>
							</div>

							<div class="form-champ">
								<label>Intitulé de l'historique</label>
								<input style="max-width:500px;" type="text" name="nom_action" value="" required="required" placeholder="Frais d'agence"/>
							</div>
							
							<div class="form-champ">
								<label>Date et montant de l'historique</label>
								<input type="text" class="form-add-date" name="date" id="datepicker" value="" placeholder="date"/>
								<input style="margin-top:5px;" type="number" class="form-add-montant" name="prix" value="" placeholder="montant en €"/>
							</div>

							<div class="form-champ">
								<label>Description de l'historique</label>
								<textarea name="descriptif" value="" required="required" placeholder="Description du bien"></textarea>
							</div>


							<div class="form-champ">
								<label>Imputer le locataire ? Si dépense</label>

								<div class="form-add-radio">
									<input type="radio" id="imput-loc-oui" checked name="imputation" value="1" /> 
									<span><label for="imput-loc-oui" style='line-height:0px;font-weight:normal;margin-top:12px'>Oui</label></span>

									<input type="radio" id="imput-loc-non" name="imputation" value="0" /> 
									<span><label for="imput-loc-non" style='line-height:0px;font-weight:normal;margin-top:12px'>Non</label></span>
								</div>

							</div>

							


							<div class="form-champ-sub" style="margin-left:20px;width:100%;display:inline-block">
								<input style="margin-left:0px;" type="submit" name="connexion" value="Ajouter l'historique" />
								<input type="hidden" name='id_bien_immobilier' value='<?php echo $_GET['id_bien_immobilier'] ?>'/>
								<input type='hidden' name='statut_reponse' value='<?php echo $statut_reponse ?>'/>
								<input type='hidden' name='statut_message' value="<?php echo $message_reponse?>"/>
							</div>



						</form>
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



<script type="text/javascript">
<?php echo getJsForModalAjoutBien('form_ajout_historique');?>

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