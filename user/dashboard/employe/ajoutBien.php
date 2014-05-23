<?php
	require_once(dirname(__FILE__).'/../../../enum/enum_type_user.php'); 
	require_once(dirname(__FILE__).'/../../../enum/enum_type_biens.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils_html.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/user_utils/dashboard/getUtils.php'); 
	require_once(dirname(__FILE__).'/../../../functions_php/dashboard/employe/affichage_result.php'); 
	session_start();

	if(empty($_SESSION['id_personne']) || $_SESSION['type_personne'] != EMPLOYE){
		header('Location: '.getPathRoot().'index.php',false,301);
		die();
	}

	$statut_reponse=''; $message_reponse='';
	if(!empty($_GET['statut_reponse']) && ($_GET['statut_reponse'] == 'ok' || $_GET['statut_reponse'] =='fail')){
		$statut_reponse= $_GET['statut_reponse'];
		if(!empty($_GET['message_reponse']))
			$message_reponse=urldecode(utf8_decode($_GET['message_reponse']));
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

		<?php echo getModalsInfos();?>
		<header>
			<?php echo getBanniereConnected($_SESSION) ?>
			<?php echo getBanniereDash() ?>
		</header>


	<section class="bg-grey first-section" style="padding-top:60px;">


		<div class="container ">
			<div class="row">

				<?php echo getMenuAccueil($_SESSION['type_personne'],false) ?>

				<div class="col-md-9">
					
					<div class="titlepage bg-blue">
						<h2>Ajouter un bien sur le site</h2>
					</div>
		


					<div class="form-add bg-white margin30" style='min-height:1880px'>
						<form action='traitement/ajoutBienTraitement.php' method='POST' name='form_ajout_bien'>
							<h4>Les informations primaires sont obligatoires.</h4>
							<div class="form-champ">
								<label>Type d'opération</label>

								<div class="form-add-radio">
									<input type="radio" id="location_id" checked name="vente_location" value="location" /> 
									<span><label for='location_id' style='line-height:0px;font-weight:normal;margin-top:12px'>Location</label></span>

									<input type="radio" id="vente_id" name="vente_location" value="vente" /> 
									<span><label for="vente_id" style='line-height:0px;font-weight:normal;margin-top:12px'>Vente</label></span>
								</div>

							</div>
							
							<div class="form-champ">
								<label>Proprietaire</label>
								<?php echo getSelectProprietaireEmploye('proprietaire')?>
							</div>

							<div class="form-champ">
								<label>Prix</label>
								<input style="max-width:500px;" type="text" name="prix" value="" required="required" placeholder="€"/>
							</div>

							<div class="form-champ">
								<label>Supérficie (en m&sup2;)</label>
								<input style="max-width:500px;" type="text" name="superficie" value="" required="required" placeholder="0"/>
							</div>

							<div class="form-champ">
								<label>Nombre de pièces</label>
								<input style="max-width:500px;" type="text" name="nb_pieces" value="" required="required" placeholder="0"/>
							</div>

							<div class="form-champ">
								<label>Type de bien</label>

								<div class="form-add-radio">
									<?php echo getSelectTypeBienEmploye('type_bien_radio',$ARRAY_TYPE_BIEN); ?>
								</div>

							</div>


							<div class="form-champ">
								<label>Adresse du bien</label>
								<input style="max-width:500px;" type="text" name="numero_rue" value="" required="required" placeholder="20"/>
								<input style="max-width:500px;" type="text" name="rue" value="" required="required" placeholder="rue du php"/>
							</div>

							<div class="form-champ">
								<label>Ville</label>
								<input style="max-width:500px;" type="text" name="ville" value="" required="required" placeholder="Paris"/>
							</div>

							<div class="form-champ">
								<label>Code Postal</label>
								<input type="text" class="form-add-cp" name="code_postal" value="" required="required" placeholder="75000"/>
								<?php echo getSelectDepartementsEmploye('departement') ?>
							</div>

							

							<div class="form-champ">
								<label>Description du bien</label>
								<textarea name="description" value="" required="required" placeholder="Description du bien"></textarea>
							</div>

							
							

							<div class="form-add-tech">
							<h4>Toutes les informations techniques ne sont pas obligatoires.</h4>


							<div class="form-champ">
								<label>Parking ?</label>

								<div class="form-add-radio">
								<input type="radio" id="garage_oui" name="parking" value="1" /> 
								<span for="garage_oui">Oui</span>

								<input type="radio" id="garage_non" name="parking" value="0" /> 
								<span for="garage_non">Non</span>
								</div>

							</div>

							<div class="form-champ">
								<label>Jardin si maison(surface en m&sup2;)</label>
								<input style="max-width:120px;" name='superficie_jardin' type="number" value="0"/>
							</div>


							



							<h4 style="font-style:italic;margin-top:20px;">Energie :</h4>

							<div class="form-champ">
								<?php echo getSelectTypeChauffageEmploye('chauffage') ?>
							</div>

						
						


							<div class="form-champ">
								<label>Consommations:</label>
								<?php echo getSelectConsosEnergetiqueEmploye('energetique'); ?>
								<?php echo getSelectConsosGazEmploye('gaz'); ?>								
							</div>


							<div class="form-si-appt">
								<h4 style="font-style:italic;padding-bottom:0px;">Si appartement :</h4>
								
								<div class="form-champ" style="margin-top:0px;">
									<label>Nombre d'étages</label>
									<input type="number" name="nb_etage" style='max-width:400px' value="0"/>
								</div>

								<div class="form-champ">
									<label>Étage et numéro d'appartement</label>
									<input type="number" class="form-add-nom" name="etage" value="" placeholder="étage"/>
									<input type="number" class="form-add-prenom" name="numero_appartement" value="" placeholder="numéro"/>
								</div>

								<div class="form-champ">
									<label>Ascenseur ?</label>

									<div class="form-add-radio">
									<input type="radio" id="ascenseur_oui_id" name="ascenseur" value="1" /> 
									<span for="ascenseur">Oui</span>

									<input type="radio" id="ascenseur_non_id" name="ascenseur" value="0" /> 
									<span for="ascenseur">Non</span>
									</div>

								</div>
							</div>


							</div>

							<div class="form-champ-sub">
								<input type="submit" name="connexion" value="Ajouter le nouveau bien" />
							</div>
							<input type='hidden' name='statut_reponse' value='<?php echo $statut_reponse ?>'/>
							<input type='hidden' name='statut_message' value="<?php echo $message_reponse?>"/>
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



</div>



<script type="text/javascript">
<?php echo getJsForModalAjoutBien('form_ajout_bien');?>

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